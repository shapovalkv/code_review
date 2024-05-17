<?php

namespace Modules\User\Models\Chat\Traits\Relationship;

use Modules\User\Models\Chat\Message;
use Modules\User\Models\Chat\MessageNotification;

trait ConversationRelationship
{
    public function notifications()
    {
        return $this->hasManyThrough(MessageNotification::class, Message::class);
    }

    public function unseenNotifications()
    {
        return $this->notifications()->where('is_seen', 0);
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'conversation_id');
    }

    public function abuseReports()
    {
        return $this->morphMany(Abuse::class, 'entity');
    }
}
