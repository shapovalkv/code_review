@extends('layouts.app')

@section('content')

    @include('Job::frontend.layouts.detail-ver.'. $style)

@endsection

@section('og:image')
    @if($row->company->avatar_id)
        <meta property="og:image" content="{{ \Modules\Media\Helpers\FileHelper::url($row->company->avatar_id) }}"/>
    @endif
@endsection

@section('footer')
    {!! App\Helpers\MapEngine::scripts() !!}
    <script>
        jQuery(function ($) {
            @if($row->map_lat && $row->map_lng)
            new BravoMapEngine('map-canvas', {
                disableScripts: true,
                fitBounds: true,
                center: [{{$row->map_lat}}, {{$row->map_lng}}],
                zoom:{{$row->map_zoom ?? "12"}},
                ready: function (engineMap) {
                    engineMap.addMarker([{{$row->map_lat}}, {{$row->map_lng}}], {
                        icon_options: {}
                    });
                }
            });
            @endif
        })
    </script>
@endsection
