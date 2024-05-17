<?php

namespace App\Http\Chat\Models\Interfaces;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Http\Chat\Models\Chat\Conversation;

interface IsMessageable
{
    function conversations();
    function participation(): MorphMany;
    function joinConversation(Conversation $conversation);
    function leaveConversation($conversationId);
    function getKey();
    function getMorphClass();
}
