<?php

namespace App\Listeners;

use App\Events\CopyrightUnassignedEvent;
use App\Notifications\CustomNotification;
use Illuminate\Support\Facades\Notification;

class SendNotificationCopyrightUnassignedListener
{
    /**
     * Handle the event.
     */
    public function handle(CopyrightUnassignedEvent $event): void
    {
        $agent = $event->agent;

        $userProject = $event->userProject;

        Notification::send($agent, new CustomNotification(
            __('emails.project_unassign_email.notificationSubject'),
            __('emails.project_unassign_email.notificationGreeting'),
            __('emails.project_unassign_email.notificationBody', ['client' => $userProject->author->first_name.' '.$userProject->author->last_name, 'project_name' => $userProject->name]),
            ['text' => '', 'url' => ''],
            __('emails.project_unassign_email.notificationEnd'),
            $userProject)
        );
    }
}
