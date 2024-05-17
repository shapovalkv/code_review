@extends('layouts.user')

@section('content')
    @php
        $languages = \Modules\Language\Models\Language::getActive();
    @endphp
    <div class="bravo_user_profile">
        <div class="title-actions" style="display: none">
            {{--                <a href="{{route('user.upgrade_company')}}" class="btn btn-warning text-light">{{__("Become a Company")}}</a>--}}
            @if($url = $row->getDetailUrl())
                <a href="{{$url}}" target="_blank" class="btn btn-style-ten text-light mb-3"><i
                        class="la la-eye"></i> {{__("View profile")}}</a>
            @endif
        </div>
        <form id='company_form' method="post" action="{{ route('user.company.update' ) }}" class="default-form">
            @csrf
            <div class="d-flex justify-content-between mb20">
                <div class="upper-title-box">
                    <h3>{{ __('Edit: ').$row->name }}</h3>
                    <div class="text">
                        @if($row->slug)
                            <p class="item-url-demo">{{__("Permalink")}}
                                : {{ url(config('companies.companies_route_prefix') ) }}/<a href="#" class="open-edit-input"
                                                                                            data-name="slug">{{$row->slug}}</a>
                            </p>
                        @endif
                    </div>
                </div>
                <div class="title-actions">
                    @if($url = $row->getDetailUrl())
                        <a href="{{$url}}" target="_blank" class="btn btn-style-ten text-light ml-3"><i
                                class="la la-eye"></i> {{__("View profile")}}</a>
                    @endif
                </div>
            </div>

            @include('admin.message')

            @if($row->id)
                @include('Language::admin.navigation')
            @endif

            <div class="row">
                <div class="col-xl-9">
                    <!-- Ls widget -->
                    <div class="ls-widget">
                        <div class="tabs-box">
                            <div class="widget-title"><h4>{{ __("Company Info") }}</h4></div>
                            <div class="widget-content">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__("Company name")}} <span class="text-danger">*</span></label>
                                            <input type="text" required value="{{old('name',$translation->name)}}"
                                                   name="name" placeholder="{{__("Company name")}}"
                                                   class="form-control onChangeAutoSave">
                                        </div>
                                    </div>
                                    @if(is_default_lang())
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{ __('E-mail')}} <span class="text-danger">*</span></label>
                                                <input type="email" required value="{{old('email',$row->email)}}"
                                                       placeholder="{{ __('Email')}}" name="email"
                                                       class="form-control onChangeAutoSave">
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('Phone Number')}} <span class="text-danger">*</span></label>
                                            <input type="text" value="{{old('phone',$row->phone)}}"
                                                   placeholder="{{ __('Phone')}}" name="phone"
                                                   class="form-control onChangeAutoSave"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__("Website")}}</label>
                                            <input type="text" value="{{old('website',$row->website)}}" name="website"
                                                   placeholder="{{__("Website")}}"
                                                   class="form-control onChangeAutoSave">
                                        </div>
                                    </div>
                                    {{--                                    @if(is_default_lang())--}}
                                    {{--                                        <div class="col-md-6">--}}
                                    {{--                                            <div class="form-group">--}}
                                    {{--                                                <label>{{ __('Est. Since')}}</label>--}}
                                    {{--                                                <input type="text" value="{{ old('founded_in',$row->founded_in ? date(get_date_format(),strtotime($row->founded_in)) :'') }}" placeholder="{{ __('Est. Since')}}" name="founded_in" class="form-control has-datepicker input-group date">--}}
                                    {{--                                            </div>--}}
                                    {{--                                        </div>--}}
                                    {{--                                    @endif--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('Address')}} <span class="text-danger">*</span></label>
                                            <input type="text" required value="{{old('address',$row->address)}}"
                                                   placeholder="{{ __('Address')}}" name="address"
                                                   class="form-control onChangeAutoSave">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__("City")}} <span class="text-danger">*</span></label>
                                            <input type="text" required value="{{old('city',$row->city)}}" name="city"
                                                   placeholder="{{__("City")}}" class="form-control onChangeAutoSave">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__("State")}} <span class="text-danger">*</span></label>
                                            <input type="text" required value="{{old('state',$row->state)}}"
                                                   name="state" placeholder="{{__("State")}}"
                                                   class="form-control onChangeAutoSave">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="">{{__("Country")}}</label>
                                            <select name="country" class="form-control onChangeAutoSave"
                                                    id="country-sms-testing">
                                                <option value="">{{__('-- Select --')}}</option>
                                                @foreach(get_country_lists() as $id=>$name)
                                                    <option @if($row->country==$id) selected
                                                            @endif value="{{$id}}">{{$name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__("Zip Code")}} <span class="text-danger">*</span></label>
                                            <input type="text" required value="{{old('zip_code',$row->zip_code)}}"
                                                   name="zip_code" placeholder="{{__("Zip Code")}}"
                                                   class="form-control onChangeAutoSave">
                                        </div>
                                    </div>
                                    {{--                                    <div class="col-md-6">--}}
                                    {{--                                        <div class="form-group">--}}
                                    {{--                                            <label class="">{{__("W-2 California")}}</label>--}}
                                    {{--                                            <select name="w2_california" class="form-control">--}}
                                    {{--                                                <option value="{{null}}" @if(is_null($row->w2_california)) selected @endif>{{__('Not Selected')}}</option>--}}
                                    {{--                                                <option value="{{1}}" @if($row->w2_california==1) selected @endif>{{__('Yes')}}</option>--}}
                                    {{--                                                <option value="{{0}}" @if($row->w2_california==0 && !is_null($row->w2_california)) selected @endif>{{__('No')}}</option>--}}
                                    {{--                                            </select>--}}
                                    {{--                                        </div>--}}
                                    {{--                                    </div>--}}
                                    @if(is_default_lang())
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input @if($row->allow_search) checked @endif type="checkbox"
                                                       name="allow_search" value="1"
                                                       class="form-control onChangeAutoSave">
                                                <label>{{__("Allow In Search & Listing")}}</label>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('About Company')}}</label>
                                            <div class="">
                                                <textarea name="about" class="d-none has-ckeditor onChangeAutoSave"
                                                          cols="30"
                                                          rows="10">{{old('about',$translation->about)}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ls-widget">
                        <div class="tabs-box" id="map_tab_box">
                            <div class="widget-title">
                                <h4>{{ __("Company Location(s)") }}</h4>
                                <button id='add_location' class="theme-btn btn-style-four" type="button"
                                        style='padding: 10px'><i
                                        class="fa fa-save"
                                        style="padding-right: 5px"></i> {{__('Add Another Location')}}
                                </button>
                            </div>
                            <div data-offices="{{ $offices }}" id='offices_data'></div>
                            <div class="widget-content" id='widget-content_0'>
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
                                                'id'       => $location->id,
                                                'title'    => $prefix . ' ' . $translate->name,
                                                'map_lat'  => $prefix . ' ' . $location->map_lat,
                                                'map_lng'  => $prefix . ' ' . $location->map_lng,
                                                'map_zoom' => $prefix . ' ' . $location->map_zoom,
                                            ];
                                            $traverse($location->children, $prefix . '-');
                                        }
                                    };
                                    $traverse($company_location);
                                    ?>
                                    <div class="form-group col-md-12 col-sm-12 p-0 location smart-search">
                                        <span class="icon flaticon-map-locator"></span>
                                        <input type="text" id='smart-search-location_0'
                                               class="smart-search-location parent_text form-control onChangeMapAutoSave" onchange="onChangeMapAutoSave($(this))"
                                               placeholder="{{__("Type City Name and Choose Location")}}"
                                               value="{{ $row->location->name ?? $location_name }}"
                                               data-onLoad="{{__("Loading...")}}"
                                               data-default="" autocomplete="off" required>
                                        <input
                                            type="hidden"
                                            class="child_id"
                                            name="location_id"
                                            id="location_id_0"
                                            value="{{ $row->location->id ?? $location_id  }}"
                                            data-map_lng="{{ $row->location->map_lng ?? ''}}"
                                            data-map_zoom="{{ $row->location->map_zoom ?? ''}}"
                                            data-map_lat="{{ $row->location->map_lat ?? '10'}}"
                                        >
                                        <input
                                            type="hidden"
                                            id="id_0"
                                            value=""
                                        >
                                    </div>
                                    <div>
                                        <div class='d-flex justify-content-between'>
                                            <div>
                                                <input type="checkbox" id="is_main_0" data-checkbox='true'>
                                                <label for="is_main">{{__("Select this map as the main one")}}</label>
                                            </div>

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
                                                <input type="text" name="map_lat" id="map_lat_0" class="form-control"
                                                       value="{{old('map_lat', $row->map_lat ?? '34.0522' )}}" readonly
                                                       onkeydown="return event.key !== 'Enter';">
                                            </div>
                                            <div class="form-group">
                                                <label>{{__("Map Longitude")}}:</label>
                                                <input type="text" name="map_lng" id="map_lng_0" class="form-control"
                                                       value="{{old('map_lng', $row->map_lng  ?? '-118.244' )}}"
                                                       readonly onkeydown="return event.key !== 'Enter';">
                                            </div>
                                            <div class="form-group">
                                                <label>{{__("Map Zoom")}}:</label>
                                                <input type="text" name="map_zoom" id="map_zoom_0" class="form-control"
                                                       value="{{old('map_zoom', $row->map_zoom ?? "12")}}" readonly
                                                       onkeydown="return event.key !== 'Enter';">
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
                        </div>
                    </div>

                    {{--                    @include('Core::frontend/seo-meta/seo-meta')--}}

                    <div class="mb-4 d-none d-md-block">
                        <button class="theme-btn btn-style-seven" id='submit_button'><i class="fa fa-save"
                                                                                      style="padding-right: 5px"></i> {{__('Save Changes')}}
                        </button>
                    </div>
                    <div class="mb-4 d-none d-md-block">
                        @if(!empty(setting_item('user_enable_permanently_delete')) and !is_admin())
                            <div class="row">
                                <div class="col-12">
                                    <div class="ls-widget">
                                        <div class="widget-title">
                                            <h4 class="text-danger">
                                                {{__("Delete account")}}
                                            </h4>
                                        </div>
                                        <div class="widget-content">
                                            <div class="mb-4 mt-2">
                                                {!! clean(setting_item_with_lang('user_permanently_delete_content','',__('Your account will be permanently deleted. Once you delete your account, there is no going back. Please be certain.'))) !!}
                                            </div>
                                            <a rel="modal:open" class="btn btn-danger"
                                               href="#permanentlyDeleteAccount">{{__('Delete your account')}}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal bravo-form" id="permanentlyDeleteAccount">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">{{__('Confirm permanently delete account')}}</h5>
                                        </div>
                                        <div class="modal-body ">
                                            <div class="my-3">
                                                {!! clean(setting_item_with_lang('user_permanently_delete_content_confirm')) !!}
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <a href="#close-modal" rel="modal:close"
                                               class="btn btn-secondary">{{__('Close')}}</a>
                                            <a href="{{route('user.permanently.delete')}}"
                                               class="btn btn-danger">{{__('Confirm')}}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="ls-widget">
                        <div class="widget-title"><h4>{{ __("Publish") }}</h4></div>
                        <div class="widget-content">
                            <div class="form-group">
                                @if(is_default_lang())
                                    <div>
                                        <label><input @if($row->status=='publish') checked @endif type="radio"
                                                      name="status" value="publish"> {{__("Publish")}}
                                        </label></div>
                                    <div>
                                        <label><input @if($row->status=='draft' or !$row->status) checked
                                                      @endif type="radio" name="status" value="draft"> {{__("Draft")}}
                                        </label></div>
                                @endif
                            </div>
                            <div class="form-group">
                                <div class="text-right">
                                    <button class="theme-btn btn-style-seven" id='side_submit_button'><i
                                            class="fa fa-save"></i> {{__('Save Changes')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(is_default_lang())
                        @foreach ($attributes as $attribute)
                            <div class="ls-widget">
                                <div class="widget-title"><h4>{{__('Attribute: :name',['name'=>$attribute->name])}}</h4>
                                </div>
                                <div class="widget-content">
                                    <div class="terms-scrollable mb-4">
                                        @foreach($attribute->terms as $term)
                                            <label class="term-item">
                                                <input
                                                    @if(!empty($selected_terms) and $selected_terms->contains($term->id)) checked
                                                    @endif type="checkbox" name="terms[]" value="{{$term->id}}">
                                                <span class="term-name">{{$term->name}}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    @if(is_default_lang())
                        <div class="ls-widget">
                            <div class="widget-title"><h4>{{ __('Logo')}} </h4></div>
                            <div class="widget-content pb-4 xs-align-center">
                                {!! \Modules\Media\Helpers\FileHelper::fieldUpload('avatar_id',$row->avatar_id) !!}
                                <p><i>({{__('Recommended size 330px x 300px')}})</i></p>
                            </div>
                        </div>
                    @endif

                    @if(is_default_lang())
                        <div class="ls-widget">
                            <div class="widget-title"><h4>{{ __("Social Media") }}</h4></div>
                            <div class="widget-content">
                                    <?php $socialMediaData = $row->social_media; ?>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="social-skype"><i
                                                class="la la-skype"></i></span>
                                    </div>
                                    <input type="text" class="form-control onChangeAutoSave" autocomplete="off"
                                           name="social_media[skype]" value="{{ $socialMediaData['skype'] ?? '' }}"
                                           placeholder="{{__('Skype')}}" aria-label="{{__('Skype')}}"
                                           aria-describedby="social-skype">
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="social-facebook"><i
                                                class="la la-facebook"></i></span>
                                    </div>
                                    <input type="text" class="form-control onChangeAutoSave" autocomplete="off"
                                           name="social_media[facebook]"
                                           value="{{ $socialMediaData['facebook'] ?? '' }}"
                                           placeholder="{{__('Facebook')}}" aria-label="{{__('Facebook')}}"
                                           aria-describedby="social-facebook">
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="social-twitter"><i class="fa-brands fa-x-twitter"></i></span>
                                    </div>
                                    <input type="text" class="form-control onChangeAutoSave" autocomplete="off"
                                           name="social_media[twitter]" value="{{$socialMediaData['twitter'] ?? ''}}"
                                           placeholder="{{__('Twitter')}}" aria-label="{{__('Twitter')}}"
                                           aria-describedby="social-twitter">
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="social-instagram"><i
                                                class="la la-instagram"></i></span>
                                    </div>
                                    <input type="text" class="form-control onChangeAutoSave" autocomplete="off"
                                           name="social_media[instagram]"
                                           value="{{$socialMediaData['instagram'] ?? ''}}"
                                           placeholder="{{__('Instagram')}}" aria-label="{{__('Instagram')}}"
                                           aria-describedby="social-instagram">
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="social-linkedin"><i
                                                class="la la-linkedin"></i></span>
                                    </div>
                                    <input type="text" class="form-control onChangeAutoSave" autocomplete="off"
                                           name="social_media[linkedin]" value="{{$socialMediaData['linkedin'] ?? ''}}"
                                           placeholder="{{__('Linkedin')}}" aria-label="{{__('Linkedin')}}"
                                           aria-describedby="social-linkedin">
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="social-google"><i
                                                class="la la-google"></i></span>
                                    </div>
                                    <input type="text" class="form-control onChangeAutoSave" autocomplete="off"
                                           name="social_media[google]" value="{{@$socialMediaData['google'] ?? ''}}"
                                           placeholder="{{__('Google')}}" aria-label="{{__('Google')}}"
                                           aria-describedby="social-google">
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </form>
    </div>
@endsection

@section('footer')
    {!! App\Helpers\MapEngine::scripts() !!}
    <script type="text/javascript" src="{{ asset('libs/daterange/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('libs/daterange/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('libs/select2/js/select2.min.js') }}"></script>
    <script>
        console.log(superio.date_format)
        $('.has-datepicker').daterangepicker({
            singleDatePicker: true,
            showCalendar: false,
            autoUpdateInput: true,
            sameDate: true,
            autoApply: true,
            disabledPast: true,
            enableLoading: true,
            showEventTooltip: true,
            classNotAvailable: ['disabled', 'off'],
            disableHightLight: true,
            locale: {
                format: superio.date_format
            }
        }).on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format(superio.date_format));
        });
    </script>
    {{--  TODO Commented Google map autofocus --}}
    <script>
        const officesData = JSON.parse($('#offices_data').attr('data-offices'))
        const url = "{{ route('user.company.update' ) }}"
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
                        engineMap.searchBox($('#customPlaceAddress'), function (dataLatLng) {
                            engineMap.clearMarkers();
                            engineMap.addMarker(dataLatLng, {
                                icon_options: {}
                            });
                            $("#map_lat_" + index).attr("value", dataLatLng[0]);
                            $("#map_lng_" + index).attr("value", dataLatLng[1]);
                        });
                        engineMap.searchBox($('#bravo_searchbox'), function (dataLatLng) {
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


        $('#submit_button, #side_submit_button').on('click', function (e) {
            $('#company_form').find('input:invalid').on('focus', (event) => {
                console.log(event.target);
                $('html, body').animate(
                    { scrollTop: $(event.target).parent().offset().top },
                    300
                );
            });
            if (!$('#company_form')[0].checkValidity()) {
                return
            }
            const formData = $('#company_form').serializeArray()
            const formattedData = transformData(formData)
            $('#offices_data_id').attr('value', JSON.stringify(formattedData))
            $('#company_form').trigger('submit')
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
            $.ajax({
                url: '{{route('companies.api.update')}}/office/' + $('#id_' + mapId).val(),
                type: 'delete',
                dataType: 'json',
                success: function (response) {
                    return response;
                }
            });
            $('#widget-content_' + mapId).remove()
        }

        $(document).ready(function () {
            if (officesData.length === 0) {
                $("#delete_button_0").hide()
                return addNewMap(0, "-118.244", "34.0522", "12")
            }
            officesData.forEach((office, index) => {
                index > 0 && $('#widget-content_0').clone().attr("id", "widget-content_" + index).appendTo('#map_tab_box')

                const newMapContainer = $("#widget-content_" + index)
                newMapContainer.find('#map_lat_0').attr("id", "map_lat_" + index).attr('value', office.map_lat)
                newMapContainer.find('#map_lng_0').attr("id", "map_lng_" + index).attr('value', office.map_lng)
                newMapContainer.find('#location_id_0').attr("id", "location_id_" + index).attr('value', office.location_id)
                newMapContainer.find('#id_0').attr("id", "id_" + index).attr('value', office.id)
                newMapContainer.find('#smart-search-location_0').attr("id", "smart-search-location_" + index).attr('value', office.name)
                newMapContainer.find('#is_main_0').attr("id", "is_main_" + index).prop('checked', office.is_main)
                newMapContainer.find('#map_zoom_0').attr("id", "map_zoom_" + index).attr('value', office.map_zoom)
                window.applySmartSearchLocation(newMapContainer.find('#smart-search-location_' + index));
                newMapContainer.find('#map_content_0').attr("id", "map_content_" + index)
                newMapContainer.find('#delete_button_0').attr("id", "delete_button_" + index)
                $("#is_main_" + index).on('change', onCheckBoxClick)
                $("#delete_button_" + index).on('click', deleteCompany)
                $("#delete_button_" + index).show()
                index === 0 ?
                    $("#delete_button_" + index).hide()
                    : $("#delete_button_" + index).show()
                return addNewMap(index, office.map_lng, office.map_lat, office.map_zoom)
            })
        });

        const addCompany = () => {
            mapCounter++
            $('#widget-content_0').clone().attr("id", "widget-content_" + mapCounter).appendTo('#map_tab_box')
            const newMapContainer = $("#widget-content_" + mapCounter)
            newMapContainer.find('#map_lat_0').attr("id", "map_lat_" + mapCounter)
            newMapContainer.find('#map_lng_0').attr("id", "map_lng_" + mapCounter)
            newMapContainer.find('#location_id_0').attr("id", "location_id_" + mapCounter)
            newMapContainer.find('#id_0').attr("id", "id_" + mapCounter).attr('value', '')
            newMapContainer.find('#map_zoom_0').attr("id", "map_zoom_" + mapCounter)
            newMapContainer.find('#map_content_0').attr("id", "map_content_" + mapCounter)
            newMapContainer.find('#delete_button_0').attr("id", "delete_button_" + mapCounter)
            newMapContainer.find('#is_main_0').attr("id", "is_main_" + mapCounter).prop('checked', false)
            newMapContainer.find('#smart-search-location_0').attr("id", "smart-search-location_" + mapCounter).val('')
            window.applySmartSearchLocation(newMapContainer.find('#smart-search-location_' + mapCounter));
            $("#is_main_" + mapCounter).on('change', onCheckBoxClick)
            $("#delete_button_" + mapCounter).on('click', deleteCompany)
            mapCounter === 0 ?
                $("#delete_button_" + mapCounter).hide()
                : $("#delete_button_" + mapCounter).show()
            return addNewMap(mapCounter, "-118.244", "34.0522", "12")
        }
        //            addNewMap(mapCounter)

        document.addEventListener("DOMContentLoaded", function () {
            const phoneInputs = document.querySelectorAll('input[name="phone"]');
            Inputmask({
                mask: '(###) ###-####',
                repeat: 1,
                greedy: false
            }).mask(phoneInputs);
        });

        $("#is_main_0").on('change', onCheckBoxClick)
        $('#add_location').on('click', addCompany);
        $('.open-edit-input').on('click', function (e) {
            e.preventDefault();
            $(this).replaceWith('<input type="text" name="' + $(this).data('name') + '" value="' + $(this).html() + '">');
        });

        $(function () {
            $('.onChangeAutoSave').on('change', function () {
                onChangeAutoSave($(this))
            });
        });

        function onChangeTinyAutoSave(element) {
            let data = {},
                name = $(element.activeEditor.getElement()).attr('name');

            data[name] = element.activeEditor.getContent()

            saveCompanyAttribute(data);
        }

        function onChangeAutoSave(element) {
            let data = {},
                name = element.attr('name');

            data[name] = element.val()

            saveCompanyAttribute(data);
        }

        function onChangeMapAutoSave(element) {
            let id = element.attr('id').replace('smart-search-location_', '');
            setTimeout(function() {
                let data = {
                    location: {
                        map_lat: $('#map_lat_' + id).val(),
                        map_lng: $('#map_lng_' + id).val(),
                        map_zoom: $('#map_zoom_' + id).val(),
                        is_main: $('#is_main_' + id).is(':checked'),
                        location_id: $('#location_id_' + id).val(),
                        id: $('#id_' + id).val(),
                    }
                };
                let response = saveCompanyAttribute(data);
                $('#id_' + id).attr('value', response.office_id);
            }, 1000)
        }

        function saveCompanyAttribute(data) {
            let result;
            $.ajax({
                url: '{{route('companies.api.update')}}',
                type: 'post',
                data: data,
                dataType: 'json',
                async: false,
                cache: false,
                timeout: 30000,
                success: function (response) {
                    result = response;
                    return response;
                }
            });

            return result;
        }
    </script>
@endsection
