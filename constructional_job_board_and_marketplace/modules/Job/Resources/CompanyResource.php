<?php

namespace Modules\Job\Resources;

use App\Resources\LocationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class CompanyResource extends JsonResource
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
            'name' => $this->name,
            'avatar_url' => $this->avatar_url,
            'phone' => $this->phone,
            'email' => $this->email,
            'about' => $this->about,
            'social_media' => $this->social_media,
            'local_url' => $this->getDetailUrl(),
            'external_url' => $this->website ?? null,
            'location' => new LocationResource($this->location),
        ];
    }
}
