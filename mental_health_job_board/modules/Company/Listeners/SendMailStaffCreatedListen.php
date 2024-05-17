<?php

namespace Modules\Company\Listeners;

use Illuminate\Support\Facades\Mail;
use Modules\Company\Events\SendMailStaffCreated;
use Modules\User\Emails\RegisteredEmail;
use Modules\User\Models\User;

class SendMailStaffCreatedListen
{

    public User $user;

    const CODE = [
        'first_name'    => '[first_name]',
        'last_name'     => '[last_name]',
        'name'          => '[name]',
        'email'         => '[email]',
        'button_verify' => '[button_verify]',
        'password'      => '[password]',
        'company'      => '[company]',
        'project'      => '[project]',

    ];

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle(SendMailStaffCreated $event)
    {
        if ($event->user->locale) {
            $old = app()->getLocale();
            app()->setLocale($event->user->locale);
        }

        $body = $this->replaceContentEmail($event, setting_item_with_lang('staff_content_email_registered', app()->getLocale()));
        Mail::to($event->user->email)->send(new RegisteredEmail($event->user, $body, 'customer'));

        if (!empty($old)) {
            app()->setLocale($old);
        }

        if (!empty(setting_item('admin_email') and !empty(setting_item_with_lang('admin_enable_mail_user_registered', app()->getLocale())))) {
            $body = $this->replaceContentEmail($event, setting_item_with_lang('admin_content_email_user_registered', app()->getLocale()));
            Mail::to(setting_item('admin_email'))->send(new RegisteredEmail($event->user, $body, 'admin'));
        }

    }

    public function replaceContentEmail($event, $content)
    {
        if (!empty($content)) {
            foreach (self::CODE as $item => $value) {
                if ($item == "button_verify") {
                    $content = str_replace($value, $this->buttonVerify($event), $content);
                } elseif ($item === 'password') {
                    $content = str_replace($value, $event->getPassword(), $content);
                } elseif ($item === 'company') {
                    $content = str_replace($value, @$event->user->parent->company->name, $content);
                } elseif ($item === 'project') {
                    $content = str_replace($value, config('app.name'), $content);
                } else {
                    $content = str_replace($value, @$event->user->$item, $content);
                }
            }
        }
        return $content;
    }

    public function buttonVerify($event)
    {
        $text = __('Login to your personal account');
        $button = '<a style="border-radius: 3px;
                color: #fff;
                display: inline-block;
                text-decoration: none;
                background-color: #3490dc;
                border-top: 10px solid #3490dc;
                border-right: 18px solid #3490dc;
                border-bottom: 10px solid #3490dc;
                border-left: 18px solid #3490dc;" href="' . route('auth.login', ['redirect' => '/user/dashboard']) . '">' . $text . '</a>';
        return $button;
    }
}
