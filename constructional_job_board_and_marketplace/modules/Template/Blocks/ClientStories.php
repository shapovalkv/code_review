<?php

namespace Modules\Template\Blocks;

use Modules\Media\Helpers\FileHelper;

class ClientStories extends BaseBlock
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
                [
                    'id' => 'items',
                    'type' => 'listItem',
                    'label' => __('List Item(s)'),
                    'title_field' => 'title',
                    'settings' => [
                        [
                            'id' => 'title',
                            'type' => 'input',
                            'inputType' => 'text',
                            'label' => __('Title')
                        ],
                        [
                            'id' => 'desc',
                            'type' => 'textArea',
                            'label' => __('Desc')
                        ],
                        [
                            'id' => 'info_name',
                            'type' => 'input',
                            'inputType' => 'text',
                            'label' => __('Info Name')
                        ],
                        [
                            'id' => 'position',
                            'type' => 'input',
                            'inputType' => 'text',
                            'label' => __('Position')
                        ],
                        [
                            'id' => 'company_image',
                            'type' => 'uploader',
                            'label' => __('Company Image')
                        ],
                        [
                            'id' => 'company_url',
                            'type' => 'input',
                            'inputType' => 'url',
                            'label' => __('Company url')
                        ],
                    ]
                ],
            ],
            'category' => __("Other Block")
        ]);
    }

    public function getName()
    {
        return __('Client Stories');
    }

    public function content($model = [])
    {
        $model = block_attrs([
            'title' => '',
            'sub_title' => '',
            'items' => '',
            'banner_image' => '',
            'banner_image_2' => '',
            'banner_image_url' => !empty($model['banner_image']) ? FileHelper::url($model['banner_image'], 'full') : '',
            'banner_image_2_url' => !empty($model['banner_image_2']) ? FileHelper::url($model['banner_image_2'], 'full') : '',
        ], $model);

        $blade = (!empty($model['style'])) ? $model['style'] : 'index';

        return view("Template::frontend.blocks.testimonial.{$blade}", $model);
    }

    public function contentAPI($model = [])
    {
        $model = block_attrs([
            'title' => '',
            'sub_title' => '',
            'items' => '',
        ], $model);

        if (!empty($model['items'])) {
            foreach ($model['items'] as &$item) {
                $item['company_image_url'] = !empty($item['company_image']) ? FileHelper::url($item['company_image'], 'full') : null;
            }
        }

        $model['button'] = [
            'text' => 'All Companies',
            'url' => route('companies.index')
        ];

        return $model;
    }
}
