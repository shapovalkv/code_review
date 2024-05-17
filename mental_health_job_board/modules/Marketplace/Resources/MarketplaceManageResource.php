<?php

namespace Modules\Marketplace\Resources;

use App\Resources\LocationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class MarketplaceManageResource extends JsonResource
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
            'slug' => $this->slug,
            'title' => $this->title,
            'image' => $this->getImageUrl(),
            'url' => $this->getDetailUrl(),
            // Implement here contact users count
            'contacts' => 0,
            'is_featured' => $this->is_featured,
            'salary_min' => $this->price,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'expiration_date' => $this->expiration_date,
            'location' => new LocationResource($this->location),
            'company' => $this->company ? [
                'name' => $this->company->name,
                'avatar_url' => $this->company->avatar_url,
                'url' => $this->company->getDetailUrl(),
            ] : null,
            'category' => $this->MarketplaceCategory ? [
                'id' => $this->MarketplaceCategory->id,
                'name' => $this->MarketplaceCategory->name,
                'ancestors' => $this->MarketplaceCategory->ancestors->map(function ($ancestors) {
                    return $ancestors->only(['id', 'name']);
                }),
            ] : null,
        ];
    }
}
