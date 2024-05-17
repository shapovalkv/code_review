@extends('layouts.user')

@php
    $manifestDir = 'dist/frontend';
@endphp

@section('content')
    @php
        $languages = \Modules\Language\Models\Language::getActive();
    @endphp

<section id="edit-job" data-page-with-vue>
    <form
        id="mainForm"
        class="default-form post-form"
        method="post"
        action="{{ route('user.store.job', ['id'=>($row->id) ? $row->id : '-1','lang'=>request()->query('lang')] ) }}"
    >
        @csrf
        <input type="hidden" name="id" value="{{$row->id}}">
        <div class="upper-title-box"@click.prevent="handleSubmitForm">
            <div class="row">
                <div class="col-md-9">
                    <h3>{{$row->id ? __('Edit: ').$row->title : __('Post a job')}}</h3>

                    <div class="text">
                        @if($row->slug)
                            <p class="item-url-demo">{{__("Permalink")}}: {{ url( config('job.job_route_prefix') ) }}/<a href="#" class="open-edit-input" data-name="slug">{{$row->slug}}</a>
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @include('admin.message')

        @if($row->id)
            @include('Language::admin.navigation')
        @endif

        <div class="row">
            <div class="col-lg-9">
                <div class="post-form__left-col">
                    <!-- Ls widget -->
                    <div class="ls-widget mb-0 mb-md-4 mb-lg-0">
                        <div class="tabs-box">
                            <div class="widget-title"><h4>{{ __("Job Content") }}</h4></div>
                            <div class="widget-content">
                                <div class="form-group">
                                    <label>{{__("Job title")}}<span class="text-danger">*</span></label>
                                    <input
                                        required
                                        type="text"
                                        value="{{ old('title', $translation->title) }}"
                                        placeholder="{{__("Job title")}}"
                                        name="title"
                                        class="form-control js-required-input"
                                        @keydown.enter.prevent
                                    >
                                </div>
                                <div class="form-group">
                                    <label class="control-label">{{__("Content")}}<span class="text-danger">*</span></label>
                                    <div class="position-relative">
                                        <textarea
                                            name="content"
                                            class="has-ckeditor js-required-input hidden-textarea"
                                            cols="30"
                                            rows="10"
                                            required
                                        >{{ old('content', $translation->content) }}</textarea>
                                    </div>
                                </div>
                                @if(is_default_lang())
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{__("Hours")}}</label>
                                                <div :class="['input-group', {focus: focusInput === 'hours'}]">
                                                    <input
                                                        type="text"
                                                        value="{{ old('hours', $row->hours) }}"
                                                        placeholder="{{__("Hours")}}"
                                                        name="hours"
                                                        class="form-control no-focus"
                                                        @focus="handleFocusInput(true, 'hours')"
                                                        @blur="handleFocusInput(false, 'hours')"
                                                        @keydown.enter.prevent
                                                        v-maska="'####################'"
                                                    >

                                                    <div class="input-group-append">
                                                        <select
                                                            class="form-control no-focus"
                                                            name="hours_type"
                                                            @focus="handleFocusInput(true, 'hours')"
                                                            @blur="handleFocusInput(false, 'hours')"
                                                        >
                                                            <option value="" @if(old('hours_type', $row->hours_type) == '') selected @endif > -- </option>
                                                            <option value="day" @if(old('hours_type', $row->hours_type) == 'day') selected @endif >{{ __("/day") }}</option>
                                                            <option value="week" @if(old('hours_type', $row->hours_type) == 'week') selected @endif >{{ __("/week") }}</option>
                                                            <option value="month" @if(old('hours_type', $row->hours_type) == 'month') selected @endif >{{ __("/month") }}</option>
                                                            <option value="year" @if(old('hours_type', $row->hours_type) == 'year') selected @endif >{{ __("/year") }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{__("Salary")}}<span class="text-danger">*</span></label>
                                                <div :class="['input-group', {focus: focusInput === 'salary'}]">
                                                    <input
                                                        required
                                                        type="text"
                                                        value="{{ old('salary_min', $row->salary_min) }}"
                                                        placeholder="{{__("Min")}}"
                                                        name="salary_min"
                                                        min="1"
                                                        max="1000000"
                                                        class="form-control no-focus js-required-input js-double-required-input"
                                                        @focus="handleFocusInput(true, 'salary')"
                                                        @blur="handleFocusInput(false, 'salary')"
                                                        data-name-duplicate-input="wage_agreement"
                                                        @keydown.enter.prevent
                                                    >
                                                    <input
                                                        type="text"
                                                        value="{{ old('salary_max', $row->salary_max) }}"
                                                        placeholder="{{__("Max")}}"
                                                        name="salary_max"
                                                        min="1"
                                                        max="1000000"
                                                        class="form-control no-focus"
                                                        @focus="handleFocusInput(true, 'salary')"
                                                        @blur="handleFocusInput(false, 'salary')"
                                                        @keydown.enter.prevent
                                                    >
                                                    <div class="input-group-append">
                                                        <select
                                                            class="form-control no-focus"
                                                            name="salary_type"
                                                            @focus="handleFocusInput(true, 'salary')"
                                                            @blur="handleFocusInput(false, 'salary')"
                                                        >
                                                            <option value="hourly" @if(old('salary_type', $row->salary_type) == 'hourly') selected @endif > {{ __("/hourly") }} </option>
                                                            <option value="daily" @if(old('salary_type', $row->salary_type) == 'daily') selected @endif >{{ __("/daily") }}</option>
                                                            <option value="weekly" @if(old('salary_type', $row->salary_type) == 'weekly') selected @endif >{{ __("/weekly") }}</option>
                                                            <option value="monthly" @if(old('salary_type', $row->salary_type) == 'monthly') selected @endif >{{ __("/monthly") }}</option>
                                                            <option value="yearly" @if(old('salary_type', $row->salary_type) == 'yearly') selected @endif >{{ __("/yearly") }}</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-text">
                                                    Please specify at least minimal salary or choose negotiable
                                                </div>

                                                <div class="custom-control custom-checkbox">
                                                    <input
                                                        class="custom-control-input"
                                                        id="wage_agreement"
                                                        type="checkbox"
                                                        name="wage_agreement"
                                                        @if(old('wage_agreement', $row->wage_agreement)) checked @endif
                                                        value="1"
                                                    >
                                                    <label class="custom-control-label" for="wage_agreement">{{ __("Negotiable") }}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{__("Experience")}}<span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input
                                                        required
                                                        type="number"
                                                        inputmode="decimal"
                                                        class="form-control js-required-input"
                                                        placeholder="{{ __("Experience") }}"
                                                        name="experience"
                                                        value="{{ old('experience',$row->experience) }}"
                                                        max="50"
                                                        min="0"
                                                        v-maska="'##'"
                                                        @keydown.enter.prevent
                                                    >
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">{{ __("year(s)") }}</span>
                                                    </div>
                                                </div>
                                                <div class="form-text">
                                                    Experience value must be between 0 and 50 years
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            @if(is_default_lang())
                                                <!-- Ls widget -->
                                                <div class="ls-widget location">
                                                    <div class="tabs-box">
                                                        <div class="widget-title">
                                                            <h4>{{ __("Job Location") }}<span class="text-danger">*</span></h4>
                                                        </div>

                                                        <div class="widget-content">
                                                            <div class="form-group">
                                                                <label class="control-label">{{__("Location")}}</label>
                                                                <input
                                                                    type="text"
                                                                    placeholder="{{__("Job location")}}"
                                                                    name="map_location_visible"
                                                                    class="bravo_searchbox form-control js-required-input"
                                                                    autocomplete="off"
                                                                    required
                                                                    onkeydown="return event.key !== 'Enter';"
                                                                    value="{{ old('map_location', $row->location->map_location ?? '') }}"
                                                                    @keydown.enter.prevent
                                                                >
                                                                <input
                                                                    type="hidden"
                                                                    name="map_location"
                                                                    class="form-control js-hidden-location"
                                                                    autocomplete="off"
                                                                    onkeydown="return event.key !== 'Enter';"
                                                                    value="{{ old('map_location', $row->location->map_location ?? '') }}"
                                                                    @keydown.enter.prevent
                                                                >
                                                            </div>
                                                            <div class="form-group m-0">
                                                                <label class="control-label">{{__("The geographic coordinate")}}</label>
                                                                <div class="control-map-group">
                                                                    <div id="map_content"></div>
                                                                    <input type="hidden" name="map_lat" value="{{old('map_lat', $row->location->map_lat ?? "") }}">
                                                                    <input type="hidden" name="map_lng" value="{{old('map_lng', $row->location->map_lng ?? "")}}">
                                                                    <input type="hidden" name="map_zoom" value="{{old('map_zoom', $row->location->map_zoom ?? "8")}}">
                                                                    <input type="hidden" name="map_state" value="{{old('map_state', $row->location->map_state ?? "")}}">
                                                                    <input type="hidden" name="map_state_long" value="{{old('map_state_long', $row->location->map_state_long ?? "")}}">
                                                                    <input type="hidden" name="map_city" value="{{old('map_city', $row->location->map_city ?? "")}}">
                                                                    <input type="hidden" name="map_address" value="{{old('map_address', $row->location->map_address ?? "")}}">
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

