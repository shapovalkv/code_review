<?php

namespace Modules\User\Resources\WishList;

use App\Resources\LocationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;


class EquipmentListResource extends JsonResource
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
            'id' => $this->equipment->id,
            'type' => $this->equipment->type,
            'title' => $this->equipment->title,
            'slug' => $this->equipment->slug,
            'author_id' => $this->equipment->author->id,
            'url' => $this->equipment->getDetailUrl(),
            'image_url' => $this->equipment->getImageUrl(),
            'price' => $this->equipment->price,
            'wage_agreement' => $this->equipment->wage_agreement,
            'is_featured' => $this->equipment->is_featured,
            'wish_list' => Auth::check() ? Auth::user()->hasWishList($this->equipment->type, $this->equipment->id)->exists() : false,
            'location' => new LocationResource($this->equipment->location),
            'company' => $this->equipment->company ? [
                'name' => $this->equipment->company->name,
                'avatar_url' => $this->equipment->company->avatar_url,
                'url' => $this->equipment->company->getDetailUrl(),
            ] : null,
            'category' => $this->equipment->equipmentCategory ? [
                'id' => $this->equipment->equipmentCategory->id,
                'name' => $this->equipment->equipmentCategory->name,
                'ancestors' => $this->equipment->equipmentCategory->ancestors->map(function ($ancestors) {
                    return $ancestors->only(['id', 'name']);
                }),
            ] : null,
        ];
    }
}
