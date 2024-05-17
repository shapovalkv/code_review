<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Services\ProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileService $profileServices, ProfileUpdateRequest $request): RedirectResponse
    {
        return $profileServices->update($request);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(ProfileService $profileServices, Request $request): RedirectResponse
    {
        return $profileServices->destroy($request);
    }
}
