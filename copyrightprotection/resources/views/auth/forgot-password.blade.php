<x-guest-layout>
    <form method="POST" action="{{ route('password.email') }}">
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
                                            <div class="mb-4 text-sm text-gray-600 text-center">
                                                {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                                            </div>

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
                                                        <div class="d-flex flex-center">
                                                            <x-input-label class="form-label" for="email" :value="__('Email')" />
                                                        </div>
                                                        <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus="on" />
                                                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
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
