<?php

namespace Modules\Equipment\Resources;

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
            'id' => $this->id,
            'author_id' => $this->create_user,
            'type' => $this->type,
            'title' => $this->title,
            'slug' => $this->slug,
            'url' => $this->getDetailUrl(),
            'image_url' => $this->getImageUrl(),
            'price' => $this->price,
            'wage_agreement' => $this->wage_agreement,
            'is_featured' => $this->is_featured,
            'wish_list' => Auth::check() ? Auth::user()->hasWishList($this->type, $this->id)->exists() : false,
            'location' => new LocationResource($this->location),
            'company' => $this->company ? [
                'name' => $this->company->name,
                'avatar_url' => $this->company->avatar_url,
                'url' => $this->company->getDetailUrl(),
            ] : null,
            'category' => $this->equipmentCategory ? [
                'id' => $this->equipmentCategory->id,
                'name' => $this->equipmentCategory->name,
                'ancestors' => $this->equipmentCategory->ancestors->map(function ($ancestors) {
                    return $ancestors->only(['id', 'name']);
                }),
            ] : null,
        ];
    }
}
