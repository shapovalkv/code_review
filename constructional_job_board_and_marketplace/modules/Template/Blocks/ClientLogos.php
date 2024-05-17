<?php

namespace Modules\Template\Blocks;

use Modules\Media\Helpers\FileHelper;

class ClientLogos extends BaseBlock
{
    function __construct()
    {
        $this->setOptions([
            'settings' => [
                [
                    'id' => 'items',
                    'type' => 'listItem',
                    'label' => __('Client Logos'),
                    'title_field' => 'title',
                    'settings' => [
                        [
                            'id' => 'image_id',
                            'type' => 'uploader',
                            'label' => __('Logo Image')
                        ],
                        [
                            'id' => 'brand_link',
                            'type' => 'input',
                            'inputType' => 'text',
                            'label' => __('Brand Link')
                        ],
                    ]
                ],
            ],
            'category' => __("Other Block")
        ]);
    }

    public function getName()
    {
        return __('Client Logos');
    }

    public function content($model = [])
    {
        $model = block_attrs([
            'style' => '',
            'title' => '',
            'sub_title' => '',
            'items' => ''
        ], $model);
        if (!empty($model['image_id'])) {
            $model['image_url'] = get_file_url($model['image_id'], 'full');
        }
        $blade = (!empty($model['style'])) ? $model['style'] : 'style_1';
        if (!empty($model['style']) && $model['style'] == 'style_2') $blade = 'style_1';

        return view('Template::frontend.blocks.brands-list.' . $blade, $model);
    }

    public function contentAPI($model = [])
    {
        $model = block_attrs([
            'items' => ''
        ], $model);

        if (!empty($model['items'])) {
            foreach ($model['items'] as $key => $item) {
                $model['items'][$key] = [
                    "_active" => $item['_active'],
                    "image_url" => isset($item['image_id']) ? FileHelper::url($item['image_id'], 'full') : null,
                    "brand_link" => $item['brand_link']
                ];
            }
        }
        return $model;
    }
}
