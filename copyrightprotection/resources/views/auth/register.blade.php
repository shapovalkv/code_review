<x-guest-layout>
        @csrf
        <main class="main" id="top">
            <div class="container-fluid">
                <div class="row min-vh-100 flex-center g-0">
                    <div class="col-lg-8 col-xxl-5 py-3 position-relative"><img class="bg-auth-circle-shape" src="{{ asset('assets/img/icons/spot-illustrations/bg-shape.png') }}" alt="" width="250">
                        <img class="bg-auth-circle-shape-2" src="{{ asset('assets/img/icons/spot-illustrations/shape-1.png') }}" alt="" width="150">
                        <div class="card overflow-hidden z-1">
                            <div class="card-body p-0">
                                <div class="row g-0 h-100">
                                    <div class="col-md-5 text-center bg-card-gradient" style="background-image:url(../assets/img/generic/Landing-Background.png);background-repeat: no-repeat !important;background-size: cover !important;">
                                        <div class="position-relative p-4 pt-md-5 pb-md-7" data-bs-theme="light">
                                            <div class="bg-holder bg-auth-card-shape" style="background-image:url(../../../assets/img/icons/spot-illustrations/half-circle.png);"></div>
                                            <div class="z-1 position-relative"><a class="link-light mb-4 font-sans-serif fs-4 d-inline-block fw-bolder" href="{{ route('home') }}"><img class="me-2" src="{{ asset('assets/img/logos/logo1.svg') }}" alt="" width="40"/></a></div>
                                        </div>
                                        <div class="mt-3 mb-4 mt-md-4 mb-md-5" data-bs-theme="dark">
                                            <p class="pt-3 text-white">Have an account?<br><a class="btn btn-dark mt-2 px-4" href="{{ route('login') }}">Log In</a></p>
                                        </div>
                                    </div>
                                    <div class="col-md-7 d-flex flex-center">
                                        <div class="p-4 p-md-5 flex-grow-1">
                                            <h3>Register</h3>
                                            <form method="POST" action="{{ route('register') }}">
                                                @csrf

                                                <div class="mb-3">
                                                    <x-input-label class="form-label" for="card-name" :value="__('First Name')" />
                                                    <x-text-input id="card-name" class="form-control" type="text" name="first_name" :value="old('first_name')" required autofocus autocomplete="first_name" />
                                                    <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                                                </div>
                                                <div class="mb-3">
                                                    <x-input-label class="form-label" for="card-name" :value="__('Last Name')" />
                                                    <x-text-input id="card-name" class="form-control" type="text" name="last_name" :value="old('last_name')" required autofocus autocomplete="last_name" />
                                                    <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                                                </div>
                                                <div class="mb-3">
                                                    <x-input-label class="form-label" for="email" :value="__('Email')" />
                                                    <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autocomplete="on" />
                                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                                </div>
                                                <div class="mb-3">
                                                    <x-input-label class="form-label" for="phone" :value="__('Phone')" />
                                                    <x-text-input id="phone" class="form-control" type="phone" name="phone" :value="old('phone')" required autocomplete="on" />
                                                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                                                </div>
                                                <div class="row gx-2">
                                                    <div class="mb-3 col-sm-6">
                                                        <x-input-label class="form-label" for="password" :value="__('Password')" />
                                                        <x-text-input id="password" class="form-control"
                                                                      type="password"
                                                                      name="password"
                                                                      required autocomplete="new-password" />
                                                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                                    </div>
                                                    <div class="mb-3 col-sm-6">
                                                        <x-input-label class="form-label" for="password_confirmation" :value="__('Confirm Password')" />
                                                        <x-text-input id="password_confirmation" class="form-control"
                                                                      type="password"
                                                                      name="password_confirmation" required autocomplete="new-password" />

                                                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                                    </div>
                                                </div>
                                                <div class="form-check">
                                                    <x-text-input id="card-register-checkbox" class="form-check-input"
                                                                  type="checkbox"
                                                                  name="terms_conditions"
                                                                  required />
                                                    <x-input-error :messages="$errors->get('terms_conditions')" class="mt-2" />
                                                    <label class="form-label" for="card-register-checkbox">I accept the <a href="{{ route('pages.terms.conditions') }}">terms and privacy policy</a></label>
                                                </div>
                                                <div class="mb-3">
                                                    <button class="btn btn-dark d-block w-100 mt-3" type="submit" name="submit">Register</button>
                                                </div>
                                            </form>
                                            <div class="position-relative mt-4">
                                                <hr />
                                                <div class="divider-content-center">or register with</div>
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
</x-guest-layout>
