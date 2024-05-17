<?php

namespace Modules\Equipment\Blocks;

use Illuminate\Support\Facades\DB;
use Modules\Candidate\Models\Category;
use Modules\Equipment\Models\Equipment;
use Modules\Template\Blocks\BaseBlock;

class EquipmentForSale extends BaseBlock
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
            'category' => __("Equipment Blocks")
        ]);
    }

    public function getName()
    {
        return __('Equipment for sale');
    }

    public function content($model = [])
    {
        $model = block_attrs([
            'style' => 'style_1',
            'title' => '',
            'sub_title' => '',
            'equipment_categories' => '',
            'number' => 7,
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
        if (!empty($model['equipment_categories'])) $model['categories'] = Category::whereIn('id', $model['equipment_categories'])->get();

        return view("Equipment::frontend.layouts.blocks.Equipments-list.{$style}", $model);
    }

    public function contentAPI($model = [], $user = null)
    {
        $model = block_attrs([
            'title' => '',
        ], $model);

        if (empty($model['title'])) $model['title'] = 'Equipment for sale';

        $model['items'] = $this->query($model, false)->map(function ($equipment) use ($user) {
            return $equipment->dataForApi($user);
        });

        $model['button'] = [
            'text' => 'All Equipment',
            'url' => route('equipment.search')
        ];

        return $model;
    }

    public function query($model, $all = true)
    {
        $ids = $model['ids'] ?? [];
        $modelEquipments = Equipment::with(['translations', 'location', 'company', 'equipmentCategory'])->select("bc_equipments.*");
        if (empty($model['order'])) $model['order'] = "id";
        if (empty($model['order_by'])) $model['order_by'] = "desc";
        if (empty($model['number'])) $model['number'] = 3;
        if (!empty($ids) && count($ids) > 0) {
            $modelEquipments->whereIn('id', $ids);
        } else {
            if ($all == false) {
                if (!empty($model['equipment_categories']) && is_array($model['equipment_categories']) && count($model['equipment_categories']) > 0) {
                    $modelEquipments->whereIn('category_id', $model['equipment_categories']);
                }
            }
            $modelEquipments->orderBy("bc_equipments." . $model['order'], $model['order_by']);
            if ($model['order'] == 'is_featured') {
                $modelEquipments->orderBy("bc_equipments.id", $model['order_by']);
            }
        }
        $modelEquipments->where("bc_equipments.status", "publish");

        $modelEquipments->groupBy("bc_equipments.id");

        if (!empty($ids) && count($ids) > 0) {
            $implodedStrings = implode("','", $ids);
            return $modelEquipments->limit(50)->orderByRaw(DB::raw("FIELD(id, '$implodedStrings')"))->get();
        } else {
            return $modelEquipments->limit($model['number'])->get();
        }
    }
}
