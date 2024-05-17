<?php

namespace Modules\Job\Resources;

use App\Resources\LocationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class JobManageResource  extends JsonResource
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
            'title' => $this->title,
            'url' => $this->getDetailUrl(),
            'visitors' => views($this->resource)->unique()->count(),
            'applicants' => $this->candidates->count(),
            'is_featured' => $this->is_featured,
            'salary_min' => $this->salary_min,
            'salary_max' => $this->salary_max,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'expiration_date' => $this->expiration_date,
            'location' => new LocationResource($this->location),
            'company' => $this->company ? [
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
        ];
    }
}
