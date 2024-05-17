<?php

namespace App\Http\Chat\Models\Chat\Traits\Relationship;

trait MessageNotificationRelationship
{
    public function messageable()
    {
        return $this->morphTo();
    }
}
