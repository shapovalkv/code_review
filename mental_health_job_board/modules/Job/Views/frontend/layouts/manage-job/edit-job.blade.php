@extends('layouts.user')

@section('content')
    @php
        $languages = \Modules\Language\Models\Language::getActive();
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user->parent) {
            $user = $user->parent;
        }
    @endphp
    <form method="post"
          action="{{ route('user.store.job', ['id'=>($row->id) ? $row->id : '-1','lang'=>request()->query('lang')] ) }}"
          class="default-form">
        @csrf
        <input type="hidden" name="id" value="{{$row->id}}">
        <div class="upper-title-box">
            <div class="row">
                <div class="col-md-9">
                    <h3>{{$row->id ? __('Edit: ').$row->title : __('Post a Job')}}</h3>
                    <div class="text">
                        @if($row->slug)
                            <p class="item-url-demo">{{__("Permalink")}}: {{ url( config('job.job_route_prefix') ) }}/<a
                                    href="#" class="open-edit-input" data-name="slug">{{$row->slug}}</a>
                            </p>
                        @endif
                    </div>
                </div>
                <div class="col-md-3 text-right">
                    @if($row->slug)
                        <a href="{{$row->getDetailUrl(request()->query('lang'))}}" target="_blank"
                           class="btn btn-style-ten text-light ml-3"><i
                                class="la la-eye"></i> {{__("View Job")}}</a>
                    @endif
                </div>
            </div>
        </div>
        @include('admin.message')

        @if (\Illuminate\Support\Facades\Cache::has(auth()->id() . \Modules\Job\Models\Job::CACHE_KEY_DRAFT) && request()->routeIs('user.create.job'))
            <div class="alert alert-warning alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{!! __('You are editing an unsaved post from a previous session. <a href=":route">Start over</a>', ['route' => route('user.create.job', ['cache' => 'clear'])]) !!}</strong>
            </div>
        @endif

        @if($row->id)
            @include('Language::admin.navigation')
        @endif

        <div class="row">
            <div class="col-lg-8 col-xl-9">
                <!-- Ls widget -->
                <div class="ls-widget">
                    <div class="tabs-box">
                        <div class="widget-title"><h4>{{ __("Job Content") }}</h4></div>
                        <div class="widget-content">
                            <label class="mt-2">
                                @if(!$user->checkFeaturedJobPlan(false, true))
                                    <label>{{ __("Your current plan does not support popular jobs.") }}<a
                                            href="{{ route('subscription') }}"> Click
                                            Here</a> {{ __("to research more plans") }}</label>
                                @endif
                                <br>
                                <input type="checkbox" name="is_featured"
                                       @if(old('is_featured', $row->is_featured)) checked
                                       @endif @if(!$user->checkFeaturedJobPlan(false, true)) disabled="disabled"
                                       @endif  value="1"/> {{ __("Popular Job") }}
                            </label>

                            <div class="form-group">
                                <label>{{__("Title")}} <span class="required">*</span></label>
                                <input type="text" value="{{ old('title', $row->title) }}"
                                       placeholder="{{__("Title")}}" name="title" required
                                       class="form-control onChangeAutoSave">
                            </div>
                            <div class="form-group">
                                <label class="control-label">{{__("Job Description")}}</label>
                                <div class="">
                                    <textarea name="content" class="d-none has-ckeditor onChangeAutoSave" cols="30"
                                              rows="10">{{ old('content', $row->content) }}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">{{__("Key Responsibilities")}}</label>
                                <div class="">
                                    <textarea name="key_responsibilities" class="d-none has-ckeditor onChangeAutoSave"
                                              cols="30"
                                              rows="10">{{ old('key_responsibilities', $row->key_responsibilities) }}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">{{__("Skills & Experience")}}</label>
                                <div class="">
                                    <textarea name="skills_and_exp" class="d-none has-ckeditor onChangeAutoSave"
                                              cols="30"
                                              rows="10">{{ old('skills_and_exp', $row->skills_and_exp) }}</textarea>
                                </div>
                            </div>
                            @if(is_default_lang())
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__("Hours")}}</label>
                                            <div class="input-group">
                                                <input type="text" value="{{ old('hours', $row->hours) }}"
                                                       placeholder="{{__("hours")}}" name="hours"
                                                       class="form-control onChangeAutoSave">
                                                <div class="input-group-append">
                                                    <select class="form-control onChangeAutoSave" name="hours_type">
                                                        <option value=""
                                                                @if(old('hours_type', $row->hours_type) == '') selected @endif >
                                                            --
                                                        </option>
                                                        <option value="day"
                                                                @if(old('hours_type', $row->hours_type) == 'day') selected @endif >{{ __("/day") }}</option>
                                                        <option value="week"
                                                                @if(old('hours_type', $row->hours_type) == 'week') selected @endif >{{ __("/week") }}</option>
                                                        <option value="month"
                                                                @if(old('hours_type', $row->hours_type) == 'month') selected @endif >{{ __("/month") }}</option>
                                                        <option value="year"
                                                                @if(old('hours_type', $row->hours_type) == 'year') selected @endif >{{ __("/year") }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__("Salary")}}<span class="required">*</span></label>
                                            <div class="input-group">
                                                <input required type="text"
                                                       value="{{ old('salary_min', $row->salary_min) }}"
                                                       placeholder="{{__("Min")}}" name="salary_min"
                                                       class="form-control onChangeAutoSave">
                                                <input required type="text"
                                                       value="{{ old('salary_max', $row->salary_max) }}"
                                                       placeholder="{{__("Max")}}" name="salary_max"
                                                       class="form-control onChangeAutoSave">
                                                <div class="input-group-append">
                                                    <select class="form-control onChangeAutoSave" name="salary_type">
                                                        <option value="hourly"
                                                                @if(old('salary_type', $row->salary_type) == 'hourly') selected @endif > {{ __("hourly") }} </option>
                                                        <option value="daily"
                                                                @if(old('salary_type', $row->salary_type) == 'daily') selected @endif >{{ __("daily") }}</option>
                                                        <option value="weekly"
                                                                @if(old('salary_type', $row->salary_type) == 'weekly') selected @endif >{{ __("weekly") }}</option>
                                                        <option value="monthly"
                                                                @if(old('salary_type', $row->salary_type) == 'monthly') selected @endif >{{ __("monthly") }}</option>
                                                        <option value="yearly"
                                                                @if(old('salary_type', $row->salary_type) == 'yearly') selected @endif >{{ __("yearly/annualy") }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__("Experience")}}</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control onChangeAutoSave"
                                                       placeholder="{{ __("Experience") }}" name="experience"
                                                       value="{{ old('experience',$row->experience) }}">
                                                <div class="input-group-append">
                                                    <select class="form-control onChangeAutoSave"
                                                            name="experience_type">
                                                        <option value="years"
                                                                @if(old('experience_type', $row->experience_type) == 'years') selected @endif > {{ __("year(s)") }} </option>
                                                        <option value="hours"
                                                                @if(old('experience_type', $row->experience_type) == 'hours') selected @endif >{{ __("hours") }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="">{{__("Employment Type")}}<span
                                                    class="required">*</span></label>
                                            <select name="position_id" class="form-control onChangeAutoSave" required>
                                                <option value="">{{__("-- Please Select --")}}</option>
                                                    <?php
                                                    $traverse = function ($job_positions, $prefix = '') use (&$traverse, $row) {
                                                        foreach ($job_positions as $position) {
                                                            $selected = '';
                                                            if (old('position_id', $row->position_id) == $position->id)
                                                                $selected = 'selected';

                                                            $translate = $position->translateOrOrigin(app()->getLocale());
                                                            printf("<option value='%s' %s>%s</option>", $position->id, $selected, $prefix . ' ' . $translate->name);
                                                            $traverse($position->children, $prefix . '-');
                                                        }
                                                    };
                                                    $traverse($job_positions);
                                                    ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                @if(is_default_lang())
                    <!-- Ls widget -->
                    <div class="ls-widget">
                        <div class="tabs-box">
                            <div class="widget-title"><h4>{{ __("Job Location") }}</h4></div>
                            <div class="widget-content">
                                <div class="form-group">
                                    <label class="control-label">{{__("Location")}} <span
                                            class="required">*</span></label>
                                        <?php
                                        $location_name = "";
                                        $list_json = [];
                                        $location_id = request()->get('location');
                                        $traverse = function ($locations, $prefix = '') use (&$traverse, &$list_json, &$location_name, $location_id) {
                                            foreach ($locations as $location) {
                                                $translate = $location->translateOrOrigin(app()->getLocale());
                                                if ($location_id == $location->id) {
                                                    $location_name = $translate->name;
                                                }
                                                $list_json[] = [
                                                    'id' => $location->id,
                                                    'title' => $prefix . ' ' . $translate->name,
                                                    'map_lat' => $prefix . ' ' . $location->map_lat,
                                                    'map_lng' => $prefix . ' ' . $location->map_lng,
                                                    'map_zoom' => $prefix . ' ' . $location->map_zoom,
                                                ];
                                                $traverse($location->children, $prefix . '-');
                                            }
                                        };
                                        $traverse($job_location);
                                        ?>
                                    <div class="form-group col-md-12 col-sm-12 p-0 location smart-search">
                                        <span class="icon flaticon-map-locator"></span>
                                        <input type="text" class="smart-search-location parent_text form-control"
                                               onchange="if(typeof onChangeMapAutoSave === 'function') {onChangeMapAutoSave($(this))}"
                                               placeholder="{{__("Type City Name and Choose Location")}}"
                                               value="{{ old('location_name', $row->location->name ?? $location_name) }}"
                                               data-onLoad="{{__("Loading...")}}"
                                               data-default="" required name="location_name">
                                        <input
                                            type="hidden"
                                            class="child_id"
                                            name="location"
                                            value="{{ old('location', old('location_id', $row->location->id ?? $location_id))  }}"
                                            data-map_lng="{{ $row->location->map_lng ?? ''}}"
                                            data-map_zoom="{{ $row->location->map_zoom ?? ''}}"
                                            data-map_lat="{{ $row->location->map_lat ?? ''}}"
                                        >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">{{__("The geographic coordinate")}}</label>
                                    <div class="control-map-group">
                                        <div id="map_content" data-is_map_content='true'></div>
                                        <input type="text" placeholder="{{__("Search by name...")}}"
                                               class="bravo_searchbox form-control" autocomplete="off"
                                               onkeydown="return event.key !== 'Enter';">
                                        <div class="g-control">
                                            <div class="form-group">
                                                <label>{{__("Map Latitude")}}:</label>
                                                <input type="text" name="map_lat" class="form-control"
                                                       value="{{old('map_lat', $row->map_lat ?? '34.0522')}}" readonly
                                                       onkeydown="return event.key !== 'Enter';">
                                            </div>
                                            <div class="form-group">
                                                <label>{{__("Map Longitude")}}:</label>
                                                <input type="text" name="map_lng" class="form-control"
                                                       value="{{old('map_lng', $row->map_lng ?? '-118.244')}}" readonly
                                                       onkeydown="return event.key !== 'Enter';">
                                            </div>
                                            <div class="form-group">
                                                <label>{{__("Map Zoom")}}:</label>
                                                <input type="text" name="map_zoom" class="form-control"
                                                       value="{{old('map_zoom', $row->map_zoom ?? "12")}}" readonly
                                                       onkeydown="return event.key !== 'Enter';">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{--                @include('Core::frontend/seo-meta/seo-meta')--}}

                <div class="mb-4 d-none d-md-block">
                    <button class="theme-btn btn-style-seven" type="submit"
                            onclick="isSubmitting = true;beforeSubmit();"><i
                            class="fa fa-save" style="padding-right: 5px"></i> {{__('Save Changes')}}</button>
                </div>

            </div>

            <div class="col-lg-4 col-xl-3">

                <!-- Ls widget -->
                <div class="ls-widget">
                    <div class="tabs-box">
                        <div class="widget-title"><h4>{{ __("Publish") }}</h4></div>
                        <div class="widget-content pb-4">
                            @if(is_default_lang())
                                <div>
                                    <label><input @if($row->status =='publish') checked @endif type="radio"
                                                  name="status" value="publish"> {{__("Publish")}}</label>
                                </div>
                                <div>
                                    <label><input @if($row->status =='draft') checked @endif type="radio" name="status"
                                                  value="draft"> {{__("Draft")}}</label>
                                </div>
                            @endif
                            <div class="text-right">
                                <button class="theme-btn btn-style-seven" type="submit"
                                        onclick="isSubmitting = true;beforeSubmit();"><i
                                        class="fa fa-save"></i> {{__('Save Changes')}}</button>
                            </div>
                        </div>
                    </div>
                </div>

                @if(setting_item('job_need_approve'))
                    <!-- Ls widget -->
                    <div class="ls-widget">
                        <div class="tabs-box">
                            <div class="widget-title"><h4>{{ __("Approval Status") }}</h4></div>
                            <div class="widget-content">

                                @if($row->id && $row->is_approved =='approved')
                                    <div disabled class="alert alert-success"
                                         type="submit"> {{__('Your job is approved!')}}</div>
                                @else
                                    <div class="form-group">
                                        <label>{{__('Do you want to send request to Admin?')}}</label>
                                        <br>
                                        <label>
                                            <input type="radio" name="is_approved"
                                                   @if(old('is_approved',$row->is_approved) == "waiting") checked
                                                   @endif value="waiting"> {{__("Yes, send request to Admin")}}
                                        </label>
                                        <br>
                                        <label>
                                            <input type="radio" name="is_approved"
                                                   @if(old('is_approved',$row->is_approved) == "draft" || empty(old('is_approved',$row->is_approved))) checked
                                                   @endif value="draft"> {{__("No")}}
                                        </label>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                @if(is_default_lang())
                    @if(empty(setting_item('job_hide_job_apply')))
                        <!-- Ls widget -->
                        <div class="ls-widget">
                            <div class="tabs-box">
                                <div class="widget-title"><h4>{{ __("Job Apply") }}</h4></div>
                                <div class="widget-content">
                                    <div class="form-group">
                                        <label>{{__('Apply Type')}}</label>
                                        <select name="apply_type" class="form-control">
                                            <option value="">{{ __("Default") }}</option>
                                            <option value="email"
                                                    @if(old('apply_type', $row->apply_type) == 'email') selected @endif >{{ __("Send Email") }}</option>
                                            <option value="external"
                                                    @if(old('apply_type', $row->apply_type) == 'external') selected @endif >{{ __("External") }}</option>
                                        </select>
                                    </div>
                                    <div class="form-group" data-condition="apply_type:is(external)">
                                        <label>{{ __("Apply Link") }}</label>
                                        <input type="text" name="apply_link" class="form-control"
                                               value="{{ old('apply_link',$row->apply_link) }}"/>
                                    </div>
                                    <div class="form-group" data-condition="apply_type:is(email)">
                                        <label>{{ __("Apply Email") }}</label>
                                        <input type="text" name="apply_email" class="form-control"
                                               value="{{ old('apply_email',$row->apply_email) }}"/>
                                        <small><i>{{ __("If is empty, it will be sent to the company's email") }}</i></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <!-- Ls widget -->
                    <div class="ls-widget">
                        <div class="tabs-box">
                            <div class="widget-title"><h4>{{ __("Employment Location") }}</h4></div>
                            <div class="widget-content">
                                <?php $employment_location = json_decode($row->employment_location, true); ?>
                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" name="employment_location[virtual]" value="1" class="onChangeAutoSave"
                                               @if(!empty($employment_location) && key_exists('virtual', $employment_location)) checked @endif>
                                        {{ __("Virtual") }}
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" name="employment_location[hybrid]" value="1" class="onChangeAutoSave"
                                               @if(!empty($employment_location) && key_exists('hybrid',  $employment_location)) checked @endif>
                                        {{ __("Hybrid") }}
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" name="employment_location[in_person]" value="1" class="onChangeAutoSave"
                                               @if(!empty($employment_location) && key_exists('in_person', $employment_location)) checked @endif>
                                        {{ __("In Person") }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ls-widget">
                        <div class="tabs-box">
                            <div class="widget-title"><h4>{{ __("Availability") }}</h4></div>
                            <div class="widget-content">
                                <div class="form-group">
                                    <label>{{__('Job Urgent')}}</label>
                                    <br>
                                    <label>
                                        <input type="checkbox" name="is_urgent" class="onChangeAutoSave"
                                               @if(old('is_urgent',$row->is_urgent)) checked
                                               @endif value="1"> {{__("Enable Urgent")}}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ls widget -->
                    <div class="ls-widget">
                        <div class="tabs-box">
                            <div class="widget-title"><h4>{{ __("Category") }} <span class="required">*</span></h4>
                            </div>
                            <div class="widget-content">
                                <div class="form-group">
                                    <div class="">
                                        <select name="category_id" class="form-control onChangeAutoSave" required>
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

                    <!-- Ls widget -->
                    <div class="ls-widget">
                        <div class="tabs-box">
                            <div class="widget-title"><h4>{{ __("Job Type") }}<span class="required">*</span></h4></div>
                            <div class="widget-content">
                                <div class="form-group">
                                    <div class="">
                                        <select name="job_type_id" class="form-control onChangeAutoSave" required>
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
                    <!-- Ls widget -->
                    <div class="ls-widget">
                        <div class="tabs-box">
                            <div class="widget-title"><h4>{{ __("Feature Image") }}</h4></div>
                            <div class="widget-content">
                                <div class="form-group">
                                    {!! \Modules\Media\Helpers\FileHelper::fieldUpload('thumbnail_id',old('thumbnail_id', $row->thumbnail_id)) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif


            </div>
        </div>
    </form>

@endsection

@section('footer')
    {!! App\Helpers\MapEngine::scripts() !!}
    <script type="text/javascript" src="{{ asset('libs/daterange/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('libs/daterange/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('js/condition.js') }}"></script>
    <script>
        let hasChanges = false,
            isSubmitting = false,
            newJob = {{$row->id ? 'false' : 'true'}};

        jQuery(function ($) {
            "use strict"

            $('.has-datepicker').daterangepicker({
                singleDatePicker: true,
                showCalendar: false,
                autoUpdateInput: false, //disable default date
                showDropdowns: true,
                sameDate: true,
                autoApply: true,
                disabledPast: true,
                enableLoading: true,
                showEventTooltip: true,
                classNotAvailable: ['disabled', 'off'],
                disableHightLight: true,
                locale: {
                    format: 'YYYY/MM/DD'
                }
            }).on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('YYYY/MM/DD'));
            });

            let mapLat = {{ !empty($row) ? ($row->map_lat ?? "34.0522") : "34.0522" }};
            let mapLng = {{ !empty($row) ? ($row->map_lng ?? "-118.244") : "-118.244" }};
            let mapZoom = {{ !empty($row) ? ($row->map_zoom ?? "12") : "12" }};

            jQuery(function ($) {
                new BravoMapEngine('map_content', {
                    disableScripts: true,
                    fitBounds: true,
                    center: [mapLat, mapLng],
                    zoom: mapZoom,
                    ready: function (engineMap) {
                        // mapEngine = engineMap
                        engineMap.addMarker([mapLat, mapLng], {
                            icon_options: {}
                        });
                        engineMap.on('click', function (dataLatLng) {
                            engineMap.clearMarkers();
                            engineMap.addMarker(dataLatLng, {
                                icon_options: {}
                            });
                            $("input[name=map_lat]").attr("value", dataLatLng[0]);
                            $("input[name=map_lng]").attr("value", dataLatLng[1]);
                        });
                        engineMap.on('zoom_changed', function (zoom) {
                            $("input[name=map_zoom]").attr("value", zoom);
                        });
                        engineMap.searchBox($('#customPlaceAddress'), function (dataLatLng) {
                            engineMap.clearMarkers();
                            engineMap.addMarker(dataLatLng, {
                                icon_options: {}
                            });
                            $("input[name=map_lat]").attr("value", dataLatLng[0]);
                            $("input[name=map_lng]").attr("value", dataLatLng[1]);
                        });
                        engineMap.searchBox($('.bravo_searchbox'), function (dataLatLng) {
                            engineMap.clearMarkers();
                            engineMap.addMarker(dataLatLng, {
                                icon_options: {}
                            });
                            $("input[name=map_lat]").attr("value", dataLatLng[0]);
                            $("input[name=map_lng]").attr("value", dataLatLng[1]);
                        });

                        $('[name="location"]').on('change', function (e) {
                            const dataLatLng = [
                                $(this).attr('data-map_lat'),
                                $(this).attr('data-map_lng')
                            ]
                            const zoom = $(this).attr('data-map_zoom')

                            engineMap.clearMarkers();
                            engineMap.addMarker(dataLatLng, {
                                icon_options: {}
                            });
                            engineMap.map.setView(dataLatLng, zoom)

                            $("input[name=map_lat]").attr("value", dataLatLng[0])
                            $("input[name=map_lng]").attr("value", dataLatLng[1])
                        });
                    }
                });
            });

            // window.addEventListener('beforeunload', function (e) {
            //     if (hasChanges && newJob && !isSubmitting) {
            //         e.preventDefault();
            //         e.returnValue = '';
            //     }
            // });

            $('.open-edit-input').on('click', function (e) {
                e.preventDefault();
                $(this).replaceWith('<input type="text" name="' + $(this).data('name') + '" value="' + $(this).html() + '">');
            });

            $(".form-group-item").each(function () {
                let container = $(this);
                $(this).on('click', '.btn-remove-item', function () {
                    $(this).closest(".item").remove();
                });

                $(this).on('press', 'input,select', function () {
                    let value = $(this).val();
                    $(this).attr("value", value);
                });
            });
            $(".form-group-item .btn-add-item").on('click', function () {
                var p = $(this).closest(".form-group-item").find(".g-items");

                let number = $(this).closest(".form-group-item").find(".g-items .item:last-child").data("number");
                if (number === undefined) number = 0;
                else number++;
                let extra_html = $(this).closest(".form-group-item").find(".g-more").html();
                extra_html = extra_html.replace(/__name__=/gi, "name=");
                extra_html = extra_html.replace(/__number__/gi, number);
                p.append(extra_html);

                if (extra_html.indexOf('dungdt-select2-field-lazy') > 0) {

                    p.find('.dungdt-select2-field-lazy').each(function () {
                        var configs = $(this).data('options');
                        $(this).select2(configs);
                    });
                }
            });

            $('#job_type_id').select2();

        });

        $(function () {
            $('.onChangeAutoSave').on('change', function () {
                console.log(333)
                hasChanges = {{$row->id ? 'false' : 'true'}};
                onChangeAutoSave($(this))
            });
        });

        function beforeSubmit() {
            let form = $('.default-form');
            if (form.find('input:invalid')) {
                hasChanges = true;
                return false;
            } else {
                hasChanges = false;
                form.submit();
                return true;
            }
        }

        function onChangeTinyAutoSave(element) {
            console.log(1, element)

            let data = {},
                name = $(element.activeEditor.getElement()).attr('name');

            data[name] = element.activeEditor.getContent()

            saveJobAttribute(data);
        }

        function onChangeAutoSave(element) {
            console.log(2, element)
            let data = {},
                name = element.attr('name');

            if (element.attr('type') === 'checkbox') {
                data[name] = element.is(':checked') === true ? 1 : 0;
            } else {
                data[name] = element.val()
            }

            saveJobAttribute(data);
        }

        function onChangeMapAutoSave() {
            setTimeout(function () {
                let data = {
                    map_lat: $('[name="map_lat"]').val(),
                    map_lng: $('[name="map_lng"]').val(),
                    map_zoom: $('[name="map_zoom"]').val(),
                    location_id: $('[name="location"]').val(),
                };
                saveJobAttribute(data);
            }, 1000)
        }

        function saveJobAttribute(data) {
            {{--            @if($row->id)--}}
            $.ajax({
                url: '{{route('job.api.update', ['job'=>$row->id])}}',
                type: 'post',
                data: data,
                dataType: 'json',
                async: false,
                cache: false,
                timeout: 30000,
                success: function (response) {
                    return response;
                }
            });
            {{--            @endif--}}
        }
    </script>
@endsection
