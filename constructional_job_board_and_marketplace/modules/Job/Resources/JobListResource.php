<?php

namespace Modules\Job\Resources;

use App\Resources\LocationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;


class JobListResource extends JsonResource
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
            'url' => $this->getDetailUrl(),
            'hours' => $this->hours,
            'experience' => $this->experience,
            'hours_type' => $this->hours_type,
            'salary_type' => $this->salary_type,
            'salary_min' => $this->salary_min,
            'salary_max' => $this->salary_max,
            'wage_agreement' => $this->wage_agreement,
            'is_featured' => $this->is_featured,
            'is_user_applied' => (bool)$this->candidates->where('job_id', $this->id)->where('candidate_id', Auth::id())->first(),
            'wish_list' => Auth::check() ? Auth::user()->hasWishList($this->type, $this->id)->exists() : false,
            'created_at' => $this->created_at,
            'location' => new LocationResource($this->location),
            'company' => $this->company ? [
                'id' => $this->company->id,
                'name' => $this->company->name,
                'avatar_url' => $this->company->avatar_url,
                'url' => $this->company->getDetailUrl(),
            ] : null,
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
