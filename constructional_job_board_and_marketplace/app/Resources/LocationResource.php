<?php

namespace App\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'map_location' => $this->map_location,
            'map_state' => $this->map_state,
            'map_address' => $this->map_address,
            'map_city' => $this->map_city,
            'slug' => $this->slug,
            'map_lat' => $this->map_lat,
            'map_lng' => $this->map_lng,
            'map_zoom' => $this->map_zoom,
            'map_state_long' => $this->map_state_long,
        ];
    }
}
