@php
    $candidate = $row->candidate;
@endphp
{{--@if(is_admin())--}}
    <div class="form-group">
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
                    $traverse($candidate_location);
                    ?>
                <div class="form-group col-md-12 col-sm-12 p-0 location smart-search">
                    <span class="icon flaticon-map-locator"></span>
                    <input id="admin_location_select" type="text"
                           class="smart-search-location parent_text form-control" onchange="if(typeof onChangeMapAutoSave === 'function') {onChangeMapAutoSave($(this))}"
                           placeholder="{{__("All Locations")}}"
                           value="{{ $row->candidate->location->name ?? $location_name }}"
                           data-onLoad="{{__("Loading...")}}"
                           data-default="" autocomplete="off" required>
                    <input type="hidden" class="child_id" name="location_id" value="{{$row->location_id ?? Request::query('location_id')}}">
                    <input
                        type="hidden"
                        class="child_id"
                        name="location_id"
                        value="{{ $row->candidate->location->id ?? $location_id  }}"
                        data-map_lng="{{ $row->candidate->location->map_lng ?? ''}}"
                        data-map_zoom="{{ $row->candidate->location->map_zoom ?? ''}}"
                        data-map_lat="{{ $row->candidate->location->map_lat ?? ''}}"
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
                                   value="{{old('map_lat', $row->candidate->map_lat ?? '34.0522')}}" readonly
                                   onkeydown="return event.key !== 'Enter';">
                        </div>
                        <div class="form-group">
                            <label>{{__("Map Longitude")}}:</label>
                            <input type="text" name="map_lng" class="form-control"
                                   value="{{old('map_lng', $row->candidate->map_lng ?? '-118.244')}}" readonly
                                   onkeydown="return event.key !== 'Enter';">
                        </div>
                        <div class="form-group">
                            <label>{{__("Map Zoom")}}:</label>
                            <input type="text" name="map_zoom" class="form-control"
                                   value="{{old('map_zoom', $row->candidate->map_zoom ?? "12")}}" readonly
                                   onkeydown="return event.key !== 'Enter';">
                        </div>
                    </div>

                </div>
            </div>
    </div>
{{--@else--}}
{{--    <div class="form-group">--}}
{{--        <label class="control-label">{{__("Location")}} <span class="required">*</span></label>--}}
{{--            <?php--}}
{{--            $location_name = "";--}}
{{--            $list_json = [];--}}
{{--            $location_id = request()->get('location');--}}
{{--            $traverse = function ($locations, $prefix = '') use (&$traverse, &$list_json, &$location_name, $location_id) {--}}
{{--                foreach ($locations as $location) {--}}
{{--                    $translate = $location->translateOrOrigin(app()->getLocale());--}}
{{--                    if ($location_id == $location->id) {--}}
{{--                        $location_name = $translate->name;--}}
{{--                    }--}}
{{--                    $list_json[] = [--}}
{{--                        'id' => $location->id,--}}
{{--                        'title' => $prefix . ' ' . $translate->name,--}}
{{--                        'map_lat' => $prefix . ' ' . $location->map_lat,--}}
{{--                        'map_lng' => $prefix . ' ' . $location->map_lng,--}}
{{--                        'map_zoom' => $prefix . ' ' . $location->map_zoom,--}}
{{--                    ];--}}
{{--                    $traverse($location->children, $prefix . '-');--}}
{{--                }--}}
{{--            };--}}
{{--            $traverse($candidate_location);--}}
{{--            ?>--}}
{{--        <div class="form-group col-md-12 col-sm-12 p-0 location smart-search">--}}
{{--            <span class="icon flaticon-map-locator"></span>--}}
{{--            <input type="text" class="smart-search-location parent_text form-control"--}}
{{--                   placeholder="{{__("All Locations")}}"--}}
{{--                   value="{{ $candidate->location->name ?? $location_name }}" data-onLoad="{{__("Loading...")}}"--}}
{{--                   data-default="" required>--}}
{{--            <input--}}
{{--                type="hidden"--}}
{{--                class="child_id"--}}
{{--                name="location_id"--}}
{{--                value="{{ $row->location->id ?? $location_id  }}"--}}
{{--                data-map_lng="{{ $row->location->map_lng ?? ''}}"--}}
{{--                data-map_zoom="{{ $row->location->map_zoom ?? ''}}"--}}
{{--                data-map_lat="{{ $row->location->map_lat ?? ''}}"--}}
{{--            >--}}
{{--        </div>--}}
{{--    </div>--}}
{{--@endif--}}
{{--    <div class="row">--}}
{{--        <div class="col-md-6">--}}
{{--            <div class="form-group">--}}
{{--                <label class="">{{__("Country")}}</label>--}}
{{--                <select name="country" class="form-control" id="country-sms-testing">--}}
{{--                    <option value="">{{__('-- Select --')}}</option>--}}
{{--                    @foreach(get_country_lists() as $id=>$name)--}}
{{--                        <option @if(@$candidate->country==$id) selected @endif value="{{$id}}">{{$name}}</option>--}}
{{--                    @endforeach--}}
{{--                </select>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="col-md-6">--}}
{{--            <div class="form-group">--}}
{{--                <label>{{__("City")}}</label>--}}
{{--                <input type="text" value="{{old('city',@$candidate->city)}}" name="city" placeholder="{{__("City")}}" class="form-control">--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="col-md-12">--}}
{{--            <div class="form-group">--}}
{{--                <label>{{ __('Address Line')}}</label>--}}
{{--                <input type="text" value="{{old('address',@$candidate->address)}}" placeholder="{{ __('Address')}}" name="address" class="form-control">--}}
{{--            </div>--}}
{{--        </div>--}}
{{--</div>--}}
{{--    <div class="form-group">--}}
{{--        <label class="control-label">{{__("Location")}}</label>--}}
{{--        @if(!empty($is_smart_search))--}}
{{--            <div class="form-group-smart-search">--}}
{{--                <div class="form-content">--}}
{{--                    <?php--}}
{{--                    $location_name = "";--}}
{{--                    $list_json = [];--}}
{{--                    $traverse = function ($locations, $prefix = '') use (&$traverse, &$list_json , &$location_name, $candidate) {--}}
{{--                        foreach ($locations as $location) {--}}
{{--                            $translate = $location->translateOrOrigin(app()->getLocale());--}}
{{--                            if (@$candidate->location_id == $location->id){--}}
{{--                                $location_name = $translate->name;--}}
{{--                            }--}}
{{--                            $list_json[] = [--}}
{{--                                'id' => $location->id,--}}
{{--                                'title' => $prefix . ' ' . $translate->name,--}}
{{--                            ];--}}
{{--                            $traverse($location->children, $prefix . '-');--}}
{{--                        }--}}
{{--                    };--}}
{{--                    $traverse($locations);--}}
{{--                    ?>--}}
{{--                    <div class="smart-search">--}}
{{--                        <input type="text" class="smart-search-location parent_text form-control" placeholder="{{__("-- Please Select --")}}" value="{{ $location_name }}" data-onLoad="{{__("Loading...")}}"--}}
{{--                               data-default="{{ json_encode($list_json) }}">--}}
{{--                        <input type="hidden" class="child_id" name="location_id" value="{{@$candidate->location_id ?? Request::query('location_id')}}">--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        @else--}}
{{--            <div class="">--}}
{{--                <select name="location_id" class="form-control">--}}
{{--                    <option value="">{{__("-- Please Select --")}}</option>--}}
{{--                    <?php--}}
{{--                    $traverse = function ($locations, $prefix = '') use (&$traverse, $candidate) {--}}
{{--                        foreach ($locations as $location) {--}}
{{--                            $selected = '';--}}
{{--                            if (@$candidate->location_id == $location->id)--}}
{{--                                $selected = 'selected';--}}
{{--                            printf("<option data-map_lng=".$location->map_lng." data-map_zoom=".$location->map_zoom." data-map_lat=".$location->map_lat." value='%s' %s>%s</option>", $location->id, $selected, $prefix . ' ' . $location->name);--}}
{{--                            $traverse($location->children, $prefix . '-');--}}
{{--                        }--}}
{{--                    };--}}
{{--                    $traverse($locations);--}}
{{--                    ?>--}}
{{--                </select>--}}
{{--            </div>--}}
{{--        @endif--}}
{{--    </div>--}}
{{--    <div class="form-group">--}}
{{--        <label class="control-label">{{__("The geographic coordinate")}}</label>--}}
{{--        <div class="control-map-group">--}}
{{--            <div id="map_content" data-is_map_content='true'></div>--}}
{{--            <input type="text" placeholder="{{__("Search by name...")}}" class="bravo_searchbox form-control" autocomplete="off" onkeydown="return event.key !== 'Enter';">--}}
{{--            <div class="g-control">--}}
{{--                <div class="form-group">--}}
{{--                    <label>{{__("Map Latitude")}}:</label>--}}
{{--                    <input type="text" name="map_lat" class="form-control" value="{{@$candidate->map_lat ?? "51.505"}}" readonly onkeydown="return event.key !== 'Enter';">--}}
{{--                </div>--}}
{{--                <div class="form-group">--}}
{{--                    <label>{{__("Map Longitude")}}:</label>--}}
{{--                    <input type="text" name="map_lng" class="form-control" value="{{@$candidate->map_lng ?? "-0.09"}}" readonly onkeydown="return event.key !== 'Enter';">--}}
{{--                </div>--}}
{{--                <div class="form-group">--}}
{{--                    <label>{{__("Map Zoom")}}:</label>--}}
{{--                    <input type="text" name="map_zoom" class="form-control" value="{{@$candidate->map_zoom ?? "8"}}" readonly onkeydown="return event.key !== 'Enter';">--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}



