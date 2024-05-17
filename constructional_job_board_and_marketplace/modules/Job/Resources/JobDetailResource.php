<?php

namespace Modules\Job\Resources;

use App\Resources\LocationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;


class JobDetailResource extends JsonResource
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
            'title' => $this->title,
            'content' => $this->content,
            'is_featured' => $this->is_featured,
            'experience' => $this->experience,
            'is_user_applied' => (bool)$this->candidates->where('job_id', $this->id)->where('candidate_id', Auth::id())->first(),
            'wish_list' => Auth::check() ? Auth::user()->hasWishList($this->type, $this->id)->exists() : false,
            'created_at' => $this->created_at,
            'url' => $this->getDetailUrl(),
            'hours' => $this->hours,
            'hours_type' => $this->hours_type,
            'salary_type' => $this->salary_type,
            'salary_min' => $this->salary_min,
            'salary_max' => $this->salary_max,
            'wage_agreement' => $this->wage_agreement,
            'gallery' => [
                'images' =>  $this->company->getGallery(true),
                'video' => $this->company && $this->company->video_url ? [$this->company->video_url] : array(),
            ],
            'skills' => $this->skills->map(function ($skills) {
                return $skills->only(['id', 'name']);
            }),
            'location' => new LocationResource($this->location),
            'company' => new CompanyResource($this->company),
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
        ];
    }
}
