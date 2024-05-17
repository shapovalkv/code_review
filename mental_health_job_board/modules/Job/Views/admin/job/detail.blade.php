@extends('admin.layouts.app')

@section('content')
    <form action="{{route('job.admin.store',['id'=>($row->id) ? $row->id : '-1','lang'=>request()->query('lang')])}}" method="post">
        @csrf
        <div class="container-fluid">
            <div class="d-flex justify-content-between mb20">
                <div class="">
                    <h1 class="title-bar">{{$row->id ? __('Edit: ').$row->title : __('Add new job')}}</h1>
                    @if($row->slug)
                        <p class="item-url-demo">{{__("Permalink")}}: {{ url(config('job.job_route_prefix') ) }}/<a href="#" class="open-edit-input" data-name="slug">{{$row->slug}}</a>
                        </p>
                    @endif
                </div>
                <div class="">
                    @if($row->slug)
                        <a class="btn btn-default btn-sm" href="{{ $row->getDetailUrl() }}" target="_blank"><i class="fa fa-eye"></i> {{__("View Job")}}</a>
                    @endif
                </div>
            </div>
            @include('admin.message')
            @if($row->id)
                @include('Language::admin.navigation')
            @endif
            <div class="lang-content-box">
                <div class="row">
                    <div class="col-md-9">

                        <div class="panel">
                            <div class="panel-title"><strong>{{__("Job Content")}}</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label>{{__("Title")}}</label>
                                    <input type="text" value="{{ old('title', $translation->title) }}" placeholder="{{__("Title")}}" name="title" required class="form-control">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">{{__("Job Description")}}</label>
                                    <div class="">
                                        <textarea name="content" class="d-none has-ckeditor" cols="30" rows="10">{{ old('content', $translation->content) }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">{{__("Key Responsibilities")}}</label>
                                    <div class="">
                                        <textarea name="key_responsibilities" class="d-none has-ckeditor" cols="30" rows="10">{{ old('content', $translation->key_responsibilities) }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">{{__("Skills & Experience")}}</label>
                                    <div class="">
                                        <textarea name="skills_and_exp" class="d-none has-ckeditor" cols="30" rows="10">{{ old('content', $translation->skills_and_exp) }}</textarea>
                                    </div>
                                </div>
                                @if(is_default_lang())
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{__("Expiration Date")}}</label>
                                                <input type="date" required value="{{ old( 'expiration_date', $row->expiration_date ? date('Y-m-d', strtotime($row->expiration_date)) : '') }}" placeholder="YYYY-MM-DD" name="expiration_date" autocomplete="true" class="form-control bg-white">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{__("Hours")}}</label>
                                                <div class="input-group">
                                                    <input type="text" value="{{ old('hours', $row->hours) }}" placeholder="{{__("hours")}}" name="hours" class="form-control">
                                                    <div class="input-group-append">
                                                        <select class="form-control" name="hours_type">
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
{{--                                        <div class="col-md-6">--}}
{{--                                            <div class="form-group">--}}
{{--                                                <label for="gender">{{__("Gender")}}</label>--}}
{{--                                                <select class="form-control" id="gender" name="gender">--}}
{{--                                                    <option value="Both" @if(old('gender', $row->gender) == 'Both') selected @endif >{{ __("Both") }}</option>--}}
{{--                                                    <option value="Male" @if(old('gender', $row->gender) == 'Male') selected @endif >{{ __("Male") }}</option>--}}
{{--                                                    <option value="Female" @if(old('gender', $row->gender) == 'Female') selected @endif >{{ __("Female") }}</option>--}}
{{--                                                </select>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{__("Salary")}}</label>
                                                <div class="input-group">
                                                    <input type="text" required value="{{ old('salary_min', $row->salary_min) }}" placeholder="{{__("Min")}}" name="salary_min" class="form-control">
                                                    <input type="text" required value="{{ old('salary_max', $row->salary_max) }}" placeholder="{{__("Max")}}" name="salary_max" class="form-control">
                                                    <div class="input-group-append">
                                                        <select class="form-control" name="salary_type">
                                                            <option value="hourly" @if(old('salary_type', $row->salary_type) == 'hourly') selected @endif > {{ __("hourly") }} </option>
                                                            <option value="daily" @if(old('salary_type', $row->salary_type) == 'daily') selected @endif >{{ __("daily") }}</option>
                                                            <option value="weekly" @if(old('salary_type', $row->salary_type) == 'weekly') selected @endif >{{ __("weekly") }}</option>
                                                            <option value="monthly" @if(old('salary_type', $row->salary_type) == 'monthly') selected @endif >{{ __("monthly") }}</option>
                                                            <option value="yearly" @if(old('salary_type', $row->salary_type) == 'yearly') selected @endif >{{ __("yearly/annualy") }}</option>
                                                        </select>
                                                    </div>
                                                </div>
{{--                                                <label class="mt-2">--}}
{{--                                                    <input type="checkbox" name="wage_agreement" @if(old('wage_agreement', $row->wage_agreement)) checked @endif value="1" /> {{ __("Wage Agreement") }}--}}
{{--                                                </label>--}}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{__("Experience")}}</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" placeholder="{{ __("Experience") }}" name="experience" value="{{ old('experience',$row->experience) }}">
                                                    <div class="input-group-append">
                                                        <select class="form-control" name="experience_type">
                                                            <option value="years" @if(old('experience_type', $row->experience_type) == 'years') selected @endif > {{ __("year(s)") }} </option>
                                                            <option value="hours" @if(old('experience_type', $row->experience_type) == 'hours') selected @endif >{{ __("hours") }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
{{--                                        <div class="col-md-6">--}}
{{--                                            <div class="form-group">--}}
{{--                                                <label>{{__("Number Of Recruitments")}}</label>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    <input type="number" class="form-control" placeholder="{{ __("0") }}" name="number_recruitments" value="{{ old('number_recruitments',$row->number_recruitments) }}">--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{__("Employment Type")}}</label>
                                                <select name="position_id" class="form-control" required>
                                                    <option value="">{{__("-- Please Select --")}}</option>
                                                        <?php
                                                        $traverse = function ($job_positions, $prefix = '') use (&$traverse, $row) {
                                                            foreach ($job_positions as $position) {
                                                                $selected = '';
                                                                if (old('category_id', $row->position_id) == $position->id)
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

{{--                                        <div class="col-md-12">--}}
{{--                                            <div class="form-group">--}}
{{--                                                <label class="control-label">{{__("Video Url")}}</label>--}}
{{--                                                <input type="text" name="video" class="form-control" value="{{old('video',$row->video)}}" placeholder="{{__("Youtube link video")}}">--}}
{{--                                            </div>--}}
{{--                                        </div>--}}

{{--                                        <div class="col-md-12">--}}
{{--                                            <div class="form-group">--}}
{{--                                                <label>{{__("Video Cover Image")}}</label>--}}
{{--                                                <div class="form-group">--}}
{{--                                                    {!! \Modules\Media\Helpers\FileHelper::fieldUpload('video_cover_id',$row->video_cover_id) !!}--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}

{{--                                        <div class="col-md-12">--}}
{{--                                            <div class="form-group">--}}
{{--                                                <label class="control-label">{{__("Gallery")}} ({{__('Recommended size image:1080 x 1920px')}})</label>--}}
{{--                                                @php--}}
{{--                                                    $gallery_id = $row->gallery ?? old('gallery');--}}
{{--                                                @endphp--}}
{{--                                                {!! \Modules\Media\Helpers\FileHelper::fieldGalleryUpload('gallery', $gallery_id) !!}--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
                                    </div>
                                @endif
                            </div>
                        </div>
                        @if(is_default_lang())
                            <div class="panel">
                                <div class="panel-title"><strong>{{__("Job Location")}}</strong></div>
                                <div class="panel-body">
                                    <div class="form-group-smart-search">
                                        <label class="control-label">{{__("Location")}} <span class="required">*</span></label>
                                            <?php
                                            $location_name = "";
                                            $list_json = [];
                                            $location_id = request()->get('location');
                                            $traverse = function ($locations, $prefix = '') use (&$traverse, &$list_json, &$location_name, $location_id) {
                                                foreach ($locations as $location) {
                                                    $translate = $location->translateOrOrigin(app()->getLocale());
                                                    if (old('location_id', $location_id) == $location->id) {
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
                                            <input id="admin_location_select" type="text"
                                                   class="smart-search-location parent_text form-control"
                                                   placeholder="{{__("All Locations")}}"
                                                   value="{{ $row->location->name ?? $location_name }}"
                                                   data-onLoad="{{__("Loading...")}}"
                                                   data-default="" required>
                                            <input type="hidden" class="child_id" name="location_id" value="{{$row->location_id ?? Request::query('location_id')}}">
                                            <input
                                                type="hidden"
                                                class="child_id"
                                                name="location_id"
                                                value="{{ $row->location->id ?? $location_id  }}"
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
                        @endif

                        @include('Core::admin/seo-meta/seo-meta', ['object'])
                    </div>
                    <div class="col-md-3">
                        <div class="panel">
                            <div class="panel-title"><strong>{{__('Publish')}}</strong></div>
                            <div class="panel-body">
                                @if(is_default_lang())
                                    <div>
                                        <label><input @if(old('status', $row->status) =='publish') checked @endif type="radio" name="status" value="publish"> {{__("Publish")}}</label>
                                    </div>
                                    <div>
                                        <label><input @if(old('status', $row->status)=='draft') checked @endif type="radio" name="status" value="draft"> {{__("Draft")}}</label>
                                    </div>
                                @endif
                                <div class="text-right">
                                    <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> {{__('Save Changes')}}</button>
                                </div>
                            </div>
                        </div>
                        @if(is_default_lang())
                            @if(empty(setting_item('job_hide_job_apply')))
                                <div class="panel">
                                    <div class="panel-title"><strong>{{__('Job Apply')}}</strong></div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label>{{__('Apply Type')}}</label>
                                            <select name="apply_type" class="form-control">
                                                <option value="">{{ __("Default") }}</option>
                                                <option value="email" @if(old('apply_type', $row->apply_type) == 'email') selected @endif >{{ __("Send Email") }}</option>
                                                <option value="external" @if(old('apply_type', $row->apply_type) == 'external') selected @endif >{{ __("External") }}</option>
                                            </select>
                                        </div>
                                        <div class="form-group" data-condition="apply_type:is(external)">
                                            <label>{{ __("Apply Link") }}</label>
                                            <input type="text" name="apply_link" class="form-control" value="{{ old('apply_link',$row->apply_link) }}" />
                                        </div>
                                        <div class="form-group" data-condition="apply_type:is(email)">
                                            <label>{{ __("Apply Email") }}</label>
                                            <input type="text" name="apply_email" class="form-control" value="{{ old('apply_email',$row->apply_email) }}" />
                                            <small><i>{{ __("If is empty, it will be sent to the company's email") }}</i></small>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if(setting_item('job_need_approve'))
                                <div class="panel">
                                    <div class="panel-title"><strong>{{__('Approval Status')}}</strong></div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label>{{__('Approval Type')}}</label>
                                            <select name="is_approved" class="form-control">
                                                <option value="draft" @if(old('is_approved', $row->is_approved) == 'draft') selected @endif >{{ __("Draft") }}</option>
                                                <option value="waiting" @if(old('is_approved', $row->is_approved) == 'waiting') selected @endif >{{ __("Waiting for approval") }}</option>
                                                <option value="approved" @if(old('is_approved', $row->is_approved) == 'approved' || old('apply_type', $row->is_approved) == "") selected @endif >{{ __("Approved") }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="panel">
                                <div class="panel-title"><strong>{{__("Availability")}}</strong></div>
                                <div class="panel-body">
                                    @if(is_admin())
                                        <div class="form-group">
                                            <label>{{__('Popular Job')}}</label>
                                            <br>
                                            <label>
                                                <input type="checkbox" name="is_featured" @if(old('is_featured', $row->is_featured)) checked @endif value="1"> {{__("Enable Popular Job")}}
                                            </label>
                                        </div>
                                    @endif
                                    <div class="form-group">
                                        <label>{{__('Job Urgent')}}</label>
                                        <br>
                                        <label>
                                            <input type="checkbox" name="is_urgent" @if(old('is_urgent',$row->is_urgent)) checked @endif value="1"> {{__("Enable Urgent")}}
                                        </label>
                                    </div>
                                </div>
                            </div>

                                <div class="panel">
                                    <div class="panel-title"><strong>{{__("Employment Location")}}</strong></div>
                                    <div class="panel-body">
                                        @if(is_admin())
                                                <?php $employment_location = json_decode($row->employment_location, true); ?>
                                            <div class="form-group">
                                                <label>
                                                    <input type="checkbox" name="employment_location[virtual]" value="1"
                                                           @if(!empty($employment_location) && key_exists('virtual', $employment_location)) checked @endif>
                                                    {{ __("Virtual") }}
                                                </label>
                                            </div>
                                            <div class="form-group">
                                                <label>
                                                    <input type="checkbox" name="employment_location[hybrid]" value="1"
                                                           @if(!empty($employment_location) && key_exists('hybrid',  $employment_location)) checked @endif>
                                                    {{ __("Hybrid") }}
                                                </label>
                                            </div>
                                            <div class="form-group">
                                                <label>
                                                    <input type="checkbox" name="employment_location[in_person]"
                                                           value="1"
                                                           @if(!empty($employment_location) && key_exists('in_person', $employment_location)) checked @endif>
                                                    {{ __("In Person") }}
                                                </label>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                            <div class="panel">
                                <div class="panel-title"><strong>{{__("Category")}}</strong></div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="">
                                            <select required name="category_id" class="form-control">
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

                            <div class="panel">
                                <div class="panel-title"><strong>{{__("Job Type")}}</strong></div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="">
                                            <select required name="job_type_id" class="form-control">
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
{{--                            <div class="panel">--}}
{{--                                <div class="panel-title"><strong>{{__("Job Skills")}}</strong></div>--}}
{{--                                <div class="panel-body">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <div class="">--}}
{{--                                            <select id="job_type_id" name="job_skills[]" class="form-control" multiple="multiple">--}}
{{--                                                <option value="">{{__("-- Please Select --")}}</option>--}}
{{--                                                <?php--}}
{{--                                                foreach ($job_skills as $job_skill) {--}}
{{--                                                    $selected = '';--}}
{{--                                                    if ($row->skills){--}}
{{--                                                        foreach ($row->skills as $skill){--}}
{{--                                                            if($job_skill->id == $skill->id){--}}
{{--                                                                $selected = 'selected';--}}
{{--                                                            }--}}
{{--                                                        }--}}
{{--                                                    }--}}
{{--                                                    printf("<option value='%s' %s>%s</option>", $job_skill->id, $selected, $job_skill->name);--}}
{{--                                                }--}}
{{--                                                ?>--}}
{{--                                            </select>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}

                            @if(is_admin())
                                <div class="panel">
                                    <div class="panel-title"><strong>{{__("Company")}}</strong></div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <?php
                                            $company = !empty($row->company_id) ? \Modules\Company\Models\Company::find($row->company_id) : false;
                                                \App\Helpers\AdminForm::select2('company_id', [
                                                    'configs' => [
                                                        'ajax' => [
                                                            'url' => route('company.admin.getForSelect2'),
                                                            'dataType' => 'json'
                                                        ],
                                                        'allowClear' => true,
                                                        'placeholder' => __('-- Select Company --')
                                                    ]
                                                ], !empty($company->id) ? [
                                                    $company->id,
                                                    $company->name . ' (#' . $company->id . ')'
                                                ] : false,
                                                    true
                                                )
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                        @if(is_default_lang())
                            <div class="panel">
                                <div class="panel-title"><strong>{{__('Feature Image')}}</strong></div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        {!! \Modules\Media\Helpers\FileHelper::fieldUpload('thumbnail_id',old('thumbnail_id', $row->thumbnail_id)) !!}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@php  @endphp
@section ('script.body')
    {!! App\Helpers\MapEngine::scripts() !!}
    <script>
        jQuery(function ($) {
            let mapLat = {{ !empty($row) ? ($row->map_lat ?? "34.0522") : "34.0522" }};
            let mapLng = {{ !empty($row) ? ($row->map_lng ?? "-118.244") : "-118.244" }};
            let mapZoom = {{ !empty($row) ? ($row->map_zoom ?? "12") : "12" }};


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
                    engineMap.searchBox($('#customPlaceAddress'),function (dataLatLng) {
                        engineMap.clearMarkers();
                        engineMap.addMarker(dataLatLng, {
                            icon_options: {}
                        });
                        $("input[name=map_lat]").attr("value", dataLatLng[0]);
                        $("input[name=map_lng]").attr("value", dataLatLng[1]);
                    });
                    engineMap.searchBox($('.bravo_searchbox'),function (dataLatLng) {
                        engineMap.clearMarkers();
                        engineMap.addMarker(dataLatLng, {
                            icon_options: {}
                        });
                        $("input[name=map_lat]").attr("value", dataLatLng[0]);
                        $("input[name=map_lng]").attr("value", dataLatLng[1]);
                    });

                    $('[name="location_id"]').on('change', function (e) {
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

            $('#job_type_id').select2();
        })
    </script>
@endsection
