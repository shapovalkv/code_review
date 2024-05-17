<div class="form bravo-form-register">
        <div class="form-group">
            <label>{{__('Choose your role:')}}</label>
            <div class="btn-box row">
                <div class="col-lg-6 col-md-12">
                    <input class="checked" type="radio" name="type" id="checkbox1" value="candidate" />
                    <label for="checkbox1" class="theme-btn btn-style-four"><i class="la la-user"></i> {{ __("Candidate") }}</label>
                </div>
                <div class="col-lg-6 col-md-12">
                    <input class="checked" type="radio" name="type" id="checkbox2" value="employer"/>
                    <label for="checkbox2" class="theme-btn btn-style-three"><i class="la la-briefcase"></i> {{ __("Employer") }}</label>
                </div>
            </div>
            <div class="btn-box row">
                <div class="col-lg-3 d-none d-md-block"></div>
                <div class="col-lg-6 col-md-12">
                    <input class="checked" type="radio" name="type" id="checkbox3" value="marketplace-user"/>
                    <label for="checkbox3" class="theme-btn btn-style-nine"><i class="la la-book-open"></i> {{ __("Marketplace User") }}</label>
                </div>
                <div class="col-lg-3 d-none d-md-block"></div>
            </div>
            <span class="invalid-feedback error error-type"></span>
        </div>
        <div class="form-group">
            <a class="bc-call-modal signup-second theme-btn btn-style-ten text-white" >{{ __('Select') }}</a>
        </div>
    @if(setting_item('facebook_enable') or setting_item('google_enable') or setting_item('twitter_enable'))
        <div class="bottom-box">
            <div class="divider"><span>or</span></div>
            <div class="btn-box row">
                @if(setting_item('facebook_enable'))
                    <div class="col-lg-6 col-md-12">
                        <a href="{{url('/social-login/facebook')}}" class="theme-btn social-btn-two facebook-btn btn_login_fb_link"><i class="fab fa-facebook-f"></i>{{__('Facebook')}}</a>
                    </div>
                @endif
                @if(setting_item('google_enable'))
                    <div class="col-lg-6 col-md-12">
                        <a href="{{url('social-login/google')}}" class="theme-btn social-btn-two google-btn btn_login_gg_link"><i class="fab fa-google"></i>{{__('Google')}}</a>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>


