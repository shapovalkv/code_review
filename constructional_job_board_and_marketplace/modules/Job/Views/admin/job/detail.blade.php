@extends('admin.layouts.app')

@section('content')
    <form action="{{route('job.admin.store',['id'=>($row->id) ? $row->id : '-1','lang'=>request()->query('lang')])}}"
          method="post">
        @csrf
        <div class="container-fluid">
            <div class="d-flex justify-content-between mb20">
                <div class="">
                    <h1 class="title-bar">{{$row->id ? __('Edit: ').$row->title : __('Add new job')}}</h1>
                    @if($row->slug)
                        <p class="item-url-demo">{{__("Permalink")}}: {{ url(config('job.job_route_prefix') ) }}/<a
                                href="#" class="open-edit-input" data-name="slug">{{$row->slug}}</a>
                        </p>
                    @endif
                </div>
                <div class="">
                    @if($row->slug)
                        <a class="btn btn-default btn-sm" href="{{ $row->getDetailUrl() }}" target="_blank"><i
                                class="fa fa-eye"></i> {{__("View Job")}}</a>
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
                                    <input type="text" value="{{ old('title', $translation->title) }}"
                                           placeholder="{{__("Title")}}" name="title" required class="form-control">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">{{__("Content")}}</label>
                                    <div class="">
                                        <textarea name="content" class="d-none has-ckeditor" cols="30"
                                                  rows="10">{{ old('content', $translation->content) }}</textarea>
                                    </div>
                                </div>
                                @if(is_default_lang())
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{__("Expiration Date")}}</label>
                                                <input type="text" readonly
                                                       value="{{ old( 'expiration_date', $row->expiration_date ? display_date($row->expiration_date) : '' ) }}"
                                                       placeholder="MM/DD/YYYY" name="expiration_date"
                                                       autocomplete="false"
                                                       class="form-control has-easepick bg-white">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{__("Hours")}}</label>
                                                <div class="input-group">
                                                    <input type="text" value="{{ old('hours', $row->hours) }}"
                                                           placeholder="{{__("hours")}}" name="hours"
                                                           class="form-control">
                                                    <div class="input-group-append">
                                                        <select class="form-control" name="hours_type">
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
                                                <label>{{__("Salary")}}</label>
                                                <div class="input-group">
                                                    <input type="text" value="{{ old('salary_min', $row->salary_min) }}"
                                                           placeholder="{{__("Min")}}" name="salary_min"
                                                           class="form-control">
                                                    <input type="text" value="{{ old('salary_max', $row->salary_max) }}"
                                                           placeholder="{{__("Max")}}" name="salary_max"
                                                           class="form-control">
                                                    <div class="input-group-append">
                                                        <select class="form-control" name="salary_type">
                                                            <option value="hourly"
                                                                    @if(old('salary_type', $row->salary_type) == 'hourly') selected @endif > {{ __("/hourly") }} </option>
                                                            <option value="daily"
                                                                    @if(old('salary_type', $row->salary_type) == 'daily') selected @endif >{{ __("/daily") }}</option>
                                                            <option value="weekly"
                                                                    @if(old('salary_type', $row->salary_type) == 'weekly') selected @endif >{{ __("/weekly") }}</option>
                                                            <option value="monthly"
                                                                    @if(old('salary_type', $row->salary_type) == 'monthly') selected @endif >{{ __("/monthly") }}</option>
                                                            <option value="yearly"
                                                                    @if(old('salary_type', $row->salary_type) == 'yearly') selected @endif >{{ __("/yearly") }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <label class="mt-2">
                                                    <input type="checkbox" name="wage_agreement"
                                                           @if(old('wage_agreement', $row->wage_agreement)) checked
                                                           @endif value="1"/> {{ __("Wage Agreement") }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{__("Experience")}}</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control"
                                                           placeholder="{{ __("Experience") }}" name="experience"
                                                           value="{{ old('experience',$row->experience) }}">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"
                                                              style="font-size: 14px;">{{ __("year(s)") }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{__("Type of Experience")}}</label>
                                                <div class="input-group-append">
                                                    <select
                                                        id="seniority_level"
                                                        class="form-control"
                                                        name="seniority_level"
                                                        multiple="multiple"
                                                    >
                                                        <option value="newbie"
                                                                @if(old('seniority_level', str_contains($row->seniority_level, 'newbie')) == 'newbie') selected @endif > {{ __("Newbie / Journeyman") }} </option>
                                                        <option value="commercial"
                                                                @if(old('seniority_level', str_contains($row->seniority_level, 'commercial')) == 'commercial') selected @endif >{{ __("Commercial") }}</option>
                                                        <option value="residential"
                                                                @if(old('seniority_level', str_contains($row->seniority_level, 'residential')) == 'residential') selected @endif >{{ __("Residential") }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-6">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{__("Number Of Recruitments")}}</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control"
                                                           placeholder="{{ __("0") }}" name="number_recruitments"
                                                           value="{{ old('number_recruitments',$row->number_recruitments) }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @if(is_default_lang())
                            <div class="panel">
                                <div class="panel-title"><strong>{{__("Job Location")}}</strong></div>
                                <div class="panel-body">

                                    <div class="form-group">
                                        <label class="control-label">{{__("Location")}}</label>
                                        <div class="form-group-smart-search">
                                            <div class="form-content">
                                                <div class="smart-search">
                                                    <input
                                                        type="text"
                                                        placeholder="{{__("Location")}}"
                                                        name="map_location"
                                                        class="bravo_searchbox form-control"
                                                        autocomplete="off"
                                                        onkeydown="return event.key !== 'Enter';"
                                                        value="{{ old('map_location', $row->location->map_location ?? '') }}"
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label">{{__("The geographic coordinate")}}</label>
                                        <div class="control-map-group">
                                            <div id="map_content"></div>
                                            <div class="g-control">
                                                <div class="form-group">
                                                    <label>{{__("Map Latitude")}}:</label>
                                                    <input
                                                        type="text"
                                                        name="map_lat"
                                                        class="form-control"
                                                        value="{{ old('map_lat', $row->location->map_lat ?? '') }}"
                                                        readonly
                                                        onkeydown="return event.key !== 'Enter';"
                                                    >
                                                </div>
                                                <div class="form-group">
                                                    <label>{{__("Map Longitude")}}:</label>
                                                    <input
                                                        type="text"
                                                        name="map_lng"
                                                        class="form-control"
                                                        value="{{ old('map_lng', $row->location->map_lng ?? '') }}"
                                                        readonly
                                                        onkeydown="return event.key !== 'Enter';"
                                                    >
                                                </div>
                                                <div class="form-group">
                                                    <label>{{__("Map Zoom")}}:</label>
                                                    <input
                                                        type="text"
                                                        name="map_zoom"
                                                        class="form-control"
                                                        value="{{ old('map_zoom', $row->location->map_zoom ?? '') }}"
                                                        readonly
                                                        onkeydown="return event.key !== 'Enter';"
                                                    >
                                                </div>
                                                <div class="form-group">
                                                    <label>{{__("Map State")}}:</label>
                                                    <input
                                                        type="text"
                                                        name="map_state"
                                                        class="form-control"
                                                        value="{{old('map_state', $row->location->map_state ?? "")}}"
                                                        readonly
                                                        onkeydown="return event.key !== 'Enter';"
                                                    >
                                                </div>
                                                <div class="form-group">
                                                    <label>{{__("Map State Long")}}:</label>
                                                    <input
                                                        type="text"
                                                        name="map_state_long"
                                                        class="form-control"
                                                        value="{{old('map_state_long', $row->location->map_state_long ?? "")}}"
                                                        readonly
                                                        onkeydown="return event.key !== 'Enter';"
                                                    >
                                                </div>
                                                <div class="form-group">
                                                    <label>{{__("Map City")}}:</label>
                                                    <input
                                                        type="text"
                                                        name="map_city"
                                                        class="form-control"
                                                        value="{{old('map_city', $row->location->map_city ?? "")}}"
                                                        readonly
                                                        onkeydown="return event.key !== 'Enter';"
                                                    >
                                                </div>
                                                <div class="form-group">
                                                    <label>{{__("Map Address")}}:</label>
                                                    <input
                                                        type="text"
                                                        name="map_address"
                                                        class="form-control"
                                                        value="{{old('map_address', $row->location->map_address ?? "")}}"
                                                        readonly
                                                        onkeydown="return event.key !== 'Enter';"
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @endif

                        @include('Core::admin/seo-meta/seo-meta')
                    </div>
                    <div class="col-md-3">
                        <div class="panel">
                            <div class="panel-title"><strong>{{__('Publish')}}</strong></div>
                            <div class="panel-body">
                                @if(is_default_lang())
                                    <div>
                                        <label><input @if(old('status', $row->status) =='publish') checked
                                                      @endif type="radio" name="status"
                                                      value="publish"> {{__("Publish")}}</label>
                                    </div>
                                    <div>
                                        <label><input @if(old('status', $row->status)=='draft') checked
                                                      @endif type="radio" name="status" value="draft"> {{__("Draft")}}
                                        </label>
                                    </div>
                                @endif
                                <div class="text-right">
                                    <button class="btn btn-primary" type="submit"><i
                                            class="fa fa-save"></i> {{__('Save Changes')}}</button>
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
                            @endif

                            <div class="panel">
                                <div class="panel-title"><strong>{{__("Availability")}}</strong></div>
                                <div class="panel-body">
                                    @if(is_admin())
                                        <div class="form-group">
                                            <label>{{__('Job Featured')}}</label>
                                            <br>
                                            <label>
                                                <input type="checkbox" name="is_featured"
                                                       @if(old('is_featured', $row->is_featured)) checked
                                                       @endif value="1"> {{__("Enable featured")}}
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
                                            <select name="category_id" class="form-control">
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
                                            <select name="job_type_id" class="form-control">
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
                            <div class="panel">
                                <div class="panel-title"><strong>{{__("Job Skills")}}</strong></div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="">
                                            <select id="job_type_id" name="job_skills[]" class="form-control"
                                                    multiple="multiple">
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
                                        </div>
                                    </div>
                                </div>
                            </div>

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
                                                ] : false)
                                                ?>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@section ('script.body')
    {!! App\Helpers\MapEngine::scripts() !!}
    <script src="{{url('libs/easepick/easepick.min.js')}}"></script>

    <script>
        new easepick.create({
            element: ".has-easepick",
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


        jQuery(function ($) {
            new BravoMapEngine('map_content', {
                disableScripts: true,
                fitBounds: true,
                center: [{{$row->location->map_lat ?? "38.91"}}, {{$row->location->map_lng ?? "-77.03"}}],
                zoom: {{ $row->location->map_zoom ?? "8"}},
                ready: function (engineMap) {
                    @if($row->location && $row->location->map_lat &&  $row->location->map_lng)
                    engineMap.addMarker([{{ $row->location->map_lat}}, {{ $row->location->map_lng}}], {
                        icon_options: {}
                    });
                    @endif
                    engineMap.on('click', function (dataLatLng) {
                        engineMap.clearMarkers();
                        engineMap.addMarker(dataLatLng, {
                            icon_options: {}
                        });
                        const { city, state, state_long, address } = dataLatLng[3]

                        $("input[name=map_lat]").val(dataLatLng[0]);
                        $("input[name=map_lng]").val(dataLatLng[1]);
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
                        $("input[name=map_state]").val(state);
                        $("input[name=map_state_long]").val(state_long);
                        $("input[name=map_city]").val(city);
                        $("input[name=map_address]").val(address);
                    });
                }
            });

            $('#seniority_level').select2({ placeholder: "Select Your level", tokenSeparators: [','], multiple: true });

            $('#job_type_id').select2({
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
                        let option = $(`#job_type_id [value="${tag.text}"]`)
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
                const currentValues = $('#job_type_id').val()

                if (currentValues.includes(term)) {
                    return false
                }

                var newOption = new Option(term, term, false, false);
                $('#job_type_id').append(newOption).val([...currentValues, term]).trigger('change');
            })
        })
    </script>
@endsection
