<?php

namespace App\Http\Chat\Models\Chat\Traits\Attribute;

trait MessageAttribute
{
    public function getTimeAttribute()
    {
        return $this->created_at->format('h:i A');
    }
}
