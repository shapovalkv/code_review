@extends('layouts.app')
@section('head')
    <link rel="stylesheet" type="text/css" href="{{ asset("libs/ion_rangeslider/css/ion.rangeSlider.min.css") }}"/>
@endsection
@section('content')
    <div class="Marketplace_category_level1">
        <div class="auto-container pt-5">
            @if($category->image_id)
                <div class="category-banner bg-cover border-radius-8 mb-5 " style="background-image: url('{{get_file_url($category->image_id,'full')}}')">
                    <div class="row h-100">
                        <div class="col-md-3"></div>
                        <div class="col-md-6 d-flex align-items-center flex-column justify-content-center">
                            <h1 class="title c-white mb-3 fw-500">{{$translation->name}}</h1>
                            <p class="subtitle c-white mb-0 text-center">{{$translation->content}}</p>
                        </div>
                    </div>
                </div>
            @endif
            @include('Marketplace::frontend.search.popular')
            @include('Marketplace::frontend.search.types')
            @include('Marketplace::frontend.search.news')
        </div>
            @include('Marketplace::frontend.search.faqs')
        <div class="auto-container">
            @include('Marketplace::frontend.search.related')
        </div>
    </div>
@endsection

@section('footer')
    <script type="text/javascript" src="{{ asset("libs/ion_rangeslider/js/ion.rangeSlider.min.js") }}"></script>
    <script type="text/javascript" src="{{ asset('module/Marketplace/js/Marketplace.js?_ver='.config('app.version')) }}"></script>
@endsection
