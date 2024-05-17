<?php

namespace App\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Modules\Location\Models\Location;

class PopularSearchesResource extends JsonResource
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
            'id' => $this->id,
            'module' => $this->module,
            'location' => $this->location,
            'request_count' => $this->request_count,
            'created_at' => $this->created_at,
            'location_type' => $this->location_type,
            'location_state' => $this->location_type == 'city' ? $this->location_state : Arr::first(Location::STATES, function ($value, $key) {
                return $key == $this->location_state;
            }),
        ];
    }
}
