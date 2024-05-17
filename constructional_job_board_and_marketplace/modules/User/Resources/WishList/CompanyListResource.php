<?php

namespace Modules\User\Resources\WishList;

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
            'id' => $this->company->id,
            'type' => $this->company->type,
            'name' => $this->company->name,
            'author_id' => $this->company->owner_id,
            'url' => $this->company->getDetailUrl(),
            'image_url' => $this->company->getImageUrl(),
            'location' => new LocationResource($this->company->location),
            'open_jobs' => $this->company->jobs()->count(),
            'category' => $this->company->category ? [
                'id' => $this->company->category->id,
                'name' => $this->company->category->name,
                'ancestors' => $this->company->category->children->map(function ($children) {
                    return $children->only(['id', 'name']);
                }),
            ] : null,
            'wish_list' => Auth::check() ? Auth::user()->hasWishList($this->company->type, $this->company->id)->exists() : false,
        ];
    }
}
