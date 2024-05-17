<!--Login Form-->
<form method="post" class="bravo-form-login" action="{{ route('login') }}">
    <input type="hidden" name="redirect" value="{{request()->query('redirect')}}">
    @csrf
    <div class="form-group">
        <label>{{__('Email address')}}</label>
        <input type="text" name="email" placeholder="{{__('Email address')}}" required>
        <span class="invalid-feedback error error-email"></span>
    </div>

    <div class="form-group">
        <label>{{ __("Password") }}</label>
        <div style="position: relative;">
        <input type="password" name="password" value="" placeholder="{{ __("Password") }}" class="password-input">
            <i class="fa fa-eye show-password" title="Show/Hide password"></i>
        </div>
        <span class="invalid-feedback error error-password"></span>
    </div>

    <div class="form-group">
        <div class="field-outer">
            <div class="input-group checkboxes square">
                <input type="checkbox" name="remember" value="1" id="remember">
                <label for="remember" class="remember"><span class="custom-checkbox"></span> {{ __("Remember me") }}</label>
            </div>
            <a href="{{ route("password.request") }}" class="pwd">{{ __("Forgot password?") }}</a>
        </div>
    </div>
    @if(setting_item("recaptcha_enable"))
        <div class="form-group">
            {{recaptcha_field($captcha_action ?? 'login')}}
            <span class="invalid-feedback error error-recaptcha"></span>
        </div>
    @endif

    <div class="form-group">
        <button class="theme-btn btn-style-ten" type="submit" name="log-in">{{ __("Login") }}
            <span class="spinner-grow spinner-grow-sm icon-loading" role="status" aria-hidden="true"></span>
        </button>
    </div>
    <div class="bottom-box">
        <div class="text">{{ __("Don't have an account?") }} <a href="{{ route('register') }}" class="{{ (isset($popup) && $popup) ? 'bc-call-modal' : '' }} signup" style="color: #0d95e8; font-size: large; text-decoration: underline">{{ __("Create an Account") }}</a></div>
        @if(setting_item('facebook_enable') or setting_item('google_enable') or setting_item('linkedin_enable'))
            <div class="divider"><span>{{ __("or") }}</span></div>
            <div class="btn-box row">
                @if(setting_item('facebook_enable'))
                    <div class="col-lg-6 col-md-12">
                        <a href="{{url('/social-login/facebook')}}" data-channel="facebook" class="theme-btn social-btn-two facebook-btn"><i class="fab fa-facebook-f"></i> {{ __("Log In via Facebook") }}</a>
                    </div>
                @endif
                @if(setting_item('google_enable'))
                    <div class="col-lg-6 col-md-12">
                        <a href="{{url('social-login/google')}}" data-channel="google" class="theme-btn social-btn-two google-btn"><i class="fab fa-google"></i> {{ __("Log In via Gmail") }}</a>
                    </div>
                @endif
                @if(setting_item('linkedin_enable'))
                    <div class="col-lg-6 col-md-12">
                        <a href="{{url('social-login/linkedin')}}" data-channel="linkedin" class="theme-btn social-btn-two linkedin-btn"><i class="fab fa-linkedin"></i> {{ __("Log In via LinkedIn") }}</a>
                    </div>
                @endif
            </div>
        @endif
    </div>

</form>
