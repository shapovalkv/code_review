<?php

    namespace Modules\Contact\Listeners;

    use App\Notifications\AdminChannelServices;
    use Illuminate\Support\Facades\Notification;
    use Modules\Contact\Events\UserSentHelpMessageEvent;
    use Modules\User\Models\User;

    class SendNotifyHelpMessage
    {

        public function handle(UserSentHelpMessageEvent $event)
        {
            $data = [
                'id' =>  $event->row->id,
                'event'=>'UserSentHelpMessageEvent',
                'to'=>'admin',
                'name' =>  $event->row->name,
                'link' => route('contact.admin.index'),
                'type' => 'user',
                'message' => __('You received new help message from :user', ['user' => $event->row->name])
            ];

            Notification::send(User::query()->where('role_id', 1)->where('status', 'publish')->get(), new AdminChannelServices($data));
        }
    }
