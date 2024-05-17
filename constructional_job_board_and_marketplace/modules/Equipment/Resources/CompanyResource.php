<?php

namespace Modules\Equipment\Resources;

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
            'name' => $this->name,
            'avatar_url' => $this->avatar_url,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email,
            'about' => $this->about,
            'social_media' => $this->social_media,
            'local_url' => $this->getDetailUrl(),
            'external_url' => $this->website ?? null
        ];
    }
}
