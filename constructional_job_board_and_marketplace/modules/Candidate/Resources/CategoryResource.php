<?php

namespace Modules\Candidate\Resources;

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
            'open_candidate_count' => $this->openCandidates,
            'children' => $this->children->map(function ($children) {
                $children->open_candidate_count = $children->openCandidates()->count();
                return $children->only(['id', 'name', 'slug', 'open_candidate_count']);
            }),
        ];
    }
}
