<form method="post" action="{{ route('password.update') }}">
    @csrf
    @method('put')

    <div class="mb-3">
        <x-input-label class="form-label" for="current_password" :value="__('Current Password')"/>
        <x-text-input id="current_password" name="current_password" type="password" class="form-control"/>
        <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2"/>
    </div>

    <div class="mb-3">
        <x-input-label class="form-label" for="password" :value="__('New Password')"/>
        <x-text-input id="password" name="password" type="password" class="form-control" autocomplete="new-password"/>
        <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2"/>
    </div>

    <div class="mb-3">
        <x-input-label class="form-label" for="password_confirmation" :value="__('Confirm Password')"/>
        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="form-control"
                      autocomplete="new-password"/>
        <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2"/>
    </div>

    <button class="btn btn-dark" type="submit">{{ __('Update Password') }}</button>

</form>
