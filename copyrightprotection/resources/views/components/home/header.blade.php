<nav class="navbar navbar-standard navbar-expand-lg fixed-top navbar-dark" id="navbar-dashboard">
    <div class="container"><a class="navbar-brand" href="{{ route('home') }}"><span class="text-white dark__text-white"><img class="me-2" src="{{ asset('assets/img/logos/logo2.svg') }}" alt="" width="40"/></span></a>
        <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarStandard" aria-controls="navbarStandard" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse scrollbar" id="navbarStandard">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item d-flex align-items-center me-2">
                    <div class="nav-link theme-switch-toggle fa-icon-wait p-0">
                        <input class="form-check-input ms-0 theme-switch-toggle-input" id="themeControlToggle" type="checkbox" data-theme-control="theme" value="dark">
                        <label class="mb-0 theme-switch-toggle-label theme-switch-toggle-light" for="themeControlToggle" data-bs-toggle="tooltip" data-bs-placement="left" title="Switch to light theme"><span class="fas fa-sun"></span></label>
                        <label class="mb-0 py-2 theme-switch-toggle-light d-lg-none" for="themeControlToggle"><span>Switch to light theme</span></label>
                        <label class="mb-0 theme-switch-toggle-label theme-switch-toggle-dark" for="themeControlToggle" data-bs-toggle="tooltip" data-bs-placement="left" title="Switch to dark theme"><span class="fas fa-moon"></span></label>
                        <label class="mb-0 py-2 theme-switch-toggle-dark d-lg-none" for="themeControlToggle"><span>Switch to dark theme</span></label>
                    </div>
                </li>

                @auth()
                    <li class="nav-item d-flex align-items-center me-2"><a class="nav-link" href="{{ route('profile.edit') }}">Profile &amp; account</a></li>
                @else
                    <li class="nav-item d-flex align-items-center me-2"><a class="nav-link" href="#!" data-bs-toggle="modal" data-bs-target="#LoginModal">Login</a></li>
                    <li class="nav-item d-flex align-items-center me-2"><a class="nav-link" href="#!" data-bs-toggle="modal" data-bs-target="#exampleModal">Register</a></li>
                @endif
                <li class="nav-item d-flex align-items-center me-2"><a class="nav-link" href="{{ route('pages.pricing') }}">Pricing</a></li>
                <li class="nav-item d-flex align-items-center me-2"><a class="nav-link" href="{{ route('pages.faq') }}">FAQ</a></li>
                <li class="nav-item d-flex align-items-center me-2"><a class="nav-link" href="{{ route('pages.about') }}">About</a></li>
                <li class="nav-item d-flex align-items-center me-2"><a class="nav-link" href="{{ route('pages.resources') }}">Resources</a></li>
                <li class="nav-item d-flex align-items-center me-2"><a class="nav-link" href="{{ route('pages.contact') }}">Contact Us</a></li>
                @auth
                    <li class="nav-item d-flex align-items-center me-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link class="nav-link" :href="route('logout')"
                                             onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
<div class="modal fade" id="LoginModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body p-4">
                <div class="card shadow-none navbar-card-login">
                    <div class="card-body fs--1 p-4 fw-normal">
                        <div class="row text-start justify-content-between align-items-center mb-2">
                            <div class="col-auto">
                                <h5 class="mb-0">Log in</h5>
                            </div>
                            <div class="col-auto">
                                <p class="fs--1 text-600 mb-0">or <a href="{{ route('register') }}">Create an
                                        account</a></p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

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
                                        <input class="form-check-input" type="checkbox" id="modal-checkbox"/>
                                        <label class="form-check-label mb-0" for="modal-checkbox">Remember me</label>
                                    </div>
                                </div>
                                <div class="col-auto"><a class="fs--1" href="{{ route('password.request') }}">Forgot
                                        Password?</a></div>
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-dark d-block w-100 mt-3" type="submit" name="submit">Log in
                                </button>
                            </div>
                        </form>
                        <div class="position-relative mt-4">
                            <hr/>
                            <div class="divider-content-center">or log in with</div>
                        </div>
                        <div class="row d-flex justify-content-center g-2 mt-2">
                            <div class="col-sm-6"><a class="btn btn-outline-google-plus btn-sm d-block w-100"
                                                     href="{{ route('google.login') }}"><span
                                        class="fab fa-google-plus-g me-2" data-fa-transform="grow-8"></span> Google</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body p-4">
                <div class="row text-start justify-content-between align-items-center mb-2">
                    <div class="col-auto">
                        <h5 id="modalLabel">Register</h5>
                    </div>
                    <div class="col-auto">
                        <p class="fs--1 text-600 mb-0">Have an account? <a href="{{ route('login') }}">Login</a></p>
                    </div>
                </div>
                <form method="POST" id="registerForm">
                    @csrf
                    <div class="mb-3">
                        <x-input-label class="form-label" for="card-nameInput" :value="__('First Name')" />
                        <x-text-input id="card-nameInput" class="form-control" type="text" name="first_name" :value="old('first_name')" required autofocus autocomplete="first_name" />
                        <span class="invalid-feedback" role="alert" id="first_nameError"><strong></strong></span>
                    </div>
                    <div class="mb-3">
                        <x-input-label class="form-label" for="card-nameInput" :value="__('Last Name')" />
                        <x-text-input id="card-nameInput" class="form-control" type="text" name="last_name" :value="old('last_name')" required autofocus autocomplete="last_name" />
                        <span class="invalid-feedback" role="alert" id="last_nameError"><strong></strong></span>
                    </div>
                    <div class="mb-3">
                        <x-input-label class="form-label" for="emailInput" :value="__('Email')" />
                        <x-text-input id="emailInput" class="form-control" type="email" name="email" :value="old('email')" required autocomplete="on" />
                        <span class="invalid-feedback" role="alert" id="emailError"><strong></strong></span>
                    </div>
                    <div class="mb-3">
                        <x-input-label class="form-label" for="phoneInput" :value="__('Phone')" />
                        <x-text-input id="phoneInput" class="form-control" type="phone" name="phone" :value="old('phone')" required autocomplete="on" />
                        <span class="invalid-feedback" role="alert" id="phoneError"><strong></strong></span>
                    </div>
                    <div class="row gx-2">
                        <div class="mb-3 col-sm-6">
                            <x-input-label class="form-label" for="passwordInput" :value="__('Password')" />
                            <x-text-input id="passwordInput" class="form-control"
                                          type="password"
                                          name="password"
                                          required autocomplete="new-password" />
                            <span class="invalid-feedback" role="alert" id="passwordError"><strong></strong></span>
                        </div>
                        <div class="mb-3 col-sm-6">
                            <x-input-label class="form-label" for="password_confirmationInput" :value="__('Confirm Password')" />
                            <x-text-input id="password_confirmationInput" class="form-control"
                                          type="password"
                                          name="password_confirmation" required autocomplete="new-password" />

                            <span class="invalid-feedback" role="alert" id="password_confirmationError"><strong></strong></span>

                        </div>
                    </div>
                    <div class="form-check">
                        <x-text-input id="card-register-checkboxInput" class="form-check-input"
                                      type="checkbox"
                                      name="terms_conditions"
                                      required />
                        <span class="invalid-feedback" role="alert" id="terms_conditionsError"><strong></strong></span>
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
                    <div class="col-sm-6"><a class="btn btn-outline-google-plus btn-sm d-block w-100" href="{{ route('google.login') }}"><span class="fab fa-google-plus-g me-2" data-fa-transform="grow-8"></span> Google</a></div>
                </div>
            </div>
        </div>
    </div>
</div>
