<?php

namespace Modules\Candidate\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class DetailCategoryResource extends JsonResource
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
            'slug' => $this->slug,
            'ancestors' => $this->ancestors->map(function ($ancestors) {
                return $ancestors->only(['id', 'name', 'slug']);
            }),
        ];
    }
}
