<?php

namespace App\Livewire;

use Modules\User\Services\Chat\MessageNotificationService;
use Livewire\Component;

class UnreadMessagesByChatCounter extends Component
{
    protected MessageNotificationService $messageNotificationService;

    public $userId = null;
    public $chatId = null;
    public int $count = 0;

    public function __construct()
    {
        parent::__construct();
        $this->messageNotificationService = resolve(MessageNotificationService::class);
    }

    public function mount()
    {
        $this->count = $this->messageNotificationService->unreadCountByChat($this->chatId, $this->userId);
    }

    public function render()
    {
        return view('livewire.unread-messages-by-chat-counter');
    }
}
