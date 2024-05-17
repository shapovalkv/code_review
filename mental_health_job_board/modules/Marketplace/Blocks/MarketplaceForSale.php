<?php

namespace Modules\Marketplace\Blocks;

use Illuminate\Support\Facades\DB;
use Modules\Candidate\Models\Category;
use Modules\Marketplace\Models\Marketplace;
use Modules\Template\Blocks\BaseBlock;

class MarketplaceForSale extends BaseBlock
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
            'category' => __("Marketplace Blocks")
        ]);
    }

    public function getName()
    {
        return __('Marketplace for sale');
    }

    public function content($model = [])
    {
        $model = block_attrs([
            'style' => 'style_1',
            'title' => '',
            'sub_title' => '',
            'Marketplace_categories' => '',
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
        if (!empty($model['Marketplace_categories'])) $model['categories'] = Category::whereIn('id', $model['Marketplace_categories'])->get();

        return view("Marketplace::frontend.layouts.blocks.Marketplaces-list.{$style}", $model);
    }

    public function contentAPI($model = [], $user = null)
    {
        $model = block_attrs([
            'title' => '',
        ], $model);

        if (empty($model['title'])) $model['title'] = 'Marketplace for sale';

        $model['items'] = $this->query($model, false)->map(function ($Marketplace) use ($user) {
            return $Marketplace->dataForApi($user);
        });

        $model['button'] = [
            'text' => 'All Marketplace',
            'url' => route('marketplace.search')
        ];

        return $model;
    }

    public function query($model, $all = true)
    {
        $ids = $model['ids'] ?? [];
        $modelMarketplaces = Marketplace::with(['translations', 'location', 'company', 'MarketplaceCategory'])->select("bc_marketplaces.*");
        if (empty($model['order'])) $model['order'] = "id";
        if (empty($model['order_by'])) $model['order_by'] = "desc";
        if (empty($model['number'])) $model['number'] = 3;
        if (!empty($ids) && count($ids) > 0) {
            $modelMarketplaces->whereIn('id', $ids);
        } else {
            if ($all == false) {
                if (!empty($model['Marketplace_categories']) && is_array($model['Marketplace_categories']) && count($model['Marketplace_categories']) > 0) {
                    $modelMarketplaces->whereIn('category_id', $model['Marketplace_categories']);
                }
            }
            $modelMarketplaces->orderBy("bc_marketplaces." . $model['order'], $model['order_by']);
            if ($model['order'] == 'is_featured') {
                $modelMarketplaces->orderBy("bc_marketplaces.id", $model['order_by']);
            }
        }
        $modelMarketplaces->where("bc_marketplaces.status", "publish");

        $modelMarketplaces->groupBy("bc_marketplaces.id");

        if (!empty($ids) && count($ids) > 0) {
            $implodedStrings = implode("','", $ids);
            return $modelMarketplaces->limit(50)->orderByRaw(DB::raw("FIELD(id, '$implodedStrings')"))->get();
        } else {
            return $modelMarketplaces->limit($model['number'])->get();
        }
    }
}
