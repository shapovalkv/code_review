<?php

namespace App\Livewire\Chat\Traits;

trait AddsMessages
{
    protected function addMessage(array $messageData)
    {
        $this->addPreparedMessage($this->messageHelper->prepareMessage($messageData));
    }

    protected function addPreparedMessage(array $messageData)
    {
        $dateKey = $messageData['date_group'];

        if (!isset($this->messagesList[$dateKey])) {
            $this->messagesList[$dateKey] = [];
        }

        $this->messagesList[$dateKey][] = $messageData;
    }
}
