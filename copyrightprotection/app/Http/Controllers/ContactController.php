<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactFormRequest;
use App\Models\Recipient;
use App\Notifications\ContactFormMessage;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class ContactController extends Controller
{
    public function show(UserService $userService)
    {
        return view('pages.contact-page', ['agentData' => $userService->getContactUserAgent(Auth::user())]);
    }

    public function mailContactForm(ContactFormRequest $message, Recipient $recipient)
    {
        Notification::send($recipient, new ContactFormMessage($message));

        return redirect()->back()->with('success', __('messages.contact_created'));
    }
}
