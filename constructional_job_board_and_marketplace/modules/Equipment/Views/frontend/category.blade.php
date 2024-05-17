@extends('layouts.app')
@section('head')
    <link href="{{ asset('dist/frontend/module/equipment/css/equipment.css?_ver='.config('app.asset_version')) }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset("libs/ion_rangeslider/css/ion.rangeSlider.min.css") }}"/>
@endsection
@section('content')
    <div class="equipment_category_level1">
        <div class="container pt-5">
            <div class=" mb-4">
                <h1 class="title mb-1 fw-500">{{$translation->name}}</h1>
                <p class="subtitle mb-0">{{$translation->content}}</p>
            </div>
            @include('equipment::frontend.search.children')
            <div class="mt-4">
                <div class="ls-outer mb-4">
                    @include('equipment::frontend.search.filter')
                    <div class="row mb-5">
                        @foreach($rows as $row)
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4">
                                @include('equipment::frontend.search.loop')
                            </div>
                        @endforeach
                    </div>
                    {{$rows->appends(request()->query())->links()}}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <script type="text/javascript" src="{{ asset("libs/ion_rangeslider/js/ion.rangeSlider.min.js") }}"></script>
    <script type="text/javascript" src="{{ asset('module/equipment/js/equipment.js?_ver='.config('app.version')) }}"></script>
@endsection
