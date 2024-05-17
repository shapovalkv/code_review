<?php

namespace App\Listeners;

use App\Events\NewCopyrightEvent;
use App\Models\User;
use App\Notifications\CustomNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendNotificationNewCopyrightListener
{
    /**
     * Handle the event.
     */
    public function handle(NewCopyrightEvent $event): void
    {
        $admins = User::role('super_admin')->get();

        $author = $event->userProject->author;
        $userProject = $event->userProject;

        Notification::send($admins, new CustomNotification(
            __('emails.new_project_email.notificationSubject'),
            __('emails.new_project_email.notificationGreeting'),
            __('emails.new_project_email.notificationBody', ['user' =>$author->first_name.' '.$author->last_name, 'project_name' => $userProject->name]),
            ['text' => '', 'url' => ''],
            __('emails.new_project_email.notificationEnd'),
            $userProject)
        );
    }
}
