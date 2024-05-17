<?php

namespace Modules\Company\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class CategoryResource extends JsonResource
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
            'open_companies_count' => $this->openCompanys()->count(),
            'children' => $this->children->map(function ($children) {
                $children->open_companies_count = $children->openCompanys()->count();
                return $children->only(['id', 'name', 'slug', 'open_companies_count']);
            }),
        ];
    }
}
