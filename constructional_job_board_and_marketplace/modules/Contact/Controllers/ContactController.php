<?php

namespace Modules\Contact\Controllers;

use App\Helpers\ReCaptchaEngine;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Matrix\Exception;
use Modules\Candidate\Models\CandidateContact;
use Modules\Contact\Emails\NotificationToAdmin;
use Modules\Contact\Emails\NotificationToUser;
use Modules\Contact\Events\UserSentHelpMessageEvent;
use Modules\Contact\Models\Contact;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function __construct()
    {

    }

    public function index(Request $request)
    {
        $data = [
            'user' => Auth::user(),
            'page_title' => __("Contact Page"),
            'header_transparent' => true,
        ];
        return inertia('Help/Index', $data);

    }

    public function dashboardIndex(Request $request)
    {
        $data = [
            'page' => [],
            'user' => Auth::user(),
            'page_title' => __("Contact Page"),
            'header_transparent' => true,
        ];
        return view('Contact::index', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'max:255',
                'email'
            ],
            'first_name' => ['required'],
            'last_name' => ['required'],
//            'subject' => ['required'],
            'message' => ['required']
        ]);
        /**
         * Google ReCapcha
         */
        if (ReCaptchaEngine::isEnable()) {
            $codeCapcha = $request->input('g-recaptcha-response');
            if (!$codeCapcha or !ReCaptchaEngine::verify($codeCapcha)) {
                $data = [
                    'status' => 0,
                    'message' => __('Please verify the captcha'),
                ];
                return response()->json($data, 200);
            }
        }
        $row = new Contact($request->input());
        $row->name = $request->first_name.' '.$request->last_name;
        $row->status = 'sent';
        if ($row->save()) {
            $this->sendEmail($row);
            if ($_SERVER['HTTP_REFERER'] == route('user.help.index')){
                return redirect()->back()->with('success', 'Thank you for contacting us!');
            }
            $data = [
                'status' => 1,
                'message' => __('Thank you for contacting us!'),
            ];
            return response()->json($data, 200);
        }
    }

    protected function sendEmail($contact)
    {
        if ($admin_email = setting_item('admin_email')) {
            try {
                Mail::to($admin_email)->send(new NotificationToAdmin($contact));
                event(new UserSentHelpMessageEvent($contact));
            } catch (Exception $exception) {
                Log::warning("Contact Send Mail: " . $exception->getMessage());
            }
        }
        Mail::to($contact->email)->send(new NotificationToUser($contact));
    }

    public function t()
    {
        return new NotificationToAdmin(Contact::find(1));
    }
}
