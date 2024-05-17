<?php

namespace Modules\Job\Listeners;

use App\Notifications\PrivateChannelServices;
use Modules\Core\Models\Notification;
use Modules\Job\Events\AutomaticJobExpiration;

class SendNotifyJobExpiredListener
{

    public function handle(AutomaticJobExpiration $event)
    {
        $row = $event->row;
        $user = $row->company->getAuthor ?? $row->jobInfo->user;
        $data = [
            'message_type' => Notification::SYSTEM_NOTIFICATION,
            'id' => $row->id,
            'event' => 'AutomaticJobExpiration',
            'to' => 'employer',
            'name' => $user->display_name ?? '',
            'avatar' => '',
            'link' => route("user.all.jobs"),
            'type' => 'job_expiration',
            'message' => __('Your job ":job" post has been expired. You can renew the job post within', ['job' => $row->title ?? ''])
        ];

        $user->notify(new PrivateChannelServices($data));
    }
}
