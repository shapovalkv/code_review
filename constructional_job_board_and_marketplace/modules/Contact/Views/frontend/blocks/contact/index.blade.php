<div class="bravo-contact-block">
    <div class="upper-title-box">
        <h3>{{ __("Help") }}</h3>
    </div>

    @include('admin.message')

    <form method="post" action="{{ route("contact.store") }}"  class="default-form post-form">
        {{csrf_field()}}
        <div class="form-group">
            <div class="response"></div>
        </div>
        @if(setting_item("recaptcha_enable"))
            <div class="form-group">
                {{recaptcha_field('contact')}}
                <span class="invalid-feedback error error-recaptcha"></span>
            </div>
        @endif
        <div class="mt-3">
            <div class="form-mess"></div>
        </div>

        <div class="row">
            <div class="col-lg-8 order-1 order-lg-0">
                <div class="post-form__left-col">

                    <div class="tabs-box">
                        <div class="form-group mb-0">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{__("First name")}} <span class="text-danger">*</span></label>
                                        <input
                                            type="text"
                                            value="{{old('first_name',$user->first_name)}}"
                                            name="first_name"
                                            placeholder="{{__("First name")}}"
                                            class="form-control"
                                        >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{__("Last name")}} <span class="text-danger">*</span></label>
                                        <input
                                            type="text"
                                            required
                                            value="{{old('last_name',$user->last_name)}}"
                                            name="last_name"
                                            placeholder="{{__("Last name")}}"
                                            class="form-control"
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>{{__("E-mail")}} <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                name="email"
                                required
                                value="{{old('email',$user->email)}}"
                                placeholder="{{__("Your email")}}"
                                class="form-control"
                            >
                        </div>

                        <div class="form-group mb-0">
                                <label>{{ __('Message') }}</label>
                                <textarea
                                    name="message"
                                    placeholder="{{ __('Enter message') }}"
                                    required
                                    class="form-control"
                                ></textarea>
                            </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 order-0 order-lg-1">
                <div class="bravo-contact-block__info">
                    <div class="bravo-contact-block__message">
                        <div class="bravo-contact-block__message-title">any questions?</div>

                        <div class="bravo-contact-block__message-subtitle">
                            Please explain your issue and our support team will get back to you during next 24 hours.
                        </div>
                    </div>
                </div>
            </div>

            <div class="col px-md-0 post-form__send-btn-col order-2">
                <div class="post-form__send-btn-wrap">
                    <button class="post-form__send-btn f-btn primary-btn theme-btn btn-style-one" type="submit">
                        {{__('Send')}}

                        <svg width="28" height="8" viewBox="0 0 28 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M28 4L23 8V0L28 4Z" fill="white"/>
                            <path d="M23 3.58011H0V4.41989H23V3.58011Z" fill="white"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div style="display: none;">
            <input type="hidden" name="g-recaptcha-response" value="">
        </div>
    </form>
</div>
