@extends('layouts.user')
@section('head')
<style>
    .tutorial iframe {
        width: 100%;
        height: 500px;
    }
</style>
@endsection
@section('content')
    <div class="upper-title-box">
            <h3>{{__("Tutorial video")}}</h3>
    </div>
    @include('admin.message')
    <div class="row tutorial">
        <div class="col-lg-12">
            <div class="ls-widget">
                <div class="tabs-box">
                    <div class="widget-content">
                        {!! $content !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
@endsection
