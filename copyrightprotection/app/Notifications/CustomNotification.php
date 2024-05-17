<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomNotification extends Notification
{
    use Queueable;

    public string $notificationBody;
    public array $notificationAction;
    public string $notificationEnd;
    public string $notificationSubject;
    public string $notificationGreeting;

    public $userProject;

    /**
     * Create a new notification instance.
     */
    public function __construct($notificationSubject, $notificationGreeting, $notificationBody, $notificationAction, $notificationEnd, $userProject)
    {
        $this->notificationSubject = $notificationSubject;
        $this->notificationGreeting = $notificationGreeting;
        $this->notificationBody = $notificationBody;
        $this->notificationAction = $notificationAction;
        $this->notificationEnd = $notificationEnd;
        $this->userProject = $userProject;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->notificationSubject)
            ->greeting($this->notificationGreeting)
            ->line($this->notificationBody)
            ->when(!empty($this->notificationAction['text']) && !empty($this->notificationAction['url']), function ($message) {
                return $message->action($this->notificationAction['text'], url($this->notificationAction['url']));
            })
            ->salutation($this->notificationEnd);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'user_name' => $this->userProject->author->first_name.' '.$this->userProject->author->last_name,
            'user_id' => $this->userProject->author->id,
            'project_id' => $this->userProject->id,
            'project_name' => $this->userProject->name,
            'content' => "$this->notificationBody"
        ];
    }
}
