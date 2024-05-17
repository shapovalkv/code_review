<?php

namespace Modules\User\Models\Interfaces;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\User\Models\Chat\Conversation;

interface IsMessageable
{
    function conversations();
    function participation(): MorphMany;
    function joinConversation(Conversation $conversation);
    function leaveConversation($conversationId);
    function getKey();
    function getMorphClass();
}
