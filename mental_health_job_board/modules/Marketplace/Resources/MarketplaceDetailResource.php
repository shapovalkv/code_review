<?php

namespace Modules\Marketplace\Resources;

use App\Resources\LocationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;


class MarketplaceDetailResource extends JsonResource
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
            'content' => $this->content,
            'is_featured' => $this->is_featured,
            'wish_list' => Auth::check() ? Auth::user()->hasWishList($this->type, $this->id)->exists() : false,
            'created_at' => $this->created_at,
            'url' => $this->getDetailUrl(),
            'image_url' => $this->getImageUrl(),
            'price' => $this->price,
            'gallery' => [
                'images' => $this->getGallery(true),
                'video' => $this->video_url ? [$this->video_url] : null,
            ],
            'location' => new LocationResource($this->location),
            'company' => new CompanyResource($this->company),
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
