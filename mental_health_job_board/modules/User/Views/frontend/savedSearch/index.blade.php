@extends('layouts.user')
@section('head')

@endsection
@section('content')
    <div class="upper-title-box">
        <h3>{{$page_title}}</h3>
    </div>
    @include('admin.message')
    @foreach($rows as $row)
        <div class="candidate-block-three">
            <div class="inner-box">
                <div class="col-md-1">
                    #{{$row->id}}
                </div>
                <div class="col-md-3">
                    <h4 class="name">
                        <a href="{{ $row->page === 'job' ? route('job.search') : ($row->page === 'candidate' ? route('candidate.index') : route('marketplace.search')) }}?{{http_build_query($row->parameters) }}&saved_search_id={{$row->id}}"
                           target="_blank">{{$row->name}}</a>
                    </h4>
                </div>
                <div class="col-md-6">
{{--                    <a class="btn btn-link btn-xs" type="button" data-toggle="collapse"--}}
{{--                       data-target="#collapse-{{$row->id}}" aria-expanded="false"--}}
{{--                       aria-controls="collapseExample">--}}
{{--                        <i class="la la-eye"></i> See details--}}
{{--                    </a>--}}
{{--                    <div class="collapse" id="collapse-{{$row->id}}">--}}
                        <ul class="post-tags">
                            @foreach($row->parameters as $key => $value)
                                @includeIf('User::frontend.savedSearch.parts.' . $key, ['value' => $value])
                            @endforeach
                        </ul>
{{--                    </div>--}}
                </div>
                <div class="col-md-2 option-box">
                    <a class="delete-btn edit-search" data-id="{{$row->id}}" data-name="{{$row->name}}" title="rename"><span class="la la-pencil"></span></a>
                    <a class="delete-btn" target="_blank" href="{{ $row->page === 'job' ? route('job.search') : ($row->page === 'candidate' ? route('candidate.index') : route('marketplace.search')) }}?{{http_build_query($row->parameters) }}&saved_search_id={{$row->id}}" title="see results"><span
                            class="la la-eye"></span></a>
                    <button class="delete-btn remove-saved-search"
                            data-confirm="{{ __("Do you want to remove?") }}"
                            data-id="{{ $row->id }}"
                            data-text="{{ __("Remove saved search parameters") }}"><span
                            class="la la-trash"></span></button>
                </div>
            </div>
        </div>
    @endforeach
    <div class="bravo-pagination">
        {{$rows->appends(request()->query())->links()}}
        <span
            class="count-string">{{ __("Showing :from - :to of :total",["from"=>$rows->firstItem(),"to"=>$rows->lastItem(),"total"=>$rows->total()]) }}</span>
    </div>
@endsection
@section('modals')
    @if(auth()->check())
        <div class="modal fade login" id="modalSaveSearch">
            <div id="modalSaveSearch-modal">
                <div class="login-form default-form">
                    <div class="form-inner">
                        <div class="form-inner">
                            <h3>{{ __("Rename saved search paraneters") }}</h3>
                            <div class="form-group">
                                <label>{{__('Search parameters title:')}}</label>
                                <input type="text" class="form-control" name="name" autocomplete="off" required
                                       placeholder="{{__("Search parameters title")}}" value="">
                                <i class="input-icon field-icon icofont-waiter-alt"></i>
                                <span class="invalid-feedback error"></span>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-12 ">
                                    <input type="hidden" name="id" value="">
                                    <button class="theme-btn btn-style-one"
                                            id="saveSearch">{{ __('Rename') }}</button>
                                </div>
                                <div class="col-md-6 col-12">
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
@section('footer')
    <script>
        $(".remove-saved-search").on("click", function (e) {
            e.preventDefault();
            let t = $(this);
            if (!confirm($(this).attr('data-confirm'))) {
                return false;
            }
            let id = $(this).attr('data-id');
            $.ajax({
                url: '{{route('user.search-params.index')}}/' + id,
                method: "delete",
                success: function (res) {
                    if (t.closest('.candidate-block-three').length) {
                        t.closest('.candidate-block-three').remove();
                    }
                    alert(res.messages);
                }
            })
        });
        $('.edit-search').on('click', function (event) {
            event.preventDefault();
            this.blur();
            let modal = $("#modalSaveSearch");
            modal.modal({
                fadeDuration: 300,
                fadeDelay: 0.15
            });
            modal.find('input[name="name"]').first().val($(this).data('name'))
            modal.find('input[name="id"]').first().val($(this).data('id'))
        })
        $('#saveSearch').on('click', function (e) {
            let modal = $('#modalSaveSearch-modal'),
            id = modal.find('input[name="id"]').first().val();

            $.ajax({
                url: '{{route('user.search-params.index')}}/' + id,
                data: {
                    name: modal.find('input[name="name"]').first().val() ?? null,
                    id: id
                },
                type: 'post',
                dataType: 'json',
                success: function (json) {
                    window.location.reload();
                },
                error: function (err) {
                    bravo_handle_error_response(err);
                    console.log(err)
                }
            })
        })
    </script>
@endsection
