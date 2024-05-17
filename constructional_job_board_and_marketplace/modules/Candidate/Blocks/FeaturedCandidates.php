<?php

namespace Modules\Candidate\Blocks;

use Illuminate\Support\Facades\DB;
use Modules\Media\Helpers\FileHelper;
use Modules\Template\Blocks\BaseBlock;
use Modules\Candidate\Models\Candidate;
use Modules\Candidate\Models\Category;

class FeaturedCandidates extends BaseBlock
{
    function __construct()
    {
        $this->setOptions([
            'settings' => [
                [
                    'id' => 'title',
                    'type' => 'input',
                    'inputType' => 'text',
                    'label' => __('Title')
                ],
                [
                    'id' => 'sub_title',
                    'type' => 'input',
                    'inputType' => 'text',
                    'label' => __('Sub Title')
                ],
            ],
            'category' => __("Featured candidates Blocks")
        ]);
    }

    public function getName()
    {
        return __('Featured candidates');
    }

    public function content($model = [])
    {
        $list = $this->query($model);
        $data = [
            'rows' => $list,
            'title' => $model['title'] ?? "",
            'desc' => $model['desc'] ?? "",
            'load_more_url' => $model['load_more_url'] ?? "",
            'load_more_name' => $model['load_more_name'] ?? "",
        ];

        return view("Candidate::frontend.blocks.list-candidates", $data);
    }

    public function contentAPI($model = [], $user = null)
    {

        $model = block_attrs([
            'title' => '',
            'sub_title' => '',
        ], $model);

        $rows = $this->query($model);
        $model['items'] = $rows->map(function ($row) use ($user) {
            return $row->dataForApi($user);
        });

        $model['button'] = [
            'text' => 'All Candidate List',
            'url' => route('candidate.index')
        ];

        return $model;
    }

    public function query($model)
    {
        $ids = $model['ids'] ?? [];
        $model_candidates = Candidate::select("bc_candidates.*")->with(['translations', 'categories', 'skills', 'user']);
        if (empty($model['order'])) $model['order'] = "id";
        if (empty($model['order_by'])) $model['order_by'] = "desc";
        if (empty($model['number'])) $model['number'] = 5;
        if (!empty($ids) && count($ids) > 0) {
            $model_candidates->whereIn('id', $ids);
        } else {

            if (!empty($model['category_id'])) {
                $category_ids = is_array($model['category_id']) ? $model['category_id'] : [$model['category_id']];
                $list_cat = Category::query()->whereIn('id', $category_ids)->where("status", "publish")->pluck('id');
                if (!empty($list_cat)) {
                    $list_cat->toArray();
                    $model_candidates
                        ->join('bc_candidate_categories', function ($join) use ($list_cat) {
                            $join->on('bc_candidate_categories.origin_id', '=', 'bc_candidates.id')
                                ->whereIn("bc_candidate_categories.cat_id", $list_cat);
                        });
                }
            }
            $model_candidates->orderBy("bc_candidates." . $model['order'], $model['order_by']);
        }
        $model_candidates->where("bc_candidates.allow_search", "publish");
        $model_candidates->groupBy("bc_candidates.id");
        if (!empty($ids) && count($ids) > 0) {
            $imploded_strings = implode("','", $ids);
            return $model_candidates->limit(50)->orderByRaw(DB::raw("FIELD(id, '$imploded_strings')"))->get();
        } else {
            return $model_candidates->limit($model['number'])->get();
        }

    }
}
