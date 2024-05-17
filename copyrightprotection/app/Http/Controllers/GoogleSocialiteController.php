<?php

namespace App\Http\Controllers;

use App\Services\GoogleSocialiteService;
use Laravel\Socialite\Facades\Socialite;

class GoogleSocialiteController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Create a new controller instance.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function handleCallback(GoogleSocialiteService $googleSocialiteService)
    {
        return $googleSocialiteService->authorize(Socialite::driver('google')->user());
    }
}
