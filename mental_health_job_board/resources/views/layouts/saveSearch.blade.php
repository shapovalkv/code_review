@if(auth()->check())
    <div class="wrapper-submit flex-middle col-xs-12 col-md-12">
        @if(\Illuminate\Support\Facades\Route::currentRouteName() === 'job.search')
            <button
                class="theme-btn btn-style-seven bg-blue bc-call-modal save-search">{{ __("Save Jobs Search") }}</button>
        @elseif(\Illuminate\Support\Facades\Route::currentRouteName() === 'job.search.practicum')
            <button
                class="theme-btn btn-style-seven bg-blue bc-call-modal save-search">{{ __("Save Practicum Search") }}</button>
        @elseif(\Illuminate\Support\Facades\Route::currentRouteName() === 'candidate.index')
            <button
                class="theme-btn btn-style-seven bg-blue bc-call-modal save-search">{{ __("Save Candidates Search") }}</button>
        @endif
        <a id="clearSearch" class="btn btn-link w-100">{{ __("Clear") }}</a>
    </div>
@endif
@section('modals')
    @if(auth()->check())
        <div class="modal fade login" id="modalSaveSearch" style="z-index: 9999999">
            <div id="modalSaveSearch-modal">
                <div class="login-form default-form">
                    <div class="form-inner">
                        <div class="form-inner">
                            <h3>{{ __("Save search parameters") }}</h3>
                            <div class="form-group">
                                <label>{{__('Search parameters title (you can rename the title as you wish):')}}</label>
                                <input type="text" class="form-control" name="name" autocomplete="off" required
                                       placeholder="{{__("Search parameters title")}}">
                                <i class="input-icon field-icon icofont-waiter-alt"></i>
                                <span class="invalid-feedback error"></span>
                            </div>
                            <div class="row">
                                <div class="col-md-6 xs-text-center">
                                    <button class="theme-btn btn-style-one"
                                            id="saveSearch">{{ __('Save') }}</button>
                                </div>
                                <div class="col-md-6 xs-text-center">
                                    <a class="theme-btn btn-style-three pull-right"
                                       rel="modal:close">{{ __('Cancel') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
@push('js')
    @if(auth()->check())
        <script type="text/javascript">
            $(function () {
                let searchId = null,
                    page = window.location.pathname.replace('/', '');

                @if(request()->query->get('saved_search_id'))
                $('.bc-call-modal.save-search').hide();
                @endif
                $('.filters-outer input, .filters-outer select').on('change', function () {
                    $('.bc-call-modal.save-search').show();
                })
                $(document).on('click', '#clearSearch', function (e) {
                    window.location.href = window.location.pathname.split('?')[0];
                });
                $("#modalSaveSearch").on('modal:close', function () {
                    if (page === 'marketplace') {
                        $('.filters-backdrop').show();
                    }
                })
                $(document).on('click', '.bc-call-modal.save-search', function (event) {
                    event.preventDefault();
                    this.blur();
                    let modal = $("#modalSaveSearch");
                    modal.modal({
                        fadeDuration: 300,
                        fadeDelay: 0.15
                    });
                    modal.find('input[name="name"]').first().focus();
                    if (page === 'marketplace') {
                        $('.filters-backdrop').hide();
                    }
                })
                $(document).on('click', '#saveSearch', function (e) {
                    let modal = $('#modalSaveSearch'),
                        // urlParams = new URLSearchParams(window.location.search),
                        params = {};

                    $('.filters-outer')
                        .find('input[type="text"], input[type="hidden"], select, input[type="checkbox"]:checked, input[type="radio"]:checked')
                        .each(function () {
                            let k = $(this).attr('name'),
                                value = $(this).val();

                            if (!k) {
                                return;
                            }

                            let nameIsArray = k.indexOf('[]');

                            if (value === '' || value === null) {
                                return;
                            }
                            if (nameIsArray >= 0) {
                                k = k.slice(0, nameIsArray);
                            }

                            let valueIsArray = Array.isArray(value);
                            let existsIsArray = Array.isArray(params[k]);

                            if (params[k] === undefined) {
                                if (nameIsArray >= 0 && !valueIsArray) {
                                    params[k] = [value];
                                } else {
                                    params[k] = value;
                                }
                            } else {
                                if (!existsIsArray) {
                                    params[k] = [params[k]];
                                }
                                if (valueIsArray) {
                                    value.forEach(function (v) {
                                        params[k].push(v);
                                    })
                                } else {
                                    console.log(params[k], k, value)
                                    params[k].push(value);
                                }
                            }
                        })

                    // [...urlParams.entries()].forEach(function (item) {
                    //     let k = item[0],
                    //         value = item[1],
                    //         s = k.indexOf('[]');
                    //     if (value === '' || value === null) {
                    //         return;
                    //     }
                    //     if (s >= 0) {
                    //         k = k.slice(0, s);
                    //     }
                    //
                    //     if (params[k] !== undefined) {
                    //         if (!Array.isArray(params[k]) && s >= 0) {
                    //             params[k] = [params[k]];
                    //         }
                    //         params[k].push(value);
                    //     } else {
                    //         if (s >= 0) {
                    //             params[k] = [value];
                    //         } else {
                    //             params[k] = value;
                    //         }
                    //     }
                    // })

                    if (Object.keys(params).length === 0) {
                        alert('Filters is empty');
                        return;
                    }

                    $.ajax({
                        url: '{{route('user.search-params.store')}}',
                        data: {
                            name: modal.find('input[name="name"]').first().val() ?? null,
                            data: params,
                            page: window.location.pathname.replace('/', ''),
                            // id: searchId,
                        },
                        type: 'post',
                        dataType: 'json',
                        success: function (json) {
                            modal.modal('close');
                            $('.jquery-modal.blocker.current').css('opacity', 0);
                            modal.find('input[name="name"]').first().val('');
                            alert(json.messages);
                            // modal.find('h3').first().text(json.messages);
                            // searchId = json.data.id;
                        },
                        error: function (err) {
                            bravo_handle_error_response(err);
                            console.log(err)
                        }
                    })
                })
            });
        </script>
    @endif
@endpush
