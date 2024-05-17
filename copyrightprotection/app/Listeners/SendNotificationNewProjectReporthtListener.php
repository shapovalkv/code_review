<?php

namespace App\Listeners;

use App\Events\AgentCreatedNewReportEvent;
use App\Notifications\CustomNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendNotificationNewProjectReporthtListener
{
    /**
     * Handle the event.
     */
    public function handle(AgentCreatedNewReportEvent $event): void
    {
        $userProject = $event->userProject;
        $author = $userProject->author;

        Notification::send($author, new CustomNotification(
            __('emails.new_report_email.notificationSubject'),
            __('emails.new_report_email.notificationGreeting', ['client_first_name' => $author->first_name,]),
            __('emails.new_report_email.notificationBody', ['project_name' => $userProject->name]),
            ['text' => 'Website', 'url' => route('home')],
            __('emails.new_report_email.notificationEnd'),
            $userProject)        );
    }
}
