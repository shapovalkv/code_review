@extends('layouts.user')
@section('head')

@endsection
@section('content')
    <div class="upper-title-box">
        <h3>{{__("Following Employers")}}</h3>
        <div class="text">{{ __("Ready to jump back in?") }}</div>
    </div>
    @include('admin.message')
    <div class="row">
        <div class="col-lg-12">
            <div class="ls-widget">
                <div class="tabs-box">
                    <div class="widget-title">
                        <h4>{{__("Following Employers")}}</h4>

                        <div class="chosen-outer">
                            <form method="get" class="default-form form-inline" action="">
                                <div class="form-group mb-0 mr-1">
                                    <input type="text" name="s" placeholder="{{ __("Search...") }}" value="{{ request()->get('s') }}" class="form-control">
                                </div>
                                <button type="submit" class="theme-btn btn-style-one">{{ __("Search") }}</button>
                            </form>
                        </div>
                    </div>

                    <div class="widget-content">
                        <div class="list-following-employer mb-4">
                            @if($rows->total() > 0)
                                @foreach($rows as $row)
                                    @include("Company::frontend.layouts.loop.company-item-bookmark", ['wishlist' => $row, 'row' => $row->service])
                                @endforeach
                            @else
                                <h4 class="text-center">{{ __("No items") }}</h4>
                            @endif
                        </div>
                        <div class="ls-pagination mt-0">
                            {{$rows->appends(request()->query())->links()}}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
@endsection
