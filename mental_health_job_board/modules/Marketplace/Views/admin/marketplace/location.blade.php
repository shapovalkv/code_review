<div class="form-group-smart-search">
    <label class="control-label">{{__("Location")}}</label>
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
    $traverse($marketplace_location);
    ?>
    <div class="form-group col-md-12 col-sm-12 p-0 location smart-search">
        <span class="icon flaticon-map-locator"></span>
        <input id="admin_location_select" type="text"
               class="smart-search-location parent_text form-control"
               placeholder="{{__("All Locations")}}"
               value="{{ $row->location->name ?? $location_name }}"
               data-onLoad="{{__("Loading...")}}"
               data-default="">
        <input type="hidden" class="child_id" name="location_id"
               value="{{$row->location_id ?? Request::query('location_id')}}">
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
