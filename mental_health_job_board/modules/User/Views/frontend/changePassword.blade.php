@extends('layouts.user')

@section('content')
    <div class="bravo_user_profile p-0">
        <div class="upper-title-box">
            <h3 class="title">{{__("Change Password")}}</h3>
        </div>
        <div class="row">
            <div class="col-md-6">
                @include('admin.message')
            </div>
        </div>
        <form action="{{ route("user.change_password.update") }}" method="post" class="default-form pb-4 password-change">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="ls-widget mb-4 ">
                        <div class="tabs-box">
                            <div class="widget-title"><strong>{{ __('Change Password') }}</strong></div>
                            <div class="widget-content">
                                <div class="form-group">
                                    <label>{{__(auth()->user()->need_update_pw ? "Temporary Password" : "Current Password")}}</label>
                                    <input type="password" name="current-password" placeholder="{{__(auth()->user()->need_update_pw ? "Temporary Password" : "Current Password")}}" class="form-control password-input">
                                    <i class="fa fa-eye show-password" title="Show/Hide password"></i>
                                </div>
                                <div class="form-group">
                                    <label>{{__("New Password")}}</label>
                                    <input type="password" name="new-password" placeholder="{{__("New Password")}}" class="form-control password-input">
                                    <i class="fa fa-eye show-password" title="Show/Hide password"></i>
                                </div>
                                <div class="form-group">
                                    <label>{{__("Re-Type Password")}}</label>
                                    <input type="password" name="new-password_confirmation" placeholder="{{__("Re-Type Password")}}" class="form-control password-input">
                                    <i class="fa fa-eye show-password" title="Show/Hide password"></i>
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="theme-btn btn-style-seven mr-2" value="{{__("Update")}}">
                                    <a href="{{ route("user.dashboard") }}" class="theme-btn btn-style-eight">{{__("Cancel")}}</a>
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
