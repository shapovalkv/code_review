<?php

namespace Modules\Company\Resources;

use App\Resources\LocationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;


class CompanyDetailResource extends JsonResource
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
            'about' => $this->about,
            'image_url' => $this->getImageUrl(),
            'email' => $this->email,
            'phone' => $this->phone,
            'website' => $this->website,
            'avatar_url' => $this->avatar_id ? get_file_url($this->avatar_id, 'full') : null,
            'created_at' => $this->getAuthor->created_at,
            'founded_in' => $this->founded_in,
            'team_size' => $this->companyTerm ? $this->companyTerm->map(function ($companyTerm) {
                return $companyTerm->term->name;
            }) : null,
            'url' => $this->getDetailUrl(),
            'gallery' => [
                'images' => $this->getGallery(true),
                'video' => $this->video_url ? [$this->video_url] : array(),
            ],
            'social_media' => $this->social_media,
            'job_tags' => $this->skills,
            'location' => new LocationResource($this->location),
            'category' => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'ancestors' => $this->category->ancestors->map(function ($ancestors) {
                    return $ancestors->only(['id', 'name']);
                }),
            ] : null,
            'job_type' => $this->jobType ? [
                'id' => $this->jobType->id,
                'name' => $this->jobType->name
            ] : null,
            'wish_list' => Auth::check() ? Auth::user()->hasWishList($this->type, $this->id)->exists() : false,
        ];
    }
}
