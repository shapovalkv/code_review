<?php

namespace Modules\Equipment;

use Modules\Core\Helpers\SitemapHelper;
use Modules\Equipment\Models\Equipment;
use Modules\ModuleServiceProvider;

class ModuleProvider extends ModuleServiceProvider
{

    public function boot(SitemapHelper $sitemapHelper)
    {

        $this->loadMigrationsFrom(__DIR__ . '/Migrations');

        if (is_installed() and Equipment::isEnable()) {

            $sitemapHelper->add("equipment", [app()->make(Equipment::class), 'getForSitemap']);
        }
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouterServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
    }

    public static function getAdminMenu()
    {
        if (!Equipment::isEnable()) return [];
        return [
            'equipment' => [
                "position" => 40,
                'url' => route('equipment.admin.index'),
                'title' => __('Equipments'),
                'icon' => 'ion-ios-calendar',
                'permission' => 'equipment_manage',
                'children' => [
                    'add' => [
                        'url' => route('equipment.admin.index'),
                        'title' => __('All equipment'),
                        'permission' => 'equipment_manage',
                    ],
                    'create' => [
                        'url' => route('equipment.admin.create'),
                        'title' => __('Add new equipment'),
                        'permission' => 'equipment_manage',
                    ],
                    'cat' => [
                        'url' => route('equipment.admin.category.index'),
                        'title' => __('Category'),
                        'permission' => 'equipment_manage_others',
                    ],
                    'cat_type' => [
                        'url' => route('equipment.admin.category_type.index'),
                        'title' => __('Category Types'),
                        'permission' => 'equipment_manage_others',
                    ],
                    'recovery' => [
                        'url' => route('equipment.admin.recovery'),
                        'title' => __('Recovery'),
                        'permission' => 'equipment_manage_others',
                    ],
                ]
            ]
        ];
    }

    public static function getBookableServices()
    {
        if (!Equipment::isEnable()) return [];
        return [
            'equipment' => Equipment::class
        ];
    }

    public static function getMenuBuilderTypes()
    {
        if (!Equipment::isEnable()) return [];
        return [
            'equipment' => [
                'class' => Equipment::class,
                'name' => __("equipment"),
                'items' => Equipment::searchForMenu(),
                'position' => 51
            ]
        ];
    }

    public static function getUserMenu()
    {
        if (!Equipment::isEnable()) return [];
        return [
            'equipment' => [
                'url' => route('equipment.vendor.index'),
                'title' => __("Manage equipment"),
                'icon' => Equipment::getServiceIconFeatured(),
                'position' => 34,
                'permission' => 'equipment_manage',
                'children' => [
                    [
                        'url' => route('equipment.vendor.index'),
                        'title' => __("All equipment"),
                    ],
                    [
                        'url' => route('equipment.vendor.create'),
                        'title' => __("Add equipment"),
                        'permission' => 'equipment_manage',
                    ],
                    'availability' => [
                        'url' => route('equipment.vendor.availability.index'),
                        'title' => __('Availability'),
                        'permission' => 'equipment_manage',
                    ],
                    [
                        'url' => route('equipment.vendor.recovery'),
                        'title' => __("Recovery"),
                        'permission' => 'equipment_manage',
                    ],
                ]
            ],
        ];
    }

    public static function getTemplateBlocks()
    {
        return [
            'equipment_for_sale' => "\\Modules\\Equipment\\Blocks\\EquipmentForSale"
        ];
    }

}
