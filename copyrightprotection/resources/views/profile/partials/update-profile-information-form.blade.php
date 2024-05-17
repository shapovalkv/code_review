<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="post" action="{{ route('profile.update') }}" class="row g-3">
    @csrf
    @method('patch')

    <div class="col-lg-6">
        <x-input-label class="form-label" for="first_name" :value="__('First Name')"/>
        <x-text-input id="first_name" name="first_name" type="text" class="form-control"
                      :value="old('name', $user->first_name)" required autofocus autocomplete="first_name"/>
        <x-input-error class="mt-2" :messages="$errors->get('first_name')"/>
    </div>

    <div class="col-lg-6">
        <x-input-label class="form-label" for="last_name" :value="__('Last Name')"/>
        <x-text-input id="last_name" name="last_name" type="text" class="form-control"
                      :value="old('last_name', $user->last_name)" required autofocus autocomplete="last_name"/>
        <x-input-error class="mt-2" :messages="$errors->get('last_name')"/>
    </div>

    <div class="col-lg-6">
        <x-input-label class="form-label" for="phone" :value="__('Phone')"/>
        <x-text-input id="phone" name="phone" type="text" class="form-control" :value="old('phone', $user->phone)"
                      required autofocus autocomplete="phone"/>
        <x-input-error class="mt-2" :messages="$errors->get('phone')"/>
    </div>

    <div class="col-lg-6">
        <x-input-label class="form-label" for="email" :value="__('Email')"/>
        <x-text-input id="email" name="email" type="email" class="form-control" :value="old('email', $user->email)"
                      readonly autocomplete="username"/>
        <x-input-error class="mt-2" :messages="$errors->get('email')"/>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div>
                <p class="text-sm mt-2 text-gray-800">
                    {{ __('Your email address is unverified.') }}

                    <button form="send-verification"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>
                </p>

                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 font-medium text-sm text-green-600">
                        {{ __('A new verification link has been sent to your email address.') }}
                    </p>
                @endif
            </div>
        @endif
    </div>

    <div class="flex items-center gap-4">
        <button class="btn btn-dark" type="submit">{{ __('Save') }}</button>
    </div>
</form>
