<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')"/>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- ===============================================-->
        <!--    Main Content-->
        <!-- ===============================================-->
        <main class="main" id="top">
            <div class="container-fluid">
                <div class="row min-vh-100 flex-center g-0">
                    <div class="col-lg-8 col-xxl-5 py-3 position-relative">
                        <img class="bg-auth-circle-shape" src="{{ asset('assets/img/illustrations/bg-shape.png') }}" alt="" width="250">
                        <img class="bg-auth-circle-shape-2" src="{{ asset('assets/img/icons/spot-illustrations/shape-1.png') }}" alt="" width="150">
                        <div class="card overflow-hidden z-1">
                            <div class="card-body p-0">
                                <div class="row g-0 h-100">
                                    <div class="col-md-5 text-center bg-card-gradient" style="background-image:url(../assets/img/generic/Landing-Background.png);background-repeat: no-repeat !important;background-size: cover !important;">
                                        <div class="position-relative p-4 pt-md-5 pb-md-7" data-bs-theme="light">
                                            <div class="bg-holder bg-auth-card-shape"
                                                 style="background-image:url({{asset('assets/img/icons/spot-illustrations/half-circle.png')}});">
                                            </div>
                                            <!--/.bg-holder-->

                                            <div class="z-1 position-relative"><a
                                                    class="link-light mb-4 font-sans-serif fs-4 d-inline-block fw-bolder"
                                                    href="{{ route('home') }}"><img class="me-2" src="{{ asset('assets/img/logos/logo1.svg') }}" alt="" width="40"/></a>
                                            </div>
                                        </div>
                                        <div class="mt-3 mb-4 mt-md-4 mb-md-5" data-bs-theme="light">
                                            <p class="text-black">Don't have an account?<br><a
                                                    class="text-decoration-underline link-black"
                                                    href="{{ route('register') }}">Get
                                                    started!</a></p>
                                            <p class="mb-0 mt-4 mt-md-5 fs--1 fw-semi-bold text-black opacity-75">Read
                                                our <a class="text-decoration-underline text-black" href="{{ route('pages.terms.conditions') }}">terms and conditions </a></p>
                                        </div>
                                    </div>
                                    <div class="col-md-7 d-flex flex-center">
                                        <div class="p-4 p-md-5 flex-grow-1">
                                            <div class="row flex-between-center">
                                                <div class="col-auto">
                                                    <h3>Account Login</h3>
                                                </div>
                                            </div>
                                            <form>
                                                <div class="mb-3">
                                                    <label class="form-label" for="card-email">Email address</label>
                                                    <x-text-input class="form-control" id="card-email" type="email"
                                                                  name="email" :value="old('email')" required autofocus
                                                                  autocomplete="username"/>
                                                    <x-input-error :messages="$errors->get('email')" class="mt-2"/>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="card-email">Password</label>
                                                    <x-text-input class="form-control" id="card-password" type="password"
                                                                  name="password" :value="old('password')" required autofocus/>
                                                    <x-input-error :messages="$errors->get('password')" class="mt-2"/>
                                                </div>
                                                <div class="row flex-between-center">
                                                    <div class="col-auto">
                                                        <div class="form-check mb-0">
                                                            <input class="form-check-input" type="checkbox"
                                                                   id="card-checkbox" checked="checked"/>
                                                            <label class="form-check-label mb-0" for="card-checkbox">Remember
                                                                me</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto"><a class="fs--1"
                                                                             href="{{ route('password.request') }}">Forgot
                                                            Password?</a></div>
                                                </div>
                                                <div class="mb-3">
                                                    <button class="btn btn-dark d-block w-100 mt-3" type="submit"
                                                            name="submit">Log in
                                                    </button>
                                                </div>
                                            </form>
                                            <div class="position-relative mt-4">
                                                <hr/>
                                                <div class="divider-content-center">or log in with</div>
                                            </div>
                                            <div class="row d-flex justify-content-center g-2 mt-2">
                                                <div class="col-sm-6">
                                                    <a class="btn btn-outline-google-plus btn-sm d-block w-100" href="{{ route('google.login') }}"><span class="fab fa-google-plus-g me-2" data-fa-transform="grow-8"></span> Google</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </form>
</x-guest-layout>
