<?php

namespace App\Http\Chat\Models\Chat\Traits\Relationship;

use App\Http\Chat\Models\Chat\Conversation;
use App\Http\Chat\Models\Chat\MessageNotification;

trait MessageRelationship
{
    public function conversation()
    {
        return $this->belongsTo(Conversation::class, 'conversation_id');
    }

    public function unreadMessages()
    {
        return $this->notifications()->where('is_seen', 0);
    }

    public function notifications()
    {
        return $this->hasOne(MessageNotification::class)
            ->where('message_id', $this->getKey())
            ->where('messageable_type', $this->participation->getMorphClass());
    }
}
