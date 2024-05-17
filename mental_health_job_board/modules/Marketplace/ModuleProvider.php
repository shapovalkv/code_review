<?php

namespace Modules\Marketplace;

use App\Enums\UserPermissionEnum;
use Modules\Core\Helpers\SitemapHelper;
use Modules\Marketplace\Models\Marketplace;
use Modules\ModuleServiceProvider;

class ModuleProvider extends ModuleServiceProvider
{

    public function boot(SitemapHelper $sitemapHelper)
    {

        $this->loadMigrationsFrom(__DIR__ . '/Migrations');

        if (is_installed() and Marketplace::isEnable()) {

            $sitemapHelper->add("Marketplace", [app()->make(Marketplace::class), 'getForSitemap']);
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
        if (!Marketplace::isEnable()) return [];
        return [
            'Marketplace' => [
                "position" => 40,
                'url' => route('marketplace.admin.index'),
                'title' => __('Marketplaces'),
                'icon' => 'ion-ios-calendar',
                'permission' => 'marketplace_manage',
                'children' => [
                    'add' => [
                        'url' => route('marketplace.admin.index'),
                        'title' => __('All Marketplace'),
                        'permission' => 'marketplace_manage',
                    ],
                    'create' => [
                        'url' => route('marketplace.admin.create'),
                        'title' => __('Post on Marketplace'),
                        'permission' => 'marketplace_manage',
                    ],
                    'cat' => [
                        'url' => route('marketplace.admin.category.index'),
                        'title' => __('Category'),
                        'permission' => 'marketplace_manage_others',
                    ],
                ]
            ]
        ];
    }

    public static function getBookableServices()
    {
        if (!Marketplace::isEnable()) return [];
        return [
            'Marketplace' => Marketplace::class
        ];
    }

    public static function getMenuBuilderTypes()
    {
        if (!Marketplace::isEnable()) return [];
        return [
            'Marketplace' => [
                'class' => Marketplace::class,
                'name' => __("Marketplace"),
                'items' => Marketplace::searchForMenu(),
                'position' => 51
            ]
        ];
    }

    public static function getUserMenu()
    {
        if (!Marketplace::isEnable()) return [];
        return [
            'Marketplace' => [
                'url' => '#',
                'title' => __("Manage Marketplace"),
                'icon'       => 'la la-shopping-bag',
                'position' => 40,
                'permission' => ['marketplace_manage', UserPermissionEnum::COMPANY_ANNOUNCEMENT_MANAGE],
                'children' => [
                    [
                        'url' => route('seller.all.marketplaces'),
                        'title' => __("All Marketplace Posts"),
                        'permission' => ['marketplace_manage', UserPermissionEnum::COMPANY_ANNOUNCEMENT_MANAGE],
                    ],
                    [
                        'url' => route('seller.marketplace.create'),
                        'title' => __("Post on Marketplace"),
                        'permission' => ['marketplace_manage', UserPermissionEnum::COMPANY_ANNOUNCEMENT_MANAGE],
                    ],
                ]
            ],
        ];
    }

    public static function getTemplateBlocks()
    {
        return [
            'Marketplace_for_sale' => "\\Modules\\Marketplace\\Blocks\\MarketplaceForSale"
        ];
    }

}
