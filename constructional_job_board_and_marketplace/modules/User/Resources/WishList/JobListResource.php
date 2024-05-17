<?php

namespace Modules\User\Resources\WishList;

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
            'id' => $this->job->id,
            'type' => $this->job->type,
            'title' => $this->job->title,
            'author_id' => $this->job->company->owner_id,
            'url' => $this->job->getDetailUrl(),
            'hours' => $this->job->hours,
            'hours_type' => $this->job->hours_type,
            'salary_type' => $this->job->salary_type,
            'salary_min' => $this->job->salary_min,
            'salary_max' => $this->job->salary_max,
            'wage_agreement' => $this->job->wage_agreement,
            'is_featured' => $this->job->is_featured,
            'wish_list' => Auth::check() ? Auth::user()->hasWishList($this->job->type, $this->job->id)->exists() : false,
            'created_at' => $this->job->created_at,
            'location' => new LocationResource($this->job->location),
            'company' => $this->job->company ? [
                'name' => $this->job->company->name,
                'avatar_url' => $this->job->company->avatar_url,
                'url' => $this->job->company->getDetailUrl(),
            ] : null,
            'category' => $this->job->category ? [
                'id' => $this->job->category->id,
                'name' => $this->job->category->name,
                'ancestors' => $this->job->category->ancestors->map(function ($ancestors) {
                    return $ancestors->only(['id', 'name']);
                }),
            ] : null,
            'job_type' => $this->job->jobType ? [
                'id' => $this->job->jobType->id,
                'name' => $this->job->jobType->name
            ] : null,
        ];
    }
}
