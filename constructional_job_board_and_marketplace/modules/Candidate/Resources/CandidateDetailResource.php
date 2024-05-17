<?php

namespace Modules\Candidate\Resources;

use App\Resources\LocationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;


class CandidateDetailResource extends JsonResource
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
            'type' => $this->type,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'bio' => $this->user->bio,
                'created_at' => $this->user->created_at,
                'birthday' => $this->user->birthday,
                'avatar' => $this->user->getAvatarUrl(),
                'online' => $this->user->isUserOnline(),
            ],
            'cv_link' => ($cv = $this->user->candidate->cvs->where('is_default', 1)->first()) ? asset('uploads/'.$cv->media->file_path) : null,
            'title' => $this->title,
            'gender' => $this->gender,
            'languages' => $this->languages,
            'website' => $this->website,
            'seniority_level' => $this->seniority_level,
            'education_level' => $this->education_level,
            'expected_salary' => $this->expected_salary_min.($this->expected_salary_max ? " - ".$this->expected_salary_max : ''),
            'salary_type' => $this->salary_type,
            'education' => $this->education,
            'experience' => $this->experience,
            'experience_year' => $this->experience_year,
            'award' => $this->award,
            // todo Social links content commented for candidates
            //'social_media' => $this->social_media,
            'gallery' => [
                'video' => $this->video ? [$this->video] : array(),
                'images' => $this->getGallery(true)
            ],
            'skills' => $this->skills ? $this->skills->map(function ($skills) {
                return $skills->only(['id', 'name', 'slug']);
            }) : null,
            'location' => new LocationResource($this->location),
            'job_type' => new JobTypeResource($this->jobType),
            'category' => DetailCategoryResource::collection($this->categories),
            'url' => $this->getDetailUrl(),
            'wish_list' => Auth::check() ? Auth::user()->hasWishList($this->type, $this->id)->exists() : false,
        ];
    }
}
