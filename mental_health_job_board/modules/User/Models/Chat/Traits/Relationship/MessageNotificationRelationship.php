<?php

namespace Modules\User\Models\Chat\Traits\Relationship;

trait MessageNotificationRelationship
{
    public function messageable()
    {
        return $this->morphTo();
    }
}
