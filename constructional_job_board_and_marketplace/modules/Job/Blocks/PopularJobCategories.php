<?php

namespace Modules\Job\Blocks;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Modules\Job\Models\JobCategory;
use Modules\Media\Helpers\FileHelper;
use Modules\Template\Blocks\BaseBlock;

class PopularJobCategories extends BaseBlock
{
    function __construct()
    {
        $this->setOptions([
            'settings' => [
                [
                    'id' => 'title',
                    'type' => 'input',
                    'inputType' => 'text',
                    'label' => __("Title")
                ],
            ],
            'category' => __("Job Blocks")
        ]);
    }

    public function getName()
    {
        return __('Popular Job Categories');
    }

    public function content($model = [])
    {
        $model = block_attrs([
            'title' => '',
        ], $model);


        if (!empty($model['job_categories'])) {
            $model['job_categories'] = JobCategory::with('openJobs')->whereIn('bc_job_categories.id', $model['job_categories'])->take(1000)->get();
        }

        return view("Job::frontend.layouts.blocks.job-categories", $model);
    }

    public function contentAPI($model = [])
    {
        $model = block_attrs([
            'title' => '',
        ], $model);

        $model['items'] = JobCategory::query()
            ->whereNull('parent_id')
            ->withCount('openJobs')
            ->orderBy('name')
            ->orderByDesc('open_jobs_count')
            ->take(10)
            ->get()
            ->map(function ($categories) {
                return $categories->dataForApi();
            });

        $model['button'] = [
            'text' => 'All Job Categories',
            'url' => route('job.search')
        ];

        return $model;
    }
}
