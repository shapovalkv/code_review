<?php

namespace Modules\Marketplace\Listeners;

use App\User;
use Illuminate\Support\Facades\Mail;
use Modules\Marketplace\Emails\MarketplaceExpiredEmail;
use Modules\Marketplace\Events\AutomaticMarketplace5daysExpiration;
use Modules\Marketplace\Events\AutomaticMarketplaceExpiration;

class SendMailMarketplace5DayExpiration
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    const CODE = [
        'first_name' => '[first_name]',
        'last_name' => '[last_name]',
        'Marketplace_title' => '[Marketplace_title]',
        'loginButton' => '[login_button]',
    ];
    public $row;

    public function handle(AutomaticMarketplace5daysExpiration $event)
    {
        $row = $event->row;
        $user = User::find($row->company->owner_id ?? $row->author_id);
        if(!empty($user) && !empty($user->email)) {
            $data = [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'Marketplace_title' => $row->title ?? '',
            ];
            if($user->locale){
                $old = app()->getLocale();
                app()->setLocale($user->locale);
            }

            if (!empty(setting_item('user_content_email_expired_marketplace'))) {
                $body = $this->replaceContentEmail($data, setting_item_with_lang('user_content_email_expired_marketplace', app()->getLocale()));
            } else {
                $body = $this->replaceContentEmail($data, $this->defaultBody());
            }
            Mail::to($user->email)->send(new MarketplaceExpiredEmail($body));

            if(!empty($old)){
                app()->setLocale($old);
            }
        }
    }

    public function replaceContentEmail($data, $content)
    {
        if (!empty($content)) {
            foreach (self::CODE as $item => $value) {
                if($item == "loginButton") {
                    $content = str_replace($value, $this->loginButton(), $content);
                }

                $content = str_replace($value, @$data[$item], $content);
            }
        }
        return $content;
    }

    public function defaultBody()
    {
        $body = '
            <h1>Hello [first_name] [last_name]!</h1>
            <p>Your Announcement post [Marketplace_title] will expire in 5 days.</p>
            <p>Please login to your dashboard to check your Announcement.</p>
            <p style="text-align: center">' . $this->loginButton() . '</p>
            <p>Regards,<br>' . setting_item('site_title') . '</p>';
        return $body;
    }

    public function loginButton()
    {
        $link = route('marketplace.vendor.index');
        $button = '<a style="border-radius: 3px;
                color: #fff;
                display: inline-block;
                text-decoration: none;
                background-color: #3490dc;
                border-top: 10px solid #3490dc;
                border-right: 18px solid #3490dc;
                border-bottom: 10px solid #3490dc;
                border-left: 18px solid #3490dc;" href="' . $link . '">Your Announcement</a>';
        return $button;
    }

}
