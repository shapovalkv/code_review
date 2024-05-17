<?php

namespace Modules\Job\Blocks;

use Illuminate\Support\Facades\DB;
use Modules\Candidate\Models\Category;
use Modules\Job\Models\Job;
use Modules\Template\Blocks\BaseBlock;

class FeaturedJobs extends BaseBlock
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
        return __('Featured Jobs');
    }

    public function content($model = [])
    {
        $model = block_attrs([
            'style' => 'style_1',
            'title' => '',
            'sub_title' => '',
            'job_categories' => '',
            'number' => 6,
            'order' => 'id',
            'order_by' => 'desc',
            'load_more_url' => '',
            'ids' => []
        ], $model);

        $style = $model['style'] ? $model['style'] : 'style_1';

        if ($style == 'style_4') {
            $model['rows'] = $this->query($model);
        } else {
            $model['rows'] = $this->query($model, false);
        }
        $model['tabs'] = $this->query($model, false);
        if (!empty($model['job_categories'])) $model['categories'] = Category::whereIn('id', $model['job_categories'])->get();

        return view("Job::frontend.layouts.blocks.jobs-list.{$style}", $model);
    }

    public function contentAPI($model = [], $user = null)
    {
        $model = block_attrs([
            'title' => '',
            'sub_title' => '',
        ], $model);

        $model['items'] = $this->query($model, false)->map(function ($jobList) use ($user) {
            return $jobList->dataForApi($user);
        });

        // Implement popular searches
        $model['popular_searches'] = ['Los Angeles', 'New-York', 'Portland'];

        $model['button'] = [
            'text' => 'All Jobs',
            'url' => route('job.search')
        ];

        return $model;
    }

    public function query($model, $all = true)
    {
        $ids = $model['ids'] ?? [];
        $model_jobs = Job::query()->select("bc_jobs.*");
        if (empty($model['order'])) $model['order'] = "id";
        if (empty($model['order_by'])) $model['order_by'] = "desc";
        if (empty($model['number'])) $model['number'] = 3;
        if (!empty($ids) && count($ids) > 0) {
            $model_jobs->whereIn('id', $ids);
        } else {
            if ($all == false) {
                if (!empty($model['job_categories']) && is_array($model['job_categories']) && count($model['job_categories']) > 0) {
                    $model_jobs->whereIn('category_id', $model['job_categories']);
                }
            }
            $model_jobs->where('expiration_date', '>=', date('Y-m-d H:s:i'));
            $model_jobs->orderBy("bc_jobs." . $model['order'], $model['order_by']);
            if ($model['order'] == 'is_featured') {
                $model_jobs->orderBy("bc_jobs.id", $model['order_by']);
            }
        }
        $model_jobs->where("bc_jobs.status", "publish");

        $model_jobs->groupBy("bc_jobs.id");

        if (!empty($ids) && count($ids) > 0) {
            $imploded_strings = implode("','", $ids);
            return $model_jobs->limit(50)->orderByRaw(DB::raw("FIELD(id, '$imploded_strings')"))->get();
        } else {
            return $model_jobs->limit($model['number'])->get();
        }
    }
}
