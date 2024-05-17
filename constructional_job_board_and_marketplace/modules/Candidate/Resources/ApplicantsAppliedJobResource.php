<?php

namespace Modules\Candidate\Resources;

use App\Resources\LocationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class ApplicantsAppliedJobResource extends JsonResource
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
            'id_job_application' => $this->id, // This is Job Application Id
            'id' => $this->jobInfo->id, // This is Job Id
            'author_id' => $this->jobInfo->company->owner_id,
            'salary_min' => $this->jobInfo->salary_min,
            'salary_max' => $this->jobInfo->salary_max,
            'title' => $this->jobInfo->title,
            'url' => $this->jobInfo->getDetailUrl(),
            'status' => $this->status,
            'date' => $this->created_at,
            'company' => $this->jobInfo->company ? [
                'name' => $this->jobInfo->company->name,
                'url' => $this->jobInfo->company->getDetailUrl(),
            ] : null,
            'location' => new LocationResource($this->jobInfo->location),
            'category' => $this->jobInfo->category ? [
                'id' => $this->jobInfo->category->id,
                'name' => $this->jobInfo->category->name,
                'ancestors' => $this->jobInfo->category->ancestors->map(function ($ancestors) {
                    return $ancestors->only(['id', 'name']);
                }),
            ] : null,
        ];
    }
}
