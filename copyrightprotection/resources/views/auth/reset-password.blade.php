<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}">
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
                                    <div class="col-md-12 d-flex flex-center">
                                        <div class="p-4 p-md-5 flex-grow-1">
                                            <!-- Password Reset Token -->
                                            <input type="hidden" name="token" value="{{ $request->route('token') }}">

                                            <!-- Session Status -->
                                            <div class="d-flex flex-center">
                                                <div class="mb-3">
                                                    <div class="d-flex flex-center">
                                                        <x-auth-session-status class="mb-4" :status="session('status')" />
                                                    </div>
                                                </div>
                                            </div>

                                            <form method="POST" action="{{ route('password.email') }}">
                                                @csrf

                                                <!-- Email Address -->

                                                <div class="d-flex flex-center">
                                                    <div class="mb-3">
                                                        <!-- Email Address -->
                                                        <div class="col-md">
                                                            <x-input-label for="email" :value="__('Email')" />
                                                            <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
                                                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                                        </div>
                                                        <!-- Password -->
                                                        <div class="mt-4">
                                                            <x-input-label for="password" :value="__('Password')" />
                                                            <x-text-input id="password" class="form-control" type="password" name="password" required autocomplete="new-password" />
                                                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                                        </div>
                                                        <!-- Confirm Password -->
                                                        <div class="mt-4">
                                                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                                                            <x-text-input id="password_confirmation" class="form-control"
                                                                          type="password"
                                                                          name="password_confirmation" required autocomplete="new-password" />

                                                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="d-flex flex-center">
                                                    <div class="mb-3">
                                                        <button class="btn btn-dark d-block w-100 mt-3" type="submit"
                                                                name="submit">{{ __('Reset Password') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
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

