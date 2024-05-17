<?php

namespace Modules\User\Resources\WishList;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Modules\Candidate\Resources\CategoryResource;


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
            'id' => $this->candidate->id,
            'slug' => $this->candidate->slug,
            'title' => $this->candidate->title,
            'wishlist' => $this->candidate->wishlist,
            'experience_year' => $this->candidate->experience_year,
            'city' =>  $this->candidate->location->map_address ?
                $this->candidate->location->map_address.", <br> ".$this->candidate->location->map_city :
                '' .$this->candidate->location->map_city,
            'type' => $this->candidate->type,
            'salary_type' => $this->candidate->salary_type,
            'expected_salary' => $this->candidate->expected_salary_min.($this->candidate->expected_salary_min ? " - ".$this->candidate->expected_salary_max : ''),
            'category' => CategoryResource::collection($this->candidate->categories),
            'last_work_place' => $this->candidate->experience ? collect($this->candidate->experience)->sortByDesc('to')->first()['location'] : null,
            'author' => [
                'display_name' => $this->candidate->getAuthor->getDisplayName(),
                'avatar_url' => $this->candidate->getAuthor->getAvatarUrl(),
                'old' => !empty($this->candidate->getAuthor->birthday) ? $this->candidate->getAuthor->getUserOld() : null,
                'created_at' => $this->candidate->getAuthor->created_at,
                'online' => $this->candidate->user->isUserOnline(),
            ],
            'url' => $this->candidate->getDetailUrl(),
            'wish_list' => Auth::check() ? Auth::user()->hasWishList($this->candidate->type, $this->candidate->id)->exists() : false,
        ];
    }
}
