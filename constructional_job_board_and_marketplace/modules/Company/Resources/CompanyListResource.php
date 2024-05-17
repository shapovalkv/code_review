<?php

namespace Modules\Company\Resources;

use App\Resources\LocationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;


class CompanyListResource extends JsonResource
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
            'author_id' => $this->owner_id,
            'type' => $this->type,
            'name' => $this->name,
            'url' => $this->getDetailUrl(),
            'image_url' => $this->getImageUrl(),
            'location' => new LocationResource($this->location),
            'open_jobs' => $this->jobs()->count(),
            'category' => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'ancestors' => $this->category->children->map(function ($children) {
                    return $children->only(['id', 'name']);
                }),
            ] : null,
            'wish_list' => Auth::check() ? Auth::user()->hasWishList($this->type, $this->id)->exists() : false,
        ];
    }
}
