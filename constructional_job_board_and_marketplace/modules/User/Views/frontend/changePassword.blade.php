@extends('layouts.user')

@section('content')
    <div class="bravo_user_profile p-0">
        <div class="upper-title-box">
            <h3 class="title">{{__("Change Password")}}</h3>
            <div class="text">{{ __("Ready to jump back in?") }}</div>
        </div>
        <div class="row">
            <div class="col-md-6">
                @include('admin.message')
            </div>
        </div>
        <form action="{{ route("user.change_password.update") }}" method="post" class="default-form pb-4">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="ls-widget mb-4 ">
                        <div class="tabs-box">
                            <div class="widget-title"><strong>{{ __('Change Password') }}</strong></div>
                            <div class="widget-content">
                                <div class="form-group">
                                    <label>{{__("Current Password")}}</label>
                                    <input type="password" name="current-password" placeholder="{{__("Current Password")}}" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>{{__("New Password")}}</label>
                                    <input type="password" name="new-password" placeholder="{{__("New Password")}}" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>{{__("New Password Again")}}</label>
                                    <input type="password" name="new-password_confirmation" placeholder="{{__("New Password Again")}}" class="form-control">
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="theme-btn btn-style-one mr-2" value="{{__("Update")}}">
                                    <a href="{{ route("user.profile.index") }}" class="theme-btn btn-style-two">{{__("Cancel")}}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    <div class="bravo_user_profile">
@endsection
@section('footer')

@endsection
