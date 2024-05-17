<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Modules\User\Services\Chat\MessageNotificationService;

class NewMessageCounter extends Component
{
    public int $unreadCountMessages = 0;

    public function unreadCountMessages(MessageNotificationService $messageNotification)
    {
        $this->unreadCountMessages = $messageNotification->unreadCount(auth()->user()->id);
    }

    public function render(MessageNotificationService $messageNotification): View
    {
        return view('livewire.new-message-counter', ['unreadCountMessages' => $messageNotification->unreadCount(auth()->user()->id)]);
    }
}
