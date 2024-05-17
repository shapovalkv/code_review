<?php

namespace Modules\Job\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Modules\Job\Models\Job;


class JobApplicantsResource extends JsonResource
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
            'candidate_id' => $this->candidateInfo->user->id,
            'name' => $this->candidateInfo->user->name,
            'avatar' => $this->candidateInfo->user->getAvatarUrl(),
            'cv_link' => $this->cvInfo ? asset('uploads/'.$this->cvInfo->media->file_path) : null,
            'title' => $this->jobInfo ? $this->jobInfo->title : null,
            'url' => $this->candidateInfo->getDetailUrl(),
            'status' => $this->status,
            'date' => $this->created_at,
            'company' => $this->company ? [
                'name' => $this->company->name,
                'avatar_url' => $this->company->avatar_url,
                'url' => $this->company->getDetailUrl(),
            ] : null,
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
