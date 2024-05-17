<?php

    namespace Modules\Company\Listeners;

    use App\Notifications\PrivateChannelServices;
    use Modules\Company\Events\EmployerFilledCompanyProfile;
    use Modules\Core\Models\Notification;

    class SendNotifyToEmployerSelectSubscriptionListener
    {

        public function handle(EmployerFilledCompanyProfile $event)
        {
            $row = $event->row;
            $user = $row->getAuthor ?? $row->user;
            $data = [
                'message_type' => Notification::SYSTEM_NOTIFICATION,
                'id' => $row->id,
                'event'   => 'EmployerFilledCompanyProfile',
                'to'      => 'employer',
                'name' => $user->display_name ?? '',
                'link' => route("user.plan"),
                'type' => 'select_subscription',
                'message' => __(':name, you filled your company profile. Next step, select your subscription', ['name' => $user->getDisplayName() ?? ''])
            ];

            $user->notify(new PrivateChannelServices($data));
        }
    }
