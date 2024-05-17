@extends('layouts.app')
@section('head')

@endsection
@section('content')

@php $translation = $row->translateOrOrigin(app()->getLocale()); @endphp
    @include('Company::frontend.layouts.details.ver.'. $style)
@endsection

@section('og:image')
    @if($row->avatar_id)
    <meta property="og:image" content="{{ \Modules\Media\Helpers\FileHelper::url($row->avatar_id) }}"/>
    @endif
@endsection

@section('footer')
    {!! App\Helpers\MapEngine::scripts() !!}
    <script>
    let off = {!!$offices!!}
    let mapParams = []
    let office_zoom = off.length === 1 ? off[0].map_zoom : null

        jQuery(function ($) {
            @if($offices)
            off.forEach(el => {
               mapParams.push([el.map_lat, el.map_lng])
            })
            const bounds = L.latLngBounds(mapParams)
            const center = bounds.getCenter();
            new BravoMapEngine('map-canvas', {
                disableScripts: true,
                center: center,
                bounds:bounds,
                zoom: office_zoom,
                ready: function (engineMap) {
                    off.map(el => {
                         engineMap.addMarker([el.map_lat, el.map_lng], {
                               icon_options: {}
                        });
                     })
                 }
            });
            @endif
        })
    </script>
@endsection
