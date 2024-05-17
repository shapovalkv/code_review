<?php

namespace Modules\Template\Blocks;

use App\Resources\PopularSearchesResource;
use Modules\Candidate\Models\Candidate;
use Modules\Company\Models\Company;
use Modules\Equipment\Models\Equipment;
use Modules\Job\Models\Job;
use Modules\Job\Models\JobCategory;
use Modules\Location\Models\Location;
use Modules\Media\Helpers\FileHelper;
use Modules\User\Models\PopularSearch;

class SliderImage extends BaseBlock
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
                    'id' => 'jobs_sub_title',
                    'type' => 'input',
                    'inputType' => 'text',
                    'label' => __('Jobs Sub Title')
                ],
                [
                    'id' => 'equipments_sub_title',
                    'type' => 'input',
                    'inputType' => 'text',
                    'label' => __('Equipments Sub Title')
                ],
                [
                    'id' => 'companies_sub_title',
                    'type' => 'input',
                    'inputType' => 'text',
                    'label' => __('Companies Sub Title')
                ],
                [
                    'id' => 'candidates_sub_title',
                    'type' => 'input',
                    'inputType' => 'text',
                    'label' => __('Candidates Sub Title')
                ],
                [
                    'id' => 'banner_image_desktop',
                    'type' => 'uploader',
                    'label' => __("Banner Image Desktop")
                ],
                [
                    'id' => 'banner_image_tablet',
                    'type' => 'uploader',
                    'label' => __("Banner Image Tablet"),
                ],
                [
                    'id' => 'banner_image_mobile',
                    'type' => 'uploader',
                    'label' => __("Banner Image Mobile"),
                ],
                [
                    'id' => 'list_images',
                    'type' => 'listItem',
                    'label' => __('Images List (maximum:4)'),
                    'settings' => [
                        [
                            'id' => 'image_id',
                            'type' => 'uploader',
                            'label' => __('Image')
                        ],
                        [
                            'id' => 'url',
                            'type' => 'input',
                            'inputType' => 'text',
                            'label' => __('Url')
                        ],
                    ],
                ],
                [
                    'id' => 'list_counter',
                    'type' => 'listItem',
                    'label' => __('Block Counter'),
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
                ],
            ],
            'category' => __("Other Block")
        ]);
    }

    public function getName()
    {
        return __('Slider Image');
    }

    public function getPopularSearches($module, $limit = 3)
    {
        return PopularSearch::query()
            ->where('module', '=', $module)
            ->orderByDesc('request_count')
            ->take($limit)
            ->get()
            ->map(function ($popularSearches) {
                $popularSearches->keywords = ucfirst($popularSearches->keywords);
                return $popularSearches->only(['keywords']);
            });
    }

    public function content($model = [])
    {
        $model = block_attrs([
            'style' => 'style_1',
            'title' => '',
            'sub_title' => '',
            'upload_cv_url' => '',
            'banner_image' => '',
            'banner_image_2' => '',
            'style_5_banner_image_2' => '',
            'style_5_banner_image_3' => '',
            'style_5_list_images' => '',
            'style_6_list_images' => '',
            'style_7_list_images' => '',
            'location_style' => 'normal',
            'banner_image_url' => !empty($model['banner_image']) ? FileHelper::url($model['banner_image'], 'full') : '',
            'list_locations' => Location::where('status', 'publish')->limit(100)->get()->toTree(),
            'list_counter' => []
        ], $model);
        $style = (!empty($model['style'])) ? $model['style'] : 'style_1';
        if (!empty($model['popular_searches'])) {
            $model['popular_searches'] = explode(',', $model['popular_searches']);
        }
        $model['list_categories'] = JobCategory::where('status', 'publish')->get()->toTree();
        return view("Template::frontend.blocks.hero-banner.{$style}", $model);
    }

    public function contentAPI($model = [])
    {
        $model = block_attrs([
            'title' => '',
            'sub_title' => '',
            'jobs_sub_title' => '',
            'equipments_sub_title' => '',
            'companies_sub_title' => '',
            'candidates_sub_title' => '',
            'upload_cv_url' => '',
            'banner_image_desktop' => '',
            'banner_image_tablet' => '',
            'banner_image_mobile' => '',
            'location_style' => 'normal',
            'banner_image_desktop_url' => !empty($model['banner_image_desktop']) ? FileHelper::url($model['banner_image_desktop'], 'full') : '',
            'banner_image_tablet_url' => !empty($model['banner_image_tablet']) ? FileHelper::url($model['banner_image_tablet'], 'full') : '',
            'banner_image_mobile_url' => !empty($model['banner_image_mobile']) ? FileHelper::url($model['banner_image_mobile'], 'full') : '',
        ], $model);
        $model['popular_searches'] = $this->getPopularSearches('candidate');
        $model['jobs_sub_title'] = empty($model['jobs_sub_title']) ? '' : Job::all()->count() . " " . $model['jobs_sub_title'];
        $model['equipments_sub_title'] = empty($model['equipments_sub_title']) ? '' : Equipment::all()->count() . " " . $model['equipments_sub_title'];
        $model['companies_sub_title'] = empty($model['companies_sub_title']) ? '' : Company::all()->count() . " " . $model['companies_sub_title'];
        $model['candidates_sub_title'] = empty($model['candidates_sub_title']) ? '' : Candidate::all()->count() . " " . $model['candidates_sub_title'];
        $model['search_bar'] = [
            'search_title_key' => 'keywords',
            'speciality' => [
                ['id' => 1, 'title' => 'Jobs', 'icon' => 'ri-briefcase-line', 'slug' => 'job',
                    'items' => $this->getPopularSearches('job', 6)
                ],
                ['id' => 2, 'title' => 'Candidates', 'icon' => 'ri-user-line', 'slug' => 'candidate',
                    'items' => $this->getPopularSearches('candidate', 6)
                ],
                ['id' => 3, 'title' => 'Equipment', 'icon' => 'ri-car-line', 'slug' => 'equipment',
                    'items' => $this->getPopularSearches('equipment', 6)
                ],
                ['id' => 4, 'title' => 'Companies', 'icon' => 'ri-building-2-line', 'slug' => 'companies',
                    'items' => $this->getPopularSearches('companies', 6)
                ],
            ],
            'place' => PopularSearchesResource::collection(
                PopularSearch::query()
                    ->whereNotNull('location')
                    ->whereNotNull('location_type')
                    ->whereNotNull('location_state')
                    ->orderByDesc('request_count')
                    ->take(6)
                    ->get()
            )
        ];

        return $model;
    }
}
