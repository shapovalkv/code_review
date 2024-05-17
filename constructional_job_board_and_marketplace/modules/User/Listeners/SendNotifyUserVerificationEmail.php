<?php

namespace Modules\User\Listeners;

use App\Notifications\PrivateChannelServices;
use Modules\Core\Models\Notification;
use Modules\User\Events\SendMailUserRegistered;

class SendNotifyUserVerificationEmail
{

    /**
     * Handle the event.
     *
     * @param $event SendMailUserRegistered
     */
    public function handle(SendMailUserRegistered $event)
    {
        $user = $event->user;
        $data = [
            'message_type' => Notification::SYSTEM_NOTIFICATION,
            'id' => $user->id,
            'event' => 'SendMailUserRegistered',
            'to' => 'customer',
            'name' => $user->display_name,
            'type' => 'user_verification_email',
            'message' => __('Hi, :name! Please confirm your email in order to complete registration with constructional_job_board_and_marketplace.', ['name' => $user->display_name])
        ];

        $user->notify(new PrivateChannelServices($data));

    }

}
