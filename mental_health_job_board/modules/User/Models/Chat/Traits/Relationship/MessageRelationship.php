<?php

namespace Modules\User\Models\Chat\Traits\Relationship;

use Modules\User\Models\Chat\Conversation;
use Modules\User\Models\Chat\MessageNotification;

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
