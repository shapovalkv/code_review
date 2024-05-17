@php
    $candidate = $row->candidate;
@endphp

<div class="form-group">
    <label class="control-label">{{__("Location")}} <span class="text-danger">*</span></label>
    <div class="form-group-smart-search">
        <div class="form-content">
            <input
                type="text"
                required
                placeholder="{{__("Location")}}"
                name="map_location_visible"
                class="bravo_searchbox form-control js-required-input"
                autocomplete="off"
                onkeydown="return event.key !== 'Enter';"
                value="{{ old('map_location', $candidate->location->map_location ?? '') }}"
            >
            <input
                type="hidden"
                name="map_location"
                class="form-control js-hidden-location"
                autocomplete="off"
                onkeydown="return event.key !== 'Enter';"
                value="{{ old('map_location', $candidate->location->map_location ?? '') }}"
                @keydown.enter.prevent
            >
        </div>
    </div>
</div>

<div class="form-group">
    <label class="control-label">{{__("The geographic coordinate")}}</label>
    <div class="control-map-group">
        <div id="map_content"></div>
        @if(is_admin())
            <div class="g-control">
                <div class="form-group">
                    <label>{{__("Map Latitude")}}:</label>
                    <input
                        type="text"
                        name="map_lat"
                        class="form-control"
                        value="{{old('map_lat', $candidate->location->map_lat ?? '0')}}"
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
                        value="{{old('map_lng', $candidate->location->map_lng ?? '0')}}"
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
                        value="{{old('map_zoom', $candidate->location->map_zoom ?? "8")}}"
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
                        value="{{old('map_state', $candidate->location->map_state ?? "")}}"
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
                        value="{{old('map_state_long', $candidate->location->map_state_long ?? "")}}"
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
                        value="{{old('map_city', $candidate->location->map_city ?? "")}}"
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
                        value="{{old('map_address', $candidate->location->map_address ?? "")}}"
                        readonly
                        onkeydown="return event.key !== 'Enter';"
                    >
                </div>
            </div>
        @else
            <input
                type="hidden"
                name="map_lat"
                class="form-control"
                value="{{old('map_lat', $candidate->location->map_lat ?? '0')}}"
                readonly
                onkeydown="return event.key !== 'Enter';"
            >
            <input
                type="hidden"
                name="map_lng"
                class="form-control"
                value="{{old('map_lng', $candidate->location->map_lng ?? '0')}}"
                readonly
                onkeydown="return event.key !== 'Enter';"
            >
            <input
                type="hidden"
                name="map_zoom"
                class="form-control"
                value="{{old('map_zoom', $candidate->location->map_zoom ?? "8")}}"
                readonly
                onkeydown="return event.key !== 'Enter';"
            >
            <input
                type="hidden"
                name="map_state"
                class="form-control"
                value="{{old('map_state', $candidate->location->map_state ?? "")}}"
                readonly
                onkeydown="return event.key !== 'Enter';"
            >
            <input
                type="hidden"
                name="map_state_long"
                class="form-control"
                value="{{old('map_state_long', $candidate->location->map_state_long ?? "")}}"
                readonly
                onkeydown="return event.key !== 'Enter';"
            >
            <input
                type="hidden"
                name="map_city"
                class="form-control"
                value="{{old('map_city', $candidate->location->map_city ?? "")}}"
                readonly
                onkeydown="return event.key !== 'Enter';"
            >
            <input
                type="hidden"
                name="map_address"
                class="form-control"
                value="{{old('map_address', $candidate->location->map_address ?? "")}}"
                readonly
                onkeydown="return event.key !== 'Enter';"
            >
        @endif
    </div>
</div>


