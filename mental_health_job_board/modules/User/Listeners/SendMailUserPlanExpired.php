<?php

namespace Modules\User\Listeners;

use Illuminate\Support\Facades\Mail;
use Modules\Job\Emails\JobExpiredEmail;
use Modules\Job\Events\AutomaticJobExpiration;
use Modules\User\Emails\EmailUserPlanExpired;
use Modules\User\Emails\RegisteredEmail;
use Modules\User\Events\SendMailUserRegistered;
use Modules\User\Events\UserPlanExpired;
use Modules\User\Models\User;

class SendMailUserPlanExpired
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    const CODE = [
        'first_name'    => '[first_name]',
        'last_name'     => '[last_name]',
        'name'          => '[name]',
        'plan_name'     => '[plan_name]',
        'subscriptionButton'     => '[subscription_Button]'
    ];

    public $row;

    public function handle(UserPlanExpired $event)
    {
        $plan = $event->plan;
        $user = $plan->user;
        if(!empty($user) && !empty($user->email)) {
            $data = [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'name' => $user->getNameAttribute(),
                'plan' => $plan,
            ];
            if($user->locale){
                $old = app()->getLocale();
                app()->setLocale($user->locale);
            }

            if (!empty(setting_item('content_email_user_plan_expired'))) {
                $body = $this->replaceContentEmail($data, setting_item_with_lang('content_email_user_plan_expired', app()->getLocale()));
            } else {
                $body = $this->replaceContentEmail($data, $this->defaultBody());
            }
            Mail::to($user->email)->send(new EmailUserPlanExpired($body));

            if(!empty($old)){
                app()->setLocale($old);
            }
        }
    }

    public function replaceContentEmail($data, $content)
    {
        if (!empty($content)) {
            foreach (self::CODE as $item => $value) {
                if($item == "subscriptionButton") {
                    $content = str_replace($value, $this->subscriptionButton(), $content);
                }

                $content = str_replace($value, @$data[$item], $content);
            }
        }
        return $content;
    }

    public function defaultBody()
    {
        $body = '
            <h1>Hello!</h1>
            <p>Your Subscription Plan has expired, please renew.</p>
            <p style="text-align: center">' . $this->subscriptionButton() . '</p>
            <p>Regards,<br>' . setting_item('site_title') . '</p>';
        return $body;
    }

    public function subscriptionButton()
    {
        $link = route('subscription');
        $button = '<a style="border-radius: 3px;
                color: #fff;
                display: inline-block;
                text-decoration: none;
                background-color: #3490dc;
                border-top: 10px solid #3490dc;
                border-right: 18px solid #3490dc;
                border-bottom: 10px solid #3490dc;
                border-left: 18px solid #3490dc;" href="' . $link . '">Renew Subscription</a>';
        return $button;
    }
}
