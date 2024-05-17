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
            'id' => $row->id,
            'event' => 'AutomaticJobExpiration',
            'to' => 'employer',
            'name' => $user->display_name ?? '',
            'avatar' => '',
            'link' => route("user.manage.jobs"),
            'type' => 'job_expiration',
            'message' => __('Your job ":job" post has expired. You can renew a job via Job Manager.', ['job' => $row->title ?? ''])
        ];

        $user->notify(new PrivateChannelServices($data));
    }
}
