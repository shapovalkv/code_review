@extends('Layout::auth.app')

@section('content')
    <div class="login-section">
        <div class="image-layer" style="background-image: url({{ asset('images/login_register_BG.jpg') }});"></div>
        <div class="outer-box">
            <!-- Login Form -->
            <div class="login-form default-form bravo-login-form-page bravo-login-page">
                @if($site_title = setting_item("site_title"))
                    <h3>{{ __("Choose Your Role :site_title", ['site_title' => $site_title]) }}</h3>
                @else
                    <h3>{{ __("Sign Up") }}</h3>
                @endif
                @include('Layout::auth.register-form',['captcha_action'=>'register_normal'])
            </div>
        </div>
    </div>
@endsection