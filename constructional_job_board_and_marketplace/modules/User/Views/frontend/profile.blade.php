@extends('layouts.user')

@php
    $manifestDir = 'dist/frontend';
@endphp

@section('content')
    <div class="bravo_user_profile p-0">
        <div class="upper-title-box">
            <h3 class="title">{{__("My Profile")}}</h3>

            {{-- TODO add method to delete profile --}}
{{--            <form method="/" class="bravo_user_profile__form-delete">--}}
{{--                <button class="bravo_user_profile__delete-btn">--}}
{{--                    <i class="ri-delete-bin-line"></i>--}}

{{--                    <span>delete profile</span>--}}
{{--                </button>--}}
{{--            </form>--}}
        </div>

        @include('admin.message')

        @if ($message = Session::get('complete_registration'))
            <div class="alert alert-info alert-block">
                <button type="button" class="close ri-close-line" data-dismiss="alert"></button>
                <span>{!! clean($message) !!}</span>
            </div>
        @endif

        <form
            id="mainForm"
            action="{{ route('user.profile.update') }}" method="post"
            class="default-form post-form"
        >
            @csrf
            <input type="hidden" name="status" value="{{ $row->status }}">
            <input type="hidden" name="allow_search" value="{{ $row->candidate->allow_search ?? '' }}">
            <input type="hidden" name="need_update_pw" value="{{ $row->need_update_pw }}">
            <div class="row">
                <div class="col-lg-9">
                    <div class="post-form__left-col">
                        <div class="ls-widget mb-4">
                            <div class="tabs-box">
                                <div class="widget-title"><h4>{{ __("User Info") }}</h4></div>
                                <div class="widget-content">
                                    <div class="form-group">
                                        <label>{{__("E-mail")}} <span class="text-danger">*</span></label>
                                        <input
                                            type="text"
                                            name="email"
                                            required
                                            value="{{old('email',$row->email)}}"
                                            placeholder="{{__("E-mail")}}"
                                            class="form-control js-required-input"
                                        >
                                    </div>
                                    <div class="form-group mb-0">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>{{__("First name")}} <span class="text-danger">*</span></label>
                                                    <input
                                                        type="text"
                                                        required
                                                        value="{{old('first_name',$row->first_name)}}"
                                                        name="first_name"
                                                        placeholder="{{__("First name")}}"
                                                        class="form-control js-required-input"
                                                    >
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>{{__("Last name")}} <span class="text-danger">*</span></label>
                                                    <input
                                                        type="text"
                                                        required
                                                        value="{{old('last_name',$row->last_name)}}"
                                                        name="last_name"
                                                        placeholder="{{__("Last name")}}"
                                                        class="form-control js-required-input"
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{__("Phone Number")}}</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">+1</span>
                                                        <input
                                                            id="phone"
                                                            type="text"
                                                            value="{{old('phone',$row->phone)}}"
                                                            name="phone"
                                                            placeholder="{{__("Phone Number")}}"
                                                            class="form-control"
                                                        >
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>{{__("Birthday")}}</label>
                                                    <input
                                                        type="text"
                                                        value="{{ old('birthday',$row->birthday? display_date($row->birthday) :'') }}"
                                                        name="birthday"
                                                        placeholder="{{__("Birthday")}}"
                                                        class="form-control has-easepick"
                                                        autocomplete="off"
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>{{__("Biographical")}} <span class="text-danger">*</span></label>
                                        <textarea
                                            name="bio"
                                            required
                                            rows="5"
                                            class="form-control js-required-input"
                                        >{{ strip_tags(old('bio',$row->bio)) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($row->hasRole('employee'))
                        <div class="ls-widget mb-4">
                            <div class="tabs-box">
                                <div class="widget-title"><h4>{{ __("Candidate Info") }}</h4></div>
                                <div class="widget-content">
                                    @include('Candidate::admin.candidate.form')
                                </div>
                            </div>
                        </div>
                        <div class="ls-widget mb-4 block-divider rounded-0">
                            <div class="tabs-box">
                                <div class="widget-title"><h4>{{ __("Location") }}</h4></div>
                                <div class="widget-content">
                                    @include('Candidate::admin.candidate.location')
                                </div>
                            </div>
                        </div>
                        <div class="ls-widget mb-4 card-sub_information block-divider rounded-0">
                            <div class="tabs-box">
                                <div class="widget-content">
                                    @include('Candidate::admin.candidate.sub_information')
                                </div>
                            </div>
                        </div>

{{--                        @include('Core::frontend.seo-meta.seo-meta',['row' => ($row->candidate ?? $candidate)])--}}
                    @endif
                    </div>
                </div>

                <div class="col-lg-3 post-form__right-col-wrap">
                    <div class="post-form__right-col">
                        <div class="row">
                            <div class="col-md-6 col-lg-12">
                                <div class="ls-widget mb-0">
                                    <div class="tabs-box bravo_user_profile__avatar-wrap">
                                        <div class="widget-title mb-3 d-md-block">
                                            <h5>{{ __('Avatar')}}</h5>

                                            <div class="subtitle mt-2">Recommended size image: 1080 x 1080 px</div>
                                        </div>
                                        <div class="widget-content">
                                            <div class="form-group">
                                                {!! \Modules\Media\Helpers\FileHelper::fieldUpload('avatar_id',old('avatar_id', $row->avatar_id)) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tabs-box bravo_user_profile__avatar-wrap mt-5">
                                        <div class="widget-content">
                                            <div class="form-group mb-0">
                                                <div class="custom-control custom-checkbox">
                                                    <input
                                                        class="custom-control-input"
                                                        id="is_hidden_profile"
                                                        type="checkbox"
                                                        name="is_hidden_profile"
                                                        value="1"
                                                        {{ $row->candidate && $row->candidate->allow_search == "draft" ? "checked" : ''}}
                                                    >
                                                    <label class="custom-control-label" for="is_hidden_profile">Hide profile from search</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($row->hasRole('employee'))
                                <div class="col-md-6 col-lg-12">
                                    <div class="ls-widget ls-widget--border-top m-md-0 p-md-0 border-md-0 mb-0 rounded-0">
                                        <div class="tabs-box">
                                            <div class="widget-title">
                                                <h5>{{ __("Category") }}<span class="text-danger">*</span></h5>
                                            </div>
                                            <div class="widget-content">
                                                <div class="form-group mb-0">
                                                    <select
                                                        id="categories"
                                                        class="form-control js-required-input"
                                                        name="categories[]"
                                                        multiple="multiple"
                                                        required
                                                    >
                                                        <option value="">{{__("-- Please Select --")}}</option>
                                                        <?php
                                                        foreach ($categories as $oneCategories) {
                                                            $selected = '';
                                                            if (!empty($row->candidate->categories)){

                                                                foreach ($row->candidate->categories as $category){
                                                                    if($oneCategories->id == $category->id){
                                                                        $selected = 'selected';
                                                                    }
                                                                }
                                                            }
                                                            $trans = $oneCategories->translateOrOrigin(app()->getLocale());
                                                            printf("<option value='%s' %s>%s</option>", $oneCategories->id, $selected, $oneCategories->name);
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-lg-12">
                                    <div class="ls-widget ls-widget--border-top mb-0 rounded-0">
                                        <div class="tabs-box">
                                            <div class="widget-title justify-content-between flex-wrap flex-row">
                                                <h5>{{ __("Skills") }}</h5>

                                                <div class="subtitle">Up to 10</div>
                                            </div>
                                            <div class="widget-content">
                                                <div class="form-group mb-0">
                                                    <div class="">
                                                        <select
                                                            id="skills"
                                                            name="skills[]"
                                                            class="form-control"
                                                            multiple="multiple"
                                                        >
                                                            <option value="">{{__("-- Please Select --")}}</option>
                                                            <?php
                                                            foreach ($skills as $oneSkill) {
                                                                $selected = '';
                                                                if (!empty($row->candidate->skills)){
                                                                    foreach ($row->candidate->skills as $skill){
                                                                        if($oneSkill->id == $skill->id){
                                                                            $selected = 'selected';
                                                                        }
                                                                    }
                                                                }
                                                                $trans = $oneSkill->translateOrOrigin(app()->getLocale());
                                                                printf("<option value='%s' %s>%s</option>", $oneSkill->id, $selected, $trans->name);
                                                            }
                                                            ?>
                                                        </select>

                                                        <div class="popular-variants">
                                                            @foreach($random_skills as $skill)
                                                            <div class="popular-variants__item js-skills" data-id="{{ $skill->id }}">{{ $skill->name }}</div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-lg-12">
                                    <div class="ls-widget ls-widget--border-top mb-0 rounded-0">
                                        <div class="tabs-box">
                                            <div class="widget-title"><h5>{{ __("Upload Resume") }}</h5></div>

                                            <div class="widget-content">
                                                <div class="form-group-item form-group">
                                                    {!! \Modules\Media\Helpers\FileHelper::fieldFileUpload('cvs', @$cvs, 'cvs') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col px-md-0 post-form__send-btn-col">
                    <div class="post-form__send-btn-wrap">
                        <button
                            id="submitFormBtn"
                            class="post-form__send-btn f-btn primary-btn theme-btn btn-style-one"
                        >
                            {{__('Save Changes')}}

                            <svg width="28" height="8" viewBox="0 0 28 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M28 4L23 8V0L28 4Z" fill="white"/>
                                <path d="M23 3.58011H0V4.41989H23V3.58011Z" fill="white"/>
                            </svg>
                        </button>

                        @if(is_candidate() && !empty($row->candidate->slug))
                            <a
                                class="post-form__send-btn f-btn secondary-btn theme-btn btn-style-one"
                                href="{{ route('candidate.detail', ['slug'=>$row->candidate->slug]) }}"
                                target="_blank"
                            >{{__("View Profile")}}</a>
                        @endif
                    </div>
                </div>

            </div>
        </form>
    </div>
@endsection
@section('footer')
    {!! App\Helpers\MapEngine::scripts() !!}
    <script type="text/javascript" src="{{ asset('libs/daterange/moment.min.js') }}"></script>
    <script src="{{ asset('libs/select2/js/select2.min.js') }}" ></script>
    <script src="{{ mix('js/bootstrap5.js', $manifestDir) }}"></script>
    <script type="text/javascript" src="{{url('module/core/js/form-validation-engine.js?_ver='.config('app.version'))}}"></script>
    <script src="{{url('libs/easepick/easepick.min.js')}}"></script>

    <script>
        const datepickersList = document.querySelectorAll('.has-easepick')

        datepickersList.forEach(item => {
            new easepick.create({
                element: item,
                css: [
                    '{{ asset("libs/easepick/easepick.css") }}',
                ],
                zIndex: 10,
                format: 'MM/DD/YYYY',
                AmpPlugin: {
                    dropdown: {
                        months: true,
                        years: true
                    },
                    darkMode: false,
                },
                plugins: [
                    "AmpPlugin"
                ]
            })
        })

        Maska.create('#phone', { mask: '### ### ####' });
        Maska.create('[name="experience_year"]', { mask: '##' });

        const SALARY_MASK_MASKS = {
            hourly: '###',
            daily: '####',
            weekly: '#####',
            monthly: '######',
            yearly: '########',
        }

        const salaryType = $('[name="salary_type"]')

        if (salaryType.length > 0) {
            Maska.create('[name="expected_salary_min"]', { mask: SALARY_MASK_MASKS[salaryType.val()] });
            Maska.create('[name="expected_salary_max"]', { mask: SALARY_MASK_MASKS[salaryType.val()] });

            salaryType.on('change', () => {
                const salaryTypeVal = salaryType.val()

                Maska.create('[name="expected_salary_min"]', { mask: SALARY_MASK_MASKS[salaryTypeVal] });
                Maska.create('[name="expected_salary_max"]', { mask: SALARY_MASK_MASKS[salaryTypeVal] });
            })
        }

    </script>
    <script>
        @if($row->hasRole('employee') || !empty($candidate_create))
        $(document).ready(function() {

            $('#categories').select2({ placeholder: "Category" });
            $('#seniority_level').select2({ placeholder: "Select Your level", tokenSeparators: [','], multiple: true });

            $('#skills').select2({
                tags: true,
                placeholder: "Enter skills",
                tokenSeparators: [','],
                createTag: function (params) {
                    let term = $.trim(params.term);

                    return {
                        id: term,
                        text: term,
                        newTag: true,
                    }
                },
            }).on('select2:select', function (e) {
                let skills = $(this);
                let tag = e.params.data;
                if (e.params.data.newTag === true) {
                    $.post('{{ route('user.store.skill') }}', {
                        name: tag.text,
                        skill_type: 'candidate',
                        status: 'publish'
                    }).done(function (result) {
                        let option = $(`#skills [value="${tag.text}"]`)
                        option[0].value = result.created_skill_id
                        const data = skills.select2('data').map(item => {
                            if(item.id === tag.text){
                                item.id = result.created_skill_id.toString()
                            }
                            return item
                        })
                        skills.select2('data', data)
                    });
                }
            })

            $('.js-skills').on('click', event => {
                const termId = $(event.target).attr('data-id')
                const currentValues = $('#skills').val()

                if (currentValues.includes(termId)) {
                    return false
                }

                $('#skills').val([...currentValues, termId]).trigger('change');
            })

        });

        let mapLat = {{ !empty($row->candidate) && !empty($row->candidate->location->map_lat) ? ($row->candidate->location->map_lat ?? "38.896714696640004") : "38.896714696640004" }};
        let mapLng = {{  !empty($row->candidate) && !empty($row->candidate->location->map_lng) ? ($row->candidate->location->map_lng ?? "-77.04821945173418") : "-77.04821945173418" }};
        let mapZoom = {{  !empty($row->candidate) && !empty($row->candidate->location->map_zoom) ? ($row->candidate->location->map_zoom ?? "8") : "8" }};
        jQuery(function ($) {
            new BravoMapEngine('map_content', {
                disableScripts: true,
                fitBounds: true,
                center: [mapLat, mapLng],
                zoom: mapZoom,
                ready: function (engineMap) {
                    engineMap.addMarker([mapLat, mapLng], {
                        icon_options: {}
                    });
                    engineMap.on('click', function (dataLatLng) {
                        engineMap.clearMarkers();
                        engineMap.addMarker(dataLatLng, {
                            icon_options: {}
                        });
                        const { city, state, state_long, address } = dataLatLng[3]

                        $("input[name=map_lat]").val(dataLatLng[0]);
                        $("input[name=map_lng]").val(dataLatLng[1]);
                        $("input[name=map_location]").val(`${address} ${city} ${state}`.trim());
                        $("input[name=map_location_visible]").val(`${address} ${city} ${state}`.trim());
                        $("input[name=map_state]").val(state);
                        $("input[name=map_state_long]").val(state_long);
                        $("input[name=map_city]").val(city);
                        $("input[name=map_address]").val(address);
                    });
                    engineMap.on('zoom_changed', function (zoom) {
                        $("input[name=map_zoom]").attr("value", zoom);
                    });
                    engineMap.autocomplete($('.bravo_searchbox'),function (dataLatLng) {
                        engineMap.clearMarkers();
                        engineMap.addMarker(dataLatLng, {
                            icon_options: {}
                        });
                        const { city, state, state_long, address } = dataLatLng[3]

                        $("input[name=map_lat]").val(dataLatLng[0]);
                        $("input[name=map_lng]").val(dataLatLng[1]);
                        $("input[name=map_location]").val($("input[name=map_location_visible]").val());
                        $("input[name=map_state]").val(state);
                        $("input[name=map_state_long]").val(state_long);
                        $("input[name=map_city]").val(city);
                        $("input[name=map_address]").val(address);
                    });
                }
            });

        });

        $('.bravo_searchbox').on('input', () => {
            $('.js-hidden-location').val(null)
        })

        @endif

        window.autosize($('.experience-textarea'));

        window.initValidationForm()
    </script>
@endsection
