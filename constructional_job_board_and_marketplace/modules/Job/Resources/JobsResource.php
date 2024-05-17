<?php

namespace Modules\Job\Resources;

use App\Resources\LocationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class JobsResource extends JsonResource
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
            'slug' => $this->slug,
            'hours' => $this->hours,
            'hours_type' => $this->hours_type,
            'salary_type' => $this->salary_type,
            'salary_min' => $this->salary_min,
            'salary_max' => $this->salary_max,
            'is_featured' => $this->is_featured,
            'created_at' => $this->created_at,
            'location' => new LocationResource($this->location),
            'company' => new CompanyResource($this->company),
            'user' => new UserResource($this->user),
            'category' => new CategoryResource($this->category),
        ];
    }
}
