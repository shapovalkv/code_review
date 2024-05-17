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
                    value="{{$row->location ? old('map_lat', $row->location->map_lat) : ''}}"
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
                    value="{{$row->location ? old('map_lng', $row->location->map_lng) : ''}}"
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
                    value="{{$row->location ? old('map_zoom', $row->location->map_zoom ?? "8") : ''}}"
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
                    value="{{$row->location ? old('map_state', $row->location->map_state ?? "") : ''}}"
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
                    value="{{$row->location ? old('map_state_long', $row->location->map_state_long ?? "") : ''}}"
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
                    value="{{$row->location ? old('map_city', $row->location->map_city ?? "") : ''}}"
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
                    value="{{$row->location ? old('map_address', $row->location->map_address ?? "") : ''}}"
                    readonly
                    onkeydown="return event.key !== 'Enter';"
                >
            </div>
        </div>
    </div>
</div>

@section ('script.body')
    {!! App\Helpers\MapEngine::scripts() !!}
    <script>
        jQuery(function ($) {
            new BravoMapEngine('map_content', {
                disableScripts: true,
                fitBounds: true,
                center: [{{ $row->location->map_lat ?? "38.896714696640004"}}, {{ $row->location->map_lng ?? "-77.04821945173418"}}],
                zoom: {{ $row->location->map_zoom ?? "8"}},
                ready: function (engineMap) {
                    @if( $row->location && $row->location->map_lat && $row->location->map_lng)
                    engineMap.addMarker([{{$row->location->map_lat}}, {{ $row->location->map_lng}}], {
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

            $('#job_type_id').select2();
        })
    </script>
@endsection
