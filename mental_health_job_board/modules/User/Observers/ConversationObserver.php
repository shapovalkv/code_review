<?php

namespace Modules\User\Observers;


use Modules\User\Models\Chat\Conversation;

class ConversationObserver
{
    public function deleting(Conversation $conversation)
    {
        $conversation->abuseReports()->delete();
    }
}
