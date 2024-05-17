<?php

namespace App\Http\Chat\Livewire;

use App\Http\Chat\Services\Chat\MessageNotificationService;
use Livewire\Component;

class UnreadMessagesCounter extends Component
{
    protected MessageNotificationService $messageNotificationService;

    public $userId = null;
    public $count = 0;

    public array $chatIds = [];

    public function __construct()
    {
        parent::__construct();
        $this->messageNotificationService = resolve(MessageNotificationService::class);
    }

    public function mount()
    {
        $this->recalculateCount();
    }

    public function recalculateCount()
    {
        $counts = $this->messageNotificationService->unreadMessagesByMessageable($this->userId);

        $this->count = $counts->sum();

        foreach ($counts as $id => $count) {
            $this->emit("updateUnreadCounter:$id", $count);
        }
    }

    public function render()
    {
        return view('livewire.unread-messages-counter');
    }
}
