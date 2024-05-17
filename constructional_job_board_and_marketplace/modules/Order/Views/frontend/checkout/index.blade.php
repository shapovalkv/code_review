@extends('layouts.user')

@php
    $manifestDir = 'dist/frontend';
@endphp

@section('head')
    <link rel="stylesheet" href="{{asset('dist/frontend/module/order/css/checkout.css?_v='.config('app.asset_version'))}}">
@endsection

@section('content')
<div class="default-form post-form">
    <div class="upper-title-box">
        <div class="row">
            <div class="col-md-9">
                <h3>{{__('Checkout')}}</h3>
            </div>
        </div>
    </div>

    <div class="checkout-page py-0" id="bravo-checkout-page" v-cloak>
        @if(\Modules\Order\Helpers\CartManager::count())

        <div class="row">
            <div class="col-lg-7 col-xxl-8">
                <input type="hidden" name="redirectTo" value="{{ request()->get('redirectTo') }}" />
                @include ('Order::frontend.checkout.billing')
            </div>

            <div class="col-lg-5 col-xxl-4 post-form__right-col-wrap">
                <div class="post-form__right-col">
                    @include ('Order::frontend.checkout.review')

                    <div class="payment-box">
                    <div class="payment-options">
                        @include ('Order::frontend.checkout.payment')
                        <hr>
                        @php
                            $term_conditions = setting_item('booking_term_conditions');
                        @endphp

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" id="term_conditions" type="checkbox" name="term_conditions">
                                <label class="custom-control-label" for="term_conditions">{{__('I have read and accept the')}}</label>
                                <a class="custom-control-label__link" target="_blank" href="{{get_page_url($term_conditions)}}">{{__('terms and conditions')}}</a>
                            </div>
                        </div>
                        @if(setting_item("booking_enable_recaptcha"))
                            <div class="form-group">
                                {{recaptcha_field('booking')}}
                            </div>
                        @endif
                        <div class="html_before_actions"></div>

                        <p class="alert mt10" v-show=" message.content" v-html="message.content" :class="{'alert-danger':!message.type,'alert-success':message.type}"></p>

                        <button class="post-form__send-btn f-btn primary-btn theme-btn btn-style-one" @click.prevent="doCheckout">
                            {{__('Place Order')}}

                            <svg width="28" height="8" viewBox="0 0 28 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M28 4L23 8V0L28 4Z" fill="white"/>
                                <path d="M23 3.58011H0V4.41989H23V3.58011Z" fill="white"/>
                            </svg>
                        </button>
                    </div>
                </div>
                </div>
            </div>
        </div>
        @else
            <div class="alert alert-warning">{{__("Your cart is empty!")}}</div>
        @endif
    </div>
</div>
@endsection
@section('footer')
    <script src="{{ mix('js/bootstrap5.js', $manifestDir) }}"></script>
    <script src="{{ asset('module/order/js/checkout.js') }}"></script>
    <script>
        Maska.create('#phone', { mask: '### ### ####' });
    </script>
@endsection
