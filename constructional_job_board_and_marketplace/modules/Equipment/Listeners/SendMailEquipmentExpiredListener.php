<?php

namespace Modules\Equipment\Listeners;

use App\User;
use Illuminate\Support\Facades\Mail;
use Modules\Equipment\Emails\EquipmentExpiredEmail;
use Modules\Equipment\Events\AutomaticEquipmentExpiration;

class SendMailEquipmentExpiredListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    const CODE = [
        'first_name' => '[first_name]',
        'last_name' => '[last_name]',
        'equipment_title' => '[equipment_title]',
        'loginButton' => '[login_button]',
    ];
    public $row;

    public function handle(AutomaticEquipmentExpiration $event)
    {
        $row = $event->row;
        $user = User::find($row->company->owner_id ?? $row->author_id);
        if(!empty($user) && !empty($user->email)) {
            $data = [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'equipment_title' => $row->title ?? '',
            ];
            if($user->locale){
                $old = app()->getLocale();
                app()->setLocale($user->locale);
            }

            if (!empty(setting_item('user_content_email_expired_equipment'))) {
                $body = $this->replaceContentEmail($data, setting_item_with_lang('user_content_email_expired_equipment', app()->getLocale()));
            } else {
                $body = $this->replaceContentEmail($data, $this->defaultBody());
            }
            Mail::to($user->email)->send(new EquipmentExpiredEmail($body));

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
            <p>Your equipment post [equipment_title] has been expired.</p>
            <p>Please login to your dashboard to renew your listing.</p>
            <p style="text-align: center">' . $this->loginButton() . '</p>
            <p>Regards,<br>' . setting_item('site_title') . '</p>';
        return $body;
    }

    public function loginButton()
    {
        $link = route('seller.all.equipments');
        $button = '<a style="border-radius: 3px;
                color: #fff;
                display: inline-block;
                text-decoration: none;
                background-color: #3490dc;
                border-top: 10px solid #3490dc;
                border-right: 18px solid #3490dc;
                border-bottom: 10px solid #3490dc;
                border-left: 18px solid #3490dc;" href="' . $link . '">Your Equipment</a>';
        return $button;
    }

}
