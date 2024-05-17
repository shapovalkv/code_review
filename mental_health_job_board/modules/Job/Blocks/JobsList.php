<?php
namespace Modules\Job\Blocks;

use Illuminate\Support\Facades\DB;
use Modules\Job\Models\Job;
use Modules\Job\Models\JobCategory;
use Modules\Template\Blocks\BaseBlock;

class JobsList extends BaseBlock
{
    function __construct()
    {
        $this->setOptions([
            'settings' => [
                [
                    'id'    => 'style',
                    'type'  => 'radios',
                    'label' => __('Style'),
                    'value' => 'style_1',
                    'values' => [
                        [
                            'value'   => 'style_1',
                            'name' => __("Style 1")
                        ],
                        [
                            'value'   => 'style_2',
                            'name' => __("Style 2")
                        ],
                        [
                            'value'   => 'style_3',
                            'name' => __("Style 3")
                        ],
                        [
                            'value'   => 'style_4',
                            'name' => __("Style 4")
                        ],
                        [
                            'value'   => 'style_5',
                            'name' => __("Style 5")
                        ],
                        [
                            'value'   => 'style_6',
                            'name' => __("Style 6")
                        ],
                        [
                            'value'   => 'style_7',
                            'name' => __("Style 7")
                        ],
                        [
                            'value'   => 'style_8',
                            'name' => __("Style 8")
                        ],
                        [
                            'value'   => 'style_9',
                            'name' => __("Style 9")
                        ],
                        [
                            'value'   => 'style_10',
                            'name' => __("Style 10")
                        ],
                    ],
                ],
                [
                    'id' => 'title',
                    'type' => 'input',
                    'inputType' => 'text',
                    'label' => __("Title")
                ],
                [
                    'id' => 'sub_title',
                    'type' => 'input',
                    'inputType' => 'text',
                    'label' => __("Sub Title")
                ],
                [
                    'id'        => 'number',
                    'type'      => 'input',
                    'inputType' => 'number',
                    'label'     => __('Number Items')
                ],
                [
                    'id'           => 'job_categories',
                    'type'         => 'select2',
                    'label'        => __('Select Job Categories'),
                    'select2'      => [
                        'ajax'     => [
                            'url'      => route('job.admin.category.getForSelect2'),
                            'dataType' => 'json'
                        ],
                        'width'    => '100%',
                        'multiple' => "true",
                    ],
                    'pre_selected' => route('job.admin.category.getForSelect2', ['pre_selected' => 1])
                ],
                [
                    'id'            => 'order',
                    'type'          => 'radios',
                    'label'         => __('Order'),
                    'values'        => [
                        [
                            'value'   => 'id',
                            'name' => __("Date Create")
                        ],
                        [
                            'value'   => 'title',
                            'name' => __("Title")
                        ],
                        [
                            'value'   => 'is_featured',
                            'name' => __("Featured")
                        ],
                    ]
                ],
                [
                    'id'            => 'order_by',
                    'type'          => 'radios',
                    'label'         => __('Order By'),
                    'values'        => [
                        [
                            'value'   => 'asc',
                            'name' => __("ASC")
                        ],
                        [
                            'value'   => 'desc',
                            'name' => __("DESC")
                        ],
                    ]
                ],
                [
                    'id' => 'load_more_url',
                    'type' => 'input',
                    'inputType' => 'text',
                    'label' => __("Load More Url")
                ],
                [
                    'id'      => 'ids',
                    'type'    => 'select2',
                    'label'   => __('Or Filter by Ids'),
                    'select2' => [
                        'ajax'  => [
                            'url'      => route('job.admin.getForSelect2'),
                            'dataType' => 'json'
                        ],
                        'width' => '100%',
                        'allowClear' => 'true',
                        'placeholder' => __('-- Select --'),
                        'multiple' => "true",
                    ],
                    'pre_selected'=> route('job.admin.getForSelect2', ['pre_selected' => 1])
                ]
            ],
            'category'=>__("Job Blocks")
        ]);
    }

    public function getName()
    {
        return __('Jobs List');
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
            'load_more_url' => route('job.search'),
            'ids'=>[]
        ], $model);

        $model['load_more_url'] = route('job.search');

        $style = $model['style'] ? $model['style'] : 'style_1';

        if($style == 'style_4'){
            $model['rows'] = $this->query($model);
        }else {
            $model['rows'] = $this->query($model, false);
        }
        $model['tabs'] = $this->query($model,false);
        if (!empty($model['job_categories'])) $model['categories'] = JobCategory::whereIn('id',$model['job_categories'])->get();

        return view("Job::frontend.layouts.blocks.jobs-list.{$style}", $model);
    }

    public function contentAPI($model = []){

    }

    public function query($model,$all = true){
        $ids = $model['ids'] ?? [];
        $model_jobs = Job::with(['translations', 'location', 'category', 'company', 'jobType'])->select("bc_jobs.*")->where('is_featured', 1);
        if(empty($model['order'])) $model['order'] = "id";
        if(empty($model['order_by'])) $model['order_by'] = "desc";
        if(empty($model['number'])) $model['number'] = 6;
        if(!empty($ids) && count($ids) > 0)
        {
            $model_jobs->whereIn('id',$ids);
        }else{
            if ($all == false){
                if (!empty($model['job_categories']) && is_array($model['job_categories']) && count($model['job_categories']) > 0) {
                    $model_jobs->whereIn('category_id', $model['job_categories']);
                }
            }
            $model_jobs->whereDate('expiration_date', '>=',  date('Y-m-d'));
            $model_jobs->orderBy("bc_jobs.".$model['order'], $model['order_by']);
            if($model['order'] == 'is_featured'){
                $model_jobs->orderBy("bc_jobs.id", $model['order_by']);
            }
        }
        if(setting_item("job_need_approve")) {
            $model_jobs->where("bc_jobs.is_approved", "approved");
        }
        $model_jobs->where("bc_jobs.status", "publish");

        $model_jobs->groupBy("bc_jobs.id");

        if(!empty($ids) && count($ids) > 0){
            $imploded_strings = implode("','", $ids);
            return $model_jobs->limit(50)->orderByRaw(DB::raw("FIELD(id, '$imploded_strings')"))->get();
        }else{
            return $model_jobs->limit($model['number'])->get();
        }
    }
}
