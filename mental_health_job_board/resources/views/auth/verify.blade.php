@extends('layouts.app')

@section('content')
    <style>
        .card {
            height: auto;
        }
    </style>
    <div class="container">
        <div class="row justify-content-center bravo-login-form-page bravo-login-page">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Verify Your Email Address') }}</div>
                    <div class="card-body">
                        @if ($message = Session::get('error'))
                            <div class="alert alert-danger alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{!! clean($message) !!}</strong>
                            </div>
                        @endif
                        @if (session('resent'))
                            <div class="alert alert-success" role="alert">
                                {{ __('A fresh verification code has been sent to your email address.') }}
                            </div>
                        @endif
                        <p>{{ __('Before proceeding, please check your email for a verification code.') }}</p>
                        <form action="{{ route('verification.verify.post') }}" method="post" class="form-inline mt-3 mb-3">
                            @csrf
                            <div class="form-row align-items-center">
                                <div class="col-auto">
                                    <div class="input-group mb-2">
                                        <label class="mr-1 hidden-xs" for="code-input">Code: </label>
                                        <input type="text" class="form-control" id="code-input" placeholder="verification code" name="code">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary btn-sm" style="padding: 0 20px 0 20px">{{__('Verify')}}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <p>{{ __('If you did not receive the email') }}, <a
                                onclick="event.preventDefault(); document.getElementById('email-form').submit(); "
                                href="{{ route('verification.resend') }}">{{ __('click here to request another') }}</a>.
                        </p>
                        <form id="email-form" action="{{ route('verification.resend') }}" method="POST"
                              style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
