<?php

namespace App\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        $decodeData = $this->data ? json_decode($this->data) : null;
        return [
            'id' => $this->id,
            'event' => $decodeData->notification->event ?? null,
            'to' =>  $decodeData->notification->to ?? null,
            'avatar' =>  $decodeData->notification->avatar ?? null,
            'type' =>  $decodeData->notification->type ?? null,
            'link' => $decodeData->notification->link ?? null,
            'message' => $decodeData->notification->message ?? null,
            'created_at' => $this->created_at,
            'is_read' => (bool)$this->read_at,
            ];
    }
}
