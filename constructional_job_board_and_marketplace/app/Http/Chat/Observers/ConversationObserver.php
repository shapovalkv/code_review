<?php

namespace App\Http\Chat\Observers;

use App\Http\Chat\Models\Chat\Conversation;

class ConversationObserver
{
    public function deleting(Conversation $conversation)
    {
        $conversation->abuseReports()->delete();
    }
}
