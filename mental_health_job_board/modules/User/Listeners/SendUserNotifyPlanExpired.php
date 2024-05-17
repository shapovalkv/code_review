<?php

    namespace Modules\User\Listeners;

    use App\Notifications\AdminChannelServices;
    use App\Notifications\PrivateChannelServices;
    use Modules\User\Events\NewVendorRegistered;
    use Modules\User\Events\UserPlanExpired;
    use Modules\User\Events\VendorApproved;

    class SendUserNotifyPlanExpired
    {

        public function handle(UserPlanExpired $event)
        {
            $user = $event->plan->user;
            $data = [
                'id' =>  $user->id,
                'event'   => 'UserPlanExpired',
                'to'      => 'vendor',
                'name' =>  $user->display_name,
                'avatar' =>  $user->avatar_url,
                'link' => route("subscription"),
                'type' => 'user_upgrade_request',
                'message' => __('Your Subscription Plan has expired, please renew.')
            ];

            $user->notify(new PrivateChannelServices($data));
        }
    }
