<?php

namespace Modules\Job\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Modules\Job\Models\Job;

class JobService
{
    public function getJobBuilder(?string $s = null, ?int $categoryId = null, ?int $companyId = null, ?string $orderBy = null, ?string $orderDirection = null): Builder
    {
        $builder = Job::query()->with(['location', 'category', 'company']);

        if ($categoryId) {
            $builder->where('category_id', $categoryId);
        }
        if($companyId){
            $builder->where('company_id', $companyId);
        }
        if ($s) {
            $builder->where('title', 'LIKE', '%' . $s . '%');
        }

        if ($orderBy) {
            if (str_contains($orderBy, 'bc_job_positions')) {
                $builder->join('bc_job_positions', 'bc_jobs.position_id', '=', 'bc_job_positions.id');
            } else if (str_contains($orderBy, 'bc_locations')) {
                $builder->join('bc_locations', 'bc_jobs.location_id', '=', 'bc_locations.id');
            } else if (str_contains($orderBy, 'bc_companies')) {
                $builder->select('bc_jobs.*')
                    ->join('bc_companies', 'bc_jobs.company_id', '=', 'bc_companies.id');
            } else if (str_contains($orderBy, 'bc_job_categories')) {
                $builder->join('bc_job_categories', 'bc_jobs.category_id', '=', 'bc_job_categories.id');
            }
            $builder->orderBy($orderBy, $orderDirection);
        } else {
            $builder->orderBy('id', 'desc');
        }

        return $builder;
    }

    public function fillByAttrForCreateJob(Job $job): Job
    {
        $attr = [
            'title',
            'content',
            'key_responsibilities',
            'skills_and_exp',
            'category_id',
            'thumbnail_id',
            'location_id',
            'company_id',
            'job_type_id',
            'hours',
            'hours_type',
            'salary_min',
            'salary_max',
            'salary_type',
            'gender',
            'map_lat',
            'map_lng',
            'map_zoom',
            'experience',
            'experience_type',
            'is_urgent',
            'create_user',
            'apply_type',
            'apply_link',
            'apply_email',
            'wage_agreement',
            'gallery',
            'video',
            'video_cover_id',
            'number_recruitments',
            'position_id',
            'employment_location',
        ];

        $job->fillByAttr($attr, Cache::get(auth()->id() . Job::CACHE_KEY_DRAFT));

        return $job;
    }
}
