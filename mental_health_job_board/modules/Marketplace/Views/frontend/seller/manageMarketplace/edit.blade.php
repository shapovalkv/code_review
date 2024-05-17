@extends('layouts.user')

@section('content')
    @php
        $languages = \Modules\Language\Models\Language::getActive();
    @endphp
    <form method="post"
          id="announcement_form"
          @if($row->id)
              action="{{ route('seller.marketplace.update', ['marketplace'=>$row->id, 'lang'=>request()->query('lang')] ) }}"
          @else
              action="{{ route('seller.marketplace.store', ['lang'=>request()->query('lang')] ) }}"
          @endif

          class="default-form">
        @csrf
        <input type="hidden" name="id" value="{{$row->id}}">
        <div class="upper-title-box">
            <div class="row">
                <div class="col-md-9">
                    <h3>{{$row->id ? __('Edit: ').$row->title : __('Post on Marketplace')}}</h3>
                    <div class="text">
                        @if($row->slug)
                            <p class="item-url-demo">{{__("Permalink")}}: {{ url('marketplace') }}/<a href="#"
                                                                                               class="open-edit-input"
                                                                                               data-name="slug">{{$row->slug}}</a>
                            </p>
                        @endif
                    </div>
                </div>
                <div class="col-md-3 text-right">
                    @if($row->slug)
                        <a href="{{$row->getDetailUrl(request()->query('lang'))}}" target="_blank" class="btn btn-style-ten text-light ml-3"><i
                                class="la la-eye"></i> @if ($row->status === "publish"){{__("View Announcement")}}@else{{__("Preview Announcement")}}@endif</a>
                    @endif
                </div>
            </div>
        </div>
        @include('admin.message')

        @if (\Illuminate\Support\Facades\Cache::has(auth()->id() . \Modules\Marketplace\Models\Marketplace::CACHE_KEY_DRAFT) && request()->routeIs('seller.marketplace.create'))
            <div class="alert alert-warning alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{!! __('You are editing an unsaved post from a previous session. <a href=":route">Start over</a>', ['route' => route('seller.marketplace.create', ['cache' => 'clear'])]) !!}</strong>
            </div>
        @endif

        @if($row->id)
            @include('Language::admin.navigation')
        @endif

        <div class="row">
            <div class="col-xl-9">
                <!-- Ls widget -->
                <div class="ls-widget">
                    <div class="widget-title"><h4>{{ __('Category')}}<span class="text-danger">*</span></h4></div>
                    <div class="widget-content" id="categoryBlock">
                        @foreach ($categories as $category)
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="cat_id" value="{{ $category->id }}"
                                           @if(!empty($category->id == old('cat_id', $row->cat_id))) checked @endif class="onChangeAutoSave">
                                    {{ $category->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="tabs-box">
                        <div class="widget-title"><h4>{{ __("Announcement Content") }}</h4></div>
                        <div class="widget-content">
                            <div class="form-group">
                                <label>{{__("Title")}} <span class="text-danger">*</span></label>
                                <input type="text" name="title" value="{{old('title', $row->title)}}" required
                                       placeholder="{{__("Name of the Announcement")}}" class="form-control onChangeAutoSave">
                            </div>
                        </div>
                    </div>

                    <div class="widget-title"><h4>{{ __("Description") }}</h4></div>
                    <div class="widget-content">
                        <div class="form-group">
                            <label>{{ __("Announcement Description") }}</label>
                            <textarea name="content" class="d-none has-ckeditor onChangeAutoSave" cols="30"
                                      rows="10">{{ old('content', $row->content) }}</textarea>
                        </div>
                    </div>


                    <div class="widget-title"><h4>{{ __("Choose Date (Applies for trainings)")}}</h4></div>
                    <div class="widget-content">
                        <div class="form-group">
                            <input type="text"
                                   value="{{ old('announcement_date', $row->announcement_date? display_date($row->announcement_date) :'') }}"
                                   name="announcement_date" placeholder="{{__("Choose Date")}}" class="form-control has-datepicker onChangeAutoSave"
                                   autocomplete="off">
                            <a class="btn btn-link" onclick="clearDatePicker($(this).prev())" style="position: absolute; top: 5px; right: 0;">{{__('Clear')}}</a>
                        </div>
                    </div>

                    <div class="widget-title"><h4>{{ __("Website (optional)") }}</h4></div>
                    <div class="widget-content">
                        <div class="form-group">
                            <input type="text" value="{{old('website',$row->website)}}" name="website"
                                   placeholder="{{__("Website")}}" class="form-control onChangeAutoSave">
                        </div>
                    </div>
                </div>

                <div class="ls-widget">
                    <div class="tabs-box" id='mapContainer'>
                        <div class="widget-title"><h4>{{ __("Location (optional)") }}</h4></div>
                        <div class="widget-content">
                            <div class="form-group">
                                <label class="control-label">{{__("Location")}}</label>
                                <?php
                                $location_name = "";
                                $list_json = [];
                                $location_id = request()->get('location_id');
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
                                $traverse($marketplace_location);
                                ?>
                                <div class="form-group col-md-12 col-sm-12 p-0 location smart-search">
                                    <span class="icon flaticon-map-locator"></span>
                                    <input type="text" class="smart-search-location parent_text form-control" onchange="if(typeof onChangeMapAutoSave === 'function') {onChangeMapAutoSave($(this))}"
                                           placeholder="{{__("Type City Name and Choose Location")}}"
                                           value="{{ old('location_name', $row->location->name ?? $location_name) }}"
                                           data-onLoad="{{__("Loading...")}}"
                                           data-default="">
                                    <input
                                        type="hidden"
                                        class="child_id"
                                        name="location_id"
                                        value="{{ $row->location->id ?? $location_id  }}"
                                        data-map_lng="{{ $row->location->map_lng ?? ''}}"
                                        data-map_zoom="{{ $row->location->map_zoom ?? ''}}"
                                        data-map_lat="{{ $row->location->map_lat ?? ''}}"
                                        value="{{ old('location', old('location_id', $row->location->id ?? $location_id))  }}"
                                    >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">{{__("Location")}}</label>
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

                <div class="col-md-12">
                    <div class="form-group">
                        <label>{{__("Post Thumbnail Image")}}</label>
                        <div class="form-group">
                            {!! \Modules\Media\Helpers\FileHelper::fieldUpload('thumbnail_id',$row->thumbnail_id ?? old('thumbnail_id')) !!}
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label">{{__("Photo Gallery")}} ({{__('Recommended size image:1080 x 1920px')}}
                            )</label>
                        {!! \Modules\Media\Helpers\FileHelper::fieldGalleryUpload('gallery', $row->gallery ?? old('gallery')) !!}
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label>{{__("Video Cover Image")}}</label>
                        <div class="form-group">
                            {!! \Modules\Media\Helpers\FileHelper::fieldUpload('video_cover_image_id',$row->video_cover_image_id ?? old('video_cover_image_id')) !!}
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label">{{__("Youtube video")}}</label>
                        <input type="text" name="video_url" class="form-control onChangeAutoSave"
                               value="{{old('video',$row->video_url)}}"
                               placeholder="{{__("Video URL")}}">
                    </div>
                </div>

                {{--                @include('Core::frontend/seo-meta/seo-meta')--}}

                <div class="mb-4 d-none d-md-block">
                    <button class="theme-btn btn-style-seven" type="submit"><i class="fa fa-save"
                                                                             style="padding-right: 5px"></i> {{__('Save Changes')}}
                    </button>
                </div>

            </div>

            <div class="col-xl-3">
                <div class="ls-widget">
                    <div class="widget-title"><h4>{{ __("Publish") }}</h4></div>
                    <div class="widget-content">
                        <div class="form-group">
                            @if(is_default_lang())
                                <div>
                                    <label><input @if($row->status=='publish') checked
                                                  @endif @if(!is_admin() && setting_item('announcement_need_approve')) disabled
                                                  @endif type="radio" name="status" value="publish"> {{__("Publish")}}
                                    </label></div>
                                <div>
                                    <label><input @if($row->status=='draft') checked
                                                  @endif @if(!is_admin() && setting_item('announcement_need_approve')) disabled
                                                  @endif type="radio" name="status" value="draft"> {{__("Draft")}}
                                    </label></div>
                            @endif
                        </div>
                        <div class="form-group">
                            <div class="text-right">
                                <button class="theme-btn btn-style-seven" type="submit"><i
                                        class="fa fa-save"></i> {{__('Save Changes')}}</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{--                <div class="ls-widget">--}}
                {{--                    <div class="widget-title"><h4>{{ __("Feature Image") }}</h4></div>--}}
                {{--                    <div class="widget-content">--}}
                {{--                        <div class="form-group">--}}
                {{--                            {!! \Modules\Media\Helpers\FileHelper::fieldUpload('image_id',$row->image_id) !!}--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}

                <div class="ls-widget" id="trainingLocations">
                    <div class="tabs-box">
                        <div class="widget-title"><h4>{{ __("Training Location Status") }}</h4></div>
                        <div class="widget-content" id="locationStatus">
                            <?php $announcement_status = json_decode($row->announcement_status, true); ?>
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="announcement_status[online]" id="announcement_status_online" value="1" class="onChangeAutoSave"
                                           @if(!empty($announcement_status) && key_exists('online', $announcement_status)) checked @endif>
                                    {{ __("Online") }}
                                </label>
                            </div>
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="announcement_status[in_person]" id="announcement_status_in_person" value="1" class="onChangeAutoSave"
                                           @if(!empty($announcement_status) && key_exists('in_person',  $announcement_status)) checked @endif>
                                    {{ __("In Person") }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('footer')
    {!! App\Helpers\MapEngine::scripts() !!}
    <script type="text/javascript" src="{{ asset('libs/daterange/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('libs/daterange/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('libs/select2/js/select2.min.js') }}"></script>
    <script>
        const categoryBlockСheckboxes = document.querySelectorAll('#categoryBlock input[type="checkbox"]');
        const locationStatusСheckboxes = document.querySelectorAll('#locationStatus input[type="checkbox"]');
        const locationInput = document.querySelector('.smart-search-location');

        $('#categoryBlock input[type="checkbox"]').prop('required', true);
        // $('#locationStatus input[type="checkbox"]').prop('required', true);

        $(document).ready(function () {
           const isLocationChecked = Array.from(locationStatusСheckboxes).find(checkbox => checkbox.checked);
           const isCategoriesChecked = Array.from(categoryBlockСheckboxes).find(checkbox => checkbox.checked);

           if (isLocationChecked) {
               $('#locationStatus input[type="checkbox"]').prop('required', false);
               if (isLocationChecked.name === 'announcement_status[online]') {
                  $('#mapContainer').hide()
                }
           }
           if (isCategoriesChecked) {
                $('#categoryBlock input[type="checkbox"]').prop('required', false);
               if (isCategoriesChecked.value !== '3') {
                   $('#trainingLocations').hide()
               }
           }
        })

        categoryBlockСheckboxes.forEach(function(categoryBlockcheckbox) {
            categoryBlockcheckbox.addEventListener('change', function() {
                if (this.checked) {
                    $('#categoryBlock input[type="checkbox"]').prop('required', false);
                    categoryBlockСheckboxes.forEach(function(otherCheckbox) {
                        if (otherCheckbox !== categoryBlockcheckbox) {
                            otherCheckbox.checked = false;
                        }
                    });
                } else {
                    $('#categoryBlock input[type="checkbox"]').prop('required', true);
                }
                if (this.value !== '3' && this.checked) {
                    $('#trainingLocations').hide()
                    $('#announcement_status_online').removeAttr('checked');
                    $('#announcement_status_in_person').removeAttr('checked')()
                } else {
                    $('#trainingLocations').show()
                }
            });
        });


        locationStatusСheckboxes.forEach(function(locationStatusСheckbox) {
            locationStatusСheckbox.addEventListener('change', function() {
                if (this.checked) {
                $('#locationStatus input[type="checkbox"]').prop('required', false);
                    locationStatusСheckboxes.forEach(function(otherCheckbox) {
                        if (otherCheckbox !== locationStatusСheckbox) {
                            otherCheckbox.checked = false;
                        }
                    });
                }
                if (this.name === 'announcement_status[online]' && this.checked) {
                    $('#mapContainer').hide()
                } else {
                    $('#mapContainer').show()
                }
            });
        });


        $('.has-datepicker').daterangepicker({
            singleDatePicker: true,
            showCalendar: false,
            autoUpdateInput: false,
            sameDate: true,
            autoApply: true,
            disabledPast: true,
            enableLoading: true,
            showEventTooltip: true,
            classNotAvailable: ['disabled', 'off'],
            disableHightLight: true,
            locale: {
                format: superio.date_format,
                cancelLabel: 'Clear'
            }
        }).on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format(superio.date_format));
            onChangeAutoSave($(this))
        }).on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
            picker.setStartDate({})
            picker.setEndDate({})
            onChangeAutoSave($(this))
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
        });

        function clearDatePicker(elem) {
            elem.val('');
            // picker.setStartDate({})
            // picker.setEndDate({})
            onChangeAutoSave(elem)
        }

        let hasChanges = false,
            isSubmitting = false,
            newPost = {{$row->id ? 'false' : 'true'}};

        $(function () {
            $('.onChangeAutoSave').on('change', function () {
                hasChanges = {{$row->id ? 'false' : 'true'}};
                onChangeAutoSave($(this))
            });

            // window.addEventListener('beforeunload', function (e) {
            //     if (hasChanges && newPost && !isSubmitting) {
            //         e.preventDefault();
            //         e.returnValue = '';
            //     }
            // });
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
            let data = {},
                name = $(element.activeEditor.getElement()).attr('name');

            data[name] = element.activeEditor.getContent()

            saveMarketplaceAttribute(data);
        }

        function onChangeAutoSave(element) {
            let data = {},
                name = element.attr('name');

            // if (element.attr('type') === 'checkbox') {
            //     data[name] = element.is(':checked') === true ? 1 : 0;
            // } else {
                data[name] = element.val()
            // }

            saveMarketplaceAttribute(data);
        }

        function onChangeMapAutoSave() {
            setTimeout(function () {
                let data = {
                    map_lat: $('[name="map_lat"]').val(),
                    map_lng: $('[name="map_lng"]').val(),
                    map_zoom: $('[name="map_zoom"]').val(),
                    location_id: $('[name="location_id"]').val(),
                };
                saveMarketplaceAttribute(data);
            }, 1000)
        }

        function saveMarketplaceAttribute(data) {
{{--            @if($row->id)--}}
            $.ajax({
                url: '{{route('marketplace.api.update', ['marketplace'=>$row->id])}}',
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