{{--                    @include('Core::frontend/seo-meta/seo-meta')--}}
                </div>
            </div>

            <div class="col-lg-3 post-form__right-col-wrap">
                <div class="post-form__right-col">
                    <div class="row">
                        <!-- Ls widget -->


                        @if(is_default_lang())
                            @if(empty(setting_item('job_hide_job_apply')))
                                <!-- Ls widget -->
                                <div class="col-md-6 col-lg-12">
                                    <div class="ls-widget ls-widget--border-top m-0 p-md-0 border-md-0 rounded-0">
                                        <div class="tabs-box">
                                            <div class="widget-title"><h5>{{ __("Job Apply") }}</h5></div>
                                            <div class="widget-content">
                                                <div class="form-group mb-0">
                                                    <select name="apply_type" class="form-control select">
                                                        <option value="">{{ __("Default") }}</option>
                                                        <option value="email" @if(old('apply_type', $row->apply_type) == 'email') selected @endif >{{ __("Send Email") }}</option>
                                                        <option value="external" @if(old('apply_type', $row->apply_type) == 'external') selected @endif >{{ __("External") }}</option>
                                                    </select>
                                                </div>
                                                <div class="form-group mb-0 mt-md-2" data-condition="apply_type:is(external)">
                                                    <label>{{ __("Apply Link") }}</label>
                                                    <input type="text" name="apply_link" class="form-control" value="{{ old('apply_link',$row->apply_link) }}" />
                                                </div>
                                                <div class="form-group mb-0 mt-md-2" data-condition="apply_type:is(email)">
                                                    <label>{{ __("Apply Email") }}</label>
                                                    <input type="text" name="apply_email" class="form-control" value="{{ old('apply_email',$row->apply_email) }}" />
                                                    <small><i>{{ __("If is empty, it will be sent to the company's email") }}</i></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                                <!-- Ls widget -->
                                <div class="col-md-6 col-lg-12">
                                    <div class="ls-widget mb-0">
                                        <div class="tabs-box">
                                            <div class="widget-title"><h5>{{ __("Category") }}<span
                                                        class="text-danger">*</span></h5></div>
                                            <div class="widget-content">
                                                <div class="form-group mb-0">
                                                    <div class="">
                                                        <select name="category_id" class="form-control select js-required-input" required>
                                                            <option value="">{{__("-- Please Select --")}}</option>
                                                            <?php
                                                            $traverse = function ($categories, $prefix = '') use (&$traverse, $row) {
                                                                foreach ($categories as $category) {
                                                                    $selected = '';
                                                                    if (old('category_id', $row->category_id) == $category->id)
                                                                        $selected = 'selected';

                                                                    $translate = $category->translateOrOrigin(app()->getLocale());
                                                                    printf("<option value='%s' %s>%s</option>", $category->id, $selected, $prefix . ' ' . $translate->name);
                                                                    $traverse($category->children, $prefix . '-');
                                                                }
                                                            };
                                                            $traverse($categories);
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Ls widget -->
                                <div class="col-md-6 col-lg-12">
                                    <div class="ls-widget ls-widget--border-top m-md-0 p-md-0 border-md-0 mb-0 rounded-0">
                                        <div class="tabs-box">
                                            <div class="widget-title"><h5>{{ __("Job Type") }}<span
                                                        class="text-danger">*</span></h5></div>
                                            <div class="widget-content">
                                                <div class="form-group mb-0">
                                                    <div class="">
                                                        <select name="job_type_id" class="form-control select js-required-input" required>
                                                            <option value="">{{__("-- Please Select --")}}</option>
                                                            <?php
                                                            foreach ($job_types as $job_type) {
                                                                $selected = '';
                                                                if (old('job_type_id', $row->job_type_id) == $job_type->id)
                                                                    $selected = 'selected';
                                                                printf("<option value='%s' %s>%s</option>", $job_type->id, $selected, $job_type->name);
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Ls widget -->
                                <div class="col-md-6 col-lg-12">
                                    <div class="ls-widget ls-widget--border-top mb-0 rounded-0">
                                        <div class="tabs-box">
                                            <div class="widget-title justify-content-between flex-wrap flex-row">
                                                <h5>{{ __("Job Benefits") }}</h5>

                                                <div class="subtitle">Up to 10</div>
                                            </div>
                                            <div class="widget-content">
                                                <div class="form-group mb-0">
                                                    <div class="">
                                                        <select
                                                            id="job_skills"
                                                            name="job_skills[]"
                                                            class="form-control select"
                                                            multiple="multiple"
                                                        >
                                                            <option value="">{{__("-- Please Select --")}}</option>
                                                            <?php
                                                            foreach ($job_skills as $job_skill) {
                                                                $selected = '';
                                                                if ($row->skills) {
                                                                    foreach ($row->skills as $skill) {
                                                                        if ($job_skill->id == $skill->id) {
                                                                            $selected = 'selected';
                                                                        }
                                                                    }
                                                                }
                                                                printf("<option value='%s' %s>%s</option>", $job_skill->id, $selected, $job_skill->name);
                                                            }
                                                            ?>
                                                        </select>

                                                        <div class="popular-variants">
                                                            @foreach($random_skills as $skill)
                                                                <div class="popular-variants__item js-job-benefit">{{ $skill->name }}</div>
                                                            @endforeach
                                                        </div>
                                                    </div>
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
                        @if($row->slug)
                            {{__('Save')}}
                        @else
                            {{__('Next')}}
                        @endif
                        <svg width="28" height="8" viewBox="0 0 28 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M28 4L23 8V0L28 4Z" fill="white"/>
                            <path d="M23 3.58011H0V4.41989H23V3.58011Z" fill="white"/>
                        </svg>
                    </button>

                    @if($row->slug)
                        <a
                            class="post-form__send-btn f-btn secondary-btn theme-btn btn-style-one"
                            href="{{$row->getDetailUrl(request()->query('lang'))}}"
                            target="_blank"
                        >{{__("View Job")}}</a>
                    @endif
                </div>
            </div>
        </div>
    </form>
</section>
@endsection

@section('footer')
    {!! App\Helpers\MapEngine::scripts() !!}
    <script type="text/javascript" src="{{ asset('libs/daterange/moment.min.js') }}"></script>
    <script src="{{ asset('libs/select2/js/select2.min.js') }}" ></script>
    <script src="{{ asset('js/condition.js') }}"></script>
    <script src="{{ mix('js/editJob.js', $manifestDir) }}"></script>
    <script type="text/javascript" src="{{url('module/core/js/form-validation-engine.js?_ver='.config('app.version'))}}"></script>

    <script>
        jQuery(function ($) {
            "use strict"

            let mapLat = {{ !empty($row->location->map_lat) ? ($row->location->map_lat ?? "38.896714696640004") : "38.896714696640004" }};
            let mapLng = {{ !empty($row->location->map_lng) ? ($row->location->map_lng ?? "-77.04821945173418") : "-77.04821945173418" }};
            let mapZoom = {{ !empty($row->location->map_zoom) ? ($row->location->map_zoom ?? "8") : "8" }};

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
                            $("input[name=map_location_visible]").val(`${address} ${city} ${state}`.trim());
                            $("input[name=map_location]").val(`${address} ${city} ${state}`.trim());
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

            $('.open-edit-input').on('click', function (e) {
                e.preventDefault();
                $(this).replaceWith('<input type="text" name="' + $(this).data('name') + '" value="' + $(this).html() + '">');
            });

            $(".form-group-item").each(function () {
                let container = $(this);
                $(this).on('click','.btn-remove-item',function () {
                    $(this).closest(".item").remove();
                });

                $(this).on('press','input,select',function () {
                    let value = $(this).val();
                    $(this).attr("value",value);
                });
            });
            $(".form-group-item .btn-add-item").on('click',function () {
                var p = $(this).closest(".form-group-item").find(".g-items");

                let number = $(this).closest(".form-group-item").find(".g-items .item:last-child").data("number");
                if(number === undefined) number = 0;
                else number++;
                let extra_html = $(this).closest(".form-group-item").find(".g-more").html();
                extra_html = extra_html.replace(/__name__=/gi, "name=");
                extra_html = extra_html.replace(/__number__/gi, number);
                p.append(extra_html);

                if(extra_html.indexOf('dungdt-select2-field-lazy') >0 ){

                    p.find('.dungdt-select2-field-lazy').each(function () {
                        var configs = $(this).data('options');
                        $(this).select2(configs);
                    });
                }
            });

            $('#job_skills').select2({
                tags: true,
                placeholder: "Job Benefits",
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
                        skill_type: 'job',
                        status: 'publish'
                    }).done(function (result) {
                        let option = $(`#job_skills [value="${tag.text}"]`)
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

            $('.js-job-benefit').on('click', event => {
                const term = $(event.target).text()
                const currentValues = $('#job_skills').val()

                if (currentValues.includes(term)) {
                    return false
                }

                var newOption = new Option(term, term, false, false);
                $('#job_skills').append(newOption).val([...currentValues, term]).trigger('change');
            })

            window.initValidationForm()
        })
    </script>
@endsection
