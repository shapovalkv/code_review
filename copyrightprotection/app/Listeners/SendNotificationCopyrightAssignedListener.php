<?php

namespace App\Listeners;

use App\Events\CopyrightAssignedEvent;
use App\Notifications\CustomNotification;
use http\Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendNotificationCopyrightAssignedListener
{
    /**
     * Handle the event.
     */
    public function handle(CopyrightAssignedEvent $event): void
    {
        $agent = $event->agent;

        $userProject = $event->userProject;

        Notification::send($agent, new CustomNotification(
            __('emails.project_assign_email.notificationSubject'),
            __('emails.project_assign_email.notificationGreeting'),
            __('emails.project_assign_email.notificationBody', ['client' => $userProject->author->first_name.' '.$userProject->author->last_name, 'project_name' => $userProject->name]),
            ['text' => '', 'url' => ''],
            __('emails.project_assign_email.notificationEnd'),
            $userProject)
        );
    }
}
