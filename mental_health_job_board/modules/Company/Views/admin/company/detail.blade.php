@extends('admin.layouts.app')
@section('content')
    <?php

    use Modules\User\Models\Plan;

    $user = \Illuminate\Support\Facades\Auth::user();
    ?>
    <form id='company_form_admin'
          action="{{route('company.admin.store',['id'=>($row->id) ? $row->id : '-1','lang'=>request()->query('lang')])}}"
          method="post" class="dungdt-form">
        <div class="container-fluid">
            <div class="d-flex justify-content-between mb20">
                <div class="">
                    <h1 class="title-bar">{{$row->id ? __('Edit Company: :name',['name'=>$translation->name]) : __('Add new Company')}}</h1>
                    @if($row->slug)
                        <p class="item-url-demo">{{__("Permalink")}}
                            : {{ url( (request()->query('lang') ? request()->query('lang').'/' : '').config('companies.companies_route_prefix'))  }}
                            /<a href="#" class="open-edit-input" data-name="slug">{{$row->slug}}</a>
                        </p>
                    @endif
                </div>
                <div class="">
                    <div class="flex">
                        @if($row->id)
                            <a class="btn btn-primary btn-sm" href="{{ route('user.admin.detail', ['id' => $row?->author->id]) }}"><i class="fa fa-pencil"></i> {{ __("Edit User") }}</a>
                        @endif
                        <a class="btn btn-success btn-sm" href="{{ $row->getDetailUrl(request()->query('lang')) }}" target="_blank"><i class="fa fa-eye"></i> {{ __("View Company") }}</a>
                    </div>
                </div>
            </div>
            <div id='notification_messages'><strong></strong></div>
            @include('admin.message')
            @if($row->id)
                @include('Language::admin.navigation')
            @endif
            <div class="lang-content-box">
                <div class="row">
                    <div class="col-md-9">
                        <div class="panel">
                            <div class="panel-title"><strong>{{ __('Company content')}}</strong></div>
                            <div class="panel-body">
                                @csrf
                                @include('Company::admin/company/form',['row'=> $row])
                            </div>
                        </div>
                        @if(is_default_lang())
                            <div class="panel" id='map_parent_panel'>
                                <div class="panel-title" style="display: flex; justify-content: space-between;">
                                    <strong style="align-self: center">{{__("Company Location")}}</strong>
                                    <button id='add_location' class="classBtn" type="button" style='padding: 10px'><i
                                            class="fa fa-save" style="padding-right: 5px"></i> {{__('Add Location')}}
                                    </button>
                                </div>
                                <div data-offices="{{ $offices ?? '' }}" id='offices_data'></div>
                                <div class="panel-body" id='map_panel_0'>
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
                                            $traverse($company_location);
                                            ?>
                                        <div class="form-group col-md-12 col-sm-12 p-0 location smart-search">
                                            <span class="icon flaticon-map-locator"></span>
                                            <input id="admin_location_select_0" type="text"
                                                   class="smart-search-location parent_text form-control"
                                                   placeholder="{{__("All Locations")}}"
                                                   value="{{ $row->location->name ?? $location_name }}"
                                                   data-onLoad="{{__("Loading...")}}"
                                                   data-default="" required>
                                            <input type="hidden" class="child_id" name="request_location_id"
                                                   id="request_location_id_0"
                                                   value="{{$row->location_id ?? Request::query('location_id')}}">
                                            <input
                                                type="hidden"
                                                class="child_id"
                                                name="location_id"
                                                id="location_id_0"
                                                value="{{ $row->location->id ?? $location_id  }}"
                                                data-map_lng="{{ $row->location->map_lng ?? ''}}"
                                                data-map_zoom="{{ $row->location->map_zoom ?? ''}}"
                                                data-map_lat="{{ $row->location->map_lat ?? ''}}"
                                            >
                                        </div>
                                        <div class='d-flex justify-content-between'>
                                            <div>
                                                <input type="checkbox" id="is_main_0" data-checkbox='true'>
                                                <label for="is_main">{{__("Select this map as the main one")}}</label>
                                            </div>
                                            <div>
                                                <button id='delete_button_0' class="btn btn-danger" type="button"><i
                                                        class="fa fa-trash"></i> {{__('Delete')}}</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">{{__("The geographic coordinate")}}</label>
                                        <div class="control-map-group">
                                            <div id="map_content_0" data-is_map_content='true'></div>
                                            <input type="text" placeholder="{{__("Search by name...")}}"
                                                   class="bravo_searchbox form-control" autocomplete="off"
                                                   onkeydown="return event.key !== 'Enter';">
                                            <div class="g-control">
                                                <div class="form-group">
                                                    <label>{{__("Map Latitude")}}:</label>
                                                    <input type="text" name="map_lat" id="map_lat_0"
                                                           class="form-control"
                                                           value="{{old('map_lat', $row->map_lat ?? '34.0522')}}"
                                                           readonly
                                                           onkeydown="return event.key !== 'Enter';">
                                                </div>
                                                <div class="form-group">
                                                    <label>{{__("Map Longitude")}}:</label>
                                                    <input type="text" name="map_lng" id="map_lng_0"
                                                           class="form-control"
                                                           value="{{old('map_lng', $row->map_lng ?? '-118.244')}}"
                                                           readonly
                                                           onkeydown="return event.key !== 'Enter';">
                                                </div>
                                                <div class="form-group">
                                                    <label>{{__("Map Zoom")}}:</label>
                                                    <input type="text" name="map_zoom" id="map_zoom_0"
                                                           class="form-control"
                                                           value="{{old('map_zoom', $row->map_zoom ?? "12")}}" readonly
                                                           onkeydown="return event.key !== 'Enter';">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input
                                type="hidden"
                                name="offices"
                                id="offices_data_id"
                                value=""
                            >
                        @endif
                        @include('Core::admin/seo-meta/seo-meta')
                    </div>

                    <div class="col-md-3">
                        <div class="panel">
                            <div class="panel-title"><strong>{{__('Publish')}}</strong></div>
                            <div class="panel-body">
                                @if(is_default_lang())
                                    <div>
                                        <label><input @if($row->status=='publish') checked @endif type="radio"
                                                      name="status" value="publish"> {{__("Publish")}}
                                        </label></div>
                                    <div>
                                        <label><input @if($row->status=='draft') checked @endif type="radio"
                                                      name="status" value="draft"> {{__("Draft")}}
                                        </label></div>
                                @endif
                                <div class="text-right">
                                    <button class="btn btn-primary" id='submit_button'><i
                                            class="fa fa-save"></i> {{__('Save Changes')}}</button>
                                </div>
                            </div>
                        </div>

                        @if(is_default_lang() && isset($row->id))
                            <div class="panel">
                                <div class="panel-title"><strong>{{__('Plan')}}</strong></div>
                                <div class="panel-body">
                                    <div class="alert alert-success success-msg" role="alert" style="display:none">
                                        Plan changed
                                    </div>
                                    <div class="alert alert-danger error-msg" role="alert" style="display:none">
                                        Error while changing plan
                                    </div>

                                    <div class="row">
                                        <div class="col-6">
                                            Current plan : <strong
                                                class="current-plan">{{ isset($row->currentPlan) ? $row->currentPlan->plan_data['title'] : 'None'}} </strong>
                                        </div>
                                        <div
                                            class="col-6 text-right" {{ isset($row->currentPlan) ? '' : 'style=display:none'}} >
                                            <button data-url="{{route('company.admin.cancelPlan', $row->id)}}"
                                                    class="btn btn-danger cancel-plan" type="button">Cancel
                                            </button>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="form-group">
                                        <label for="plan_id"
                                               class="col-form-label text-md-right">{{ __('Apply new plan:') }}</label>
                                        <select id="plan_id" class="form-control" name="plan_id">
                                            @php
                                                $plans = $row->author->role->allowedPlans()->orderBy('price', 'desc')->get();
                                            @endphp
                                            @foreach($plans as $plan)
                                                <option value="{{$plan->id}}">{{$plan->title}}</option>
                                            @endforeach
                                        </select>
                                        <label for="month_count"
                                               class="col-form-label text-md-right">{{ __('Month count:') }}</label>
                                        <select id="month_count" class="form-control" name="month_count">
                                            @php
                                                $months = [1,2,3,4,5,6,7,8,9,10,11,12,24,36];
                                            @endphp
                                            @foreach($months as $month)
                                                <option value="{{$month}}">{{$month}}</option>
                                            @endforeach
                                        </select>
                                        <div class="text-right mt-2">
                                            <button data-url="{{route('company.admin.applyPlan', $row->id)}}"
                                                    class="btn btn-primary change-plan" type="button">Apply
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endif
                        {{--                        @if(is_default_lang())--}}
                        {{--                            <div class="panel">--}}
                        {{--                                <div class="panel-title"><strong>{{__('Categories')}}</strong></div>--}}
                        {{--                                <div class="panel-body">--}}
                        {{--                                    <div class="form-group">--}}
                        {{--                                        <select id="cat_id" class="form-control" name="category_id">--}}
                        {{--                                            <?php--}}
                        {{--                                            $selectedIds = !empty($row->category_id) ? explode(',', $row->category_id) : [];--}}
                        {{--                                            $traverse = function ($categories, $prefix = '') use (&$traverse, $selectedIds) {--}}
                        {{--                                                foreach ($categories as $category) {--}}
                        {{--                                                    $selected = '';--}}
                        {{--                                                    if (in_array($category->id, $selectedIds))--}}
                        {{--                                                        $selected = 'selected';--}}
                        {{--                                                    printf("<option value='%s' %s>%s</option>", $category->id, $selected, $prefix . ' ' . $category->name);--}}
                        {{--                                                    $traverse($category->children, $prefix . '-');--}}
                        {{--                                                }--}}
                        {{--                                            };--}}
                        {{--                                            $traverse($categories);--}}
                        {{--                                            ?>--}}
                        {{--                                        </select>--}}
                        {{--                                    </div>--}}
                        {{--                                </div>--}}
                        {{--                            </div>--}}
                        {{--                        @endif--}}
                        @if(is_admin() && is_default_lang())
                            <div class="panel">
                                <div class="panel-title"><strong>{{__('Featured')}}</strong></div>
                                <div class="panel-body">
                                    <div>
                                        <label><input @if($row->is_featured) checked @endif type="checkbox"
                                                      name="is_featured" value="1"> {{__("is Featured")}}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="panel">
                                <div class="panel-title"><strong>{{__("Employer")}}</strong></div>
                                <div class="panel-body">
                                    <div class="form-group">
                                            <?php
                                            $user = !empty($row->create_user) ? App\User::find($row->owner_id) : false;
                                            \App\Helpers\AdminForm::select2('owner_id', [
                                                'configs' => [
                                                    'ajax' => [
                                                        'url' => url('/admin/module/user/getForSelect2'),
                                                        'dataType' => 'json'
                                                    ],
                                                    'allowClear' => true,
                                                    'placeholder' => __('-- Select Employer --')
                                                ]
                                            ], !empty($user->id) ? [
                                                $user->id,
                                                $user->getDisplayName() . ' (#' . $user->id . ')'
                                            ] : false)
                                            ?>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(is_default_lang())
                            @include('Company::admin.company.attributes')
                            <div class="panel">
                                <div class="panel-body">
                                    <h3 class="panel-body-title"> {{ __('Logo')}}
                                        ({{__('Recommended size image:330x300px')}})</h3>
                                    <div class="form-group">
                                        {!! \Modules\Media\Helpers\FileHelper::fieldUpload('avatar_id',$row->avatar_id) !!}
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="panel">
                            <div class="panel-title"><strong>{{ __('Social Media')}}</strong></div>
                            <div class="panel-body">
                                <?php $socialMediaData = $row->social_media; ?>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="social-skype"><i
                                                class="fa fa-skype"></i></span>
                                    </div>
                                    <input type="text" class="form-control" autocomplete="off"
                                           name="social_media[skype]" value="{{ $socialMediaData['skype'] ?? '' }}"
                                           placeholder="{{__('Skype')}}" aria-label="{{__('Skype')}}"
                                           aria-describedby="social-skype">
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="social-facebook"><i
                                                class="fa fa-facebook"></i></span>
                                    </div>
                                    <input type="text" class="form-control" autocomplete="off"
                                           name="social_media[facebook]"
                                           value="{{ $socialMediaData['facebook'] ?? '' }}"
                                           placeholder="{{__('Facebook')}}" aria-label="{{__('Facebook')}}"
                                           aria-describedby="social-facebook">
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="social-twitter"><i class="fa-brands fa-x-twitter"></i></span>
                                    </div>
                                    <input type="text" class="form-control" autocomplete="off"
                                           name="social_media[twitter]" value="{{$socialMediaData['twitter'] ?? ''}}"
                                           placeholder="{{__('Twitter')}}" aria-label="{{__('Twitter')}}"
                                           aria-describedby="social-twitter">
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="social-instagram"><i
                                                class="fa fa-instagram"></i></span>
                                    </div>
                                    <input type="text" class="form-control" autocomplete="off"
                                           name="social_media[instagram]"
                                           value="{{$socialMediaData['instagram'] ?? ''}}"
                                           placeholder="{{__('Instagram')}}" aria-label="{{__('Instagram')}}"
                                           aria-describedby="social-instagram">
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="social-linkedin"><i
                                                class="fa fa-linkedin"></i></span>
                                    </div>
                                    <input type="text" class="form-control" autocomplete="off"
                                           name="social_media[linkedin]" value="{{$socialMediaData['linkedin'] ?? ''}}"
                                           placeholder="{{__('Linkedin')}}" aria-label="{{__('Linkedin')}}"
                                           aria-describedby="social-linkedin">
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="social-google"><i
                                                class="fa fa-google"></i></span>
                                    </div>
                                    <input type="text" class="form-control" autocomplete="off"
                                           name="social_media[google]" value="{{@$socialMediaData['google'] ?? ''}}"
                                           placeholder="{{__('Google')}}" aria-label="{{__('Google')}}"
                                           aria-describedby="social-google">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('script.body')
    {!! App\Helpers\MapEngine::scripts() !!}
    <script>
        $(document).ready(function () {
            $('#category_id').select2();
        });
        const officesData = JSON.parse($('#offices_data').attr('data-offices'))
        const url = "{{route('company.admin.store',['id'=>($row->id) ? $row->id : '-1','lang'=>request()->query('lang')])}}"
        let mapCounter = officesData && officesData.length > 0 ? officesData.length - 1 : 0;

        const addNewMap = (index, map_lng, map_lat, map_zoom) => {
            return jQuery(function ($) {
                new BravoMapEngine('map_content_' + index, {
                    disableScripts: true,
                    fitBounds: true,
                    center: [map_lat, map_lng],
                    zoom: map_zoom,
                    ready: function (engineMap) {
                        engineMap.addMarker([map_lat, map_lng], {
                            icon_options: {}
                        });
                        engineMap.on('click', function (dataLatLng) {
                            engineMap.clearMarkers();
                            engineMap.addMarker(dataLatLng, {
                                icon_options: {}
                            });
                            $("#map_lat_" + index).attr("value", dataLatLng[0]);
                            $("#map_lng_" + index).attr("value", dataLatLng[1]);
                        });
                        engineMap.on('zoom_changed', function (zoom) {
                            $("#map_zoom_" + index).attr("value", zoom);
                        });
                        engineMap.searchBox($('#customPlaceAddress' + index), function (dataLatLng) {
                            engineMap.clearMarkers();
                            engineMap.addMarker(dataLatLng, {
                                icon_options: {}
                            });
                            $("#map_lat_" + index).attr("value", dataLatLng[0]);
                            $("#map_lng_" + index).attr("value", dataLatLng[1]);
                        });
                        engineMap.searchBox($('.bravo_searchbox'), function (dataLatLng) {
                            engineMap.clearMarkers();
                            engineMap.addMarker(dataLatLng, {
                                icon_options: {}
                            });
                            $("#map_lat_" + index).attr("value", dataLatLng[0]);
                            $("#map_lng_" + index).attr("value", dataLatLng[1]);
                        });

                        $('#location_id_' + index).on('change', function (e) {
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

                            $("#map_lat_" + index).attr("value", dataLatLng[0]);
                            $("#map_lng_" + index).attr("value", dataLatLng[1]);
                        });
                    }
                });
            });
        }
        const neededFields = ['map_lat', 'map_lng', 'map_zoom', 'location_id', 'is_main']

        const transformData = (formData) => {
            let formattedData = []
            let counter = 0
            formData.forEach((el, index) => {
                if (el.name === 'location_id') {
                    let obj = {}
                    let arrayWithEachMapData = formData.slice(index, index + 4)
                    const checkboxes = $('#is_main_' + counter).is(':checked')
                    if (checkboxes) {
                        arrayWithEachMapData.unshift({name: 'is_main', value: 1})
                    } else {
                        arrayWithEachMapData.unshift({name: 'is_main', value: 0})
                    }
                    arrayWithEachMapData.forEach(eachEl => {
                        if (neededFields.includes(eachEl.name)) {
                            obj[eachEl.name] = eachEl.value
                        }
                    })
                    formattedData.push(obj)
                    counter++
                }
            })
            return formattedData
        }


        $('#submit_button').on('click', function (e) {
            if (!$('#company_form_admin')[0].checkValidity()) {
                return
            }
            const formData = $('#company_form_admin').serializeArray()
            const formattedData = transformData(formData)
            $('#offices_data_id').attr('value', JSON.stringify(formattedData))
            $('#company_form_admin').trigger('submit')
        })


        const onCheckBoxClick = (e) => {
            var checkboxes = $('input[data-checkbox]');
            for (var i = 0; i < checkboxes.length; i++) {
                if (e.target.id !== checkboxes[i].id) {
                    checkboxes[i].checked = false;
                }
            }
            e.checked = true
        }

        const deleteCompany = (e) => {
            const mapId = e.target.id.replace(/\D/g, "");
            $('#map_panel_' + mapId).remove()
        }

        $(document).ready(function () {
            if (officesData.length === 0) {
                $("#delete_button_0").hide()
                return addNewMap(0, "-118.244", "34.0522", "12")
            }
            officesData.forEach((office, index) => {
                index > 0 && $('#map_panel_0').clone().attr("id", "map_panel_" + index).appendTo('#map_parent_panel')
                const newMapContainer = $("#map_panel_" + index)
                newMapContainer.find('#map_lat_0').attr("id", "map_lat_" + index).attr('value', office.map_lat)
                newMapContainer.find('#map_lng_0').attr("id", "map_lng_" + index).attr('value', office.map_lng)
                newMapContainer.find('#location_id_0').attr("id", "location_id_" + index).attr('value', office.location_id)
                newMapContainer.find('#is_main_0').attr("id", "is_main_" + index).prop('checked', office.is_main)
                newMapContainer.find('#map_zoom_0').attr("id", "map_zoom_" + index).attr('value', office.map_zoom)
                newMapContainer.find('#map_content_0').attr("id", "map_content_" + index)
                newMapContainer.find('#delete_button_0').attr("id", "delete_button_" + index)
                newMapContainer.find('#admin_location_select_0').attr("id", "admin_location_select_" + index).attr('value', office.name)
                window.applySmartSearchLocation(newMapContainer.find('#admin_location_select_' + index));
                newMapContainer.find('#delete_button_0').attr("id", "delete_button_" + index)
                $("#is_main_" + index).on('change', onCheckBoxClick)
                $("#delete_button_" + index).on('click', deleteCompany)
                index === 0 ?
                    $("#delete_button_" + index).hide()
                    : $("#delete_button_" + index).show()
                return addNewMap(index, office.map_lng, office.map_lat, office.map_zoom)
            })
        });


        const addDiv = () => {
            mapCounter++
            $('#map_panel_0').clone().attr("id", "map_panel_" + mapCounter).appendTo('#map_parent_panel')
            const newMapContainer = $("#map_panel_" + mapCounter)
            newMapContainer.find('#map_lat_0').attr("id", "map_lat_" + mapCounter)
            newMapContainer.find('#map_lng_0').attr("id", "map_lng_" + mapCounter)
            newMapContainer.find('#location_id_0').attr("id", "location_id_" + mapCounter)
            newMapContainer.find('#map_zoom_0').attr("id", "map_zoom_" + mapCounter)
            newMapContainer.find('#map_content_0').attr("id", "map_content_" + mapCounter)
            newMapContainer.find('#delete_button_0').attr("id", "delete_button_" + mapCounter)
            newMapContainer.find('#is_main_0').attr("id", "is_main_" + mapCounter).prop('checked', false)
            newMapContainer.find('#admin_location_select_0').attr("id", "admin_location_select_" + mapCounter).val('')
            window.applySmartSearchLocation(newMapContainer.find('#admin_location_select_' + mapCounter));
            $("#is_main_" + mapCounter).on('change', onCheckBoxClick)
            $("#delete_button_" + mapCounter).on('click', deleteCompany)
            mapCounter === 0 ?
                $("#delete_button_" + mapCounter).hide()
                : $("#delete_button_" + mapCounter).show()
            return addNewMap(mapCounter, "-118.244", "34.0522", "12")
        }
        //                addNewMap(mapCounter)
        $("#is_main_0").on('change', onCheckBoxClick)
        $('.classBtn').on('click', addDiv);
    </script>
@endsection
