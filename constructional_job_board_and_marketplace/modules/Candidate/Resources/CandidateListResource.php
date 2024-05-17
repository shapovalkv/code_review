<?php

namespace Modules\Candidate\Resources;

use App\Resources\LocationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;


class CandidateListResource extends JsonResource
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
            'wishlist' => $this->wishlist,
            'experience_year' => $this->experience_year,
            'location' => new LocationResource($this->location),
            'type' => $this->type,
            'languages' => $this->languages,
            'salary_type' => $this->salary_type,
            'expected_salary' => $this->expected_salary_min.($this->expected_salary_max ? " - ".$this->expected_salary_max : ''),
            'category' => CategoryResource::collection($this->categories),
            'last_work_place' => $this->experience ? collect($this->experience)->sortByDesc('to')->first()['location'] : null,
            'author' => [
                'display_name' => $this->getAuthor->getDisplayName(),
                'avatar_url' => $this->getAuthor->getAvatarUrl(),
                'old' => !empty($this->getAuthor->birthday) ? $this->getAuthor->getUserOld() : null,
                'created_at' => $this->getAuthor->created_at,
                'online' => $this->user->isUserOnline(),
            ],
            'url' => $this->getDetailUrl(),
            'wish_list' => Auth::check() ? Auth::user()->hasWishList($this->type, $this->id)->exists() : false,
        ];
    }
}
