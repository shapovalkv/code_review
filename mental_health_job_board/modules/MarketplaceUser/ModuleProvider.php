<?php
namespace Modules\MarketplaceUser;

use Modules\ModuleServiceProvider;


class ModuleProvider extends ModuleServiceProvider
{

    public function boot(){
        $this->loadMigrationsFrom(__DIR__ . '/Migrations');

        $this->publishes([
            __DIR__.'/Config/config.php' => config_path('marketplace_user.php'),
        ]);

    }
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/Config/config.php', 'marketplace_user'
        );

        $this->app->register(RouteServiceProvider::class);
    }

    public static function getAdminMenu()
    {
        $marketplace_user_menus = [
            'marketplace_user'=>[
                "position"=>26,
                'url'        => route('marketplace_user.admin.index'),
                'title'      => __("Marketplace User"),
                'icon'       => 'ion-md-bookmarks',
                'permission' => 'marketplace_user_manage_others',
                'children'   => [
                    'marketplace_users_view'=>[
                        'url'        => route('marketplace_user.admin.index'),
                        'title'      => __("All MarketplaceUsers"),
                        'permission' => 'marketplace_user_manage',
                    ],
                    'marketplace_users_create'=>[
                        'url'        => route('user.admin.create', ['marketplace_user_create' => 1]),
                        'title'      => __("Add Marketplace User"),
                        'permission' => 'marketplace_user_manage',
                    ]
                ]
            ]

        ];
        return $marketplace_user_menus;
    }

    public static function getTemplateBlocks(){
        return [
            'list_marketplace_users'=>"\\Modules\\MarketplaceUser\\Blocks\\ListMarketplaceUsers",
        ];
    }

    public static function getUserMenu()
    {
        $res = [];
        if(is_marketplace_user()) {

            $res['marketplace_user_profile'] = [
                'url' => '#',
                'title' => __("Marketplace User"),
                'icon' => 'la la-user-tie',
                'enable' => true,
                'position'=>40,
                'children'=>[
                    'profile'=>[
                        'url' => route('user.marketplace_user.index'),
                        'title' => __("Profile"),
                        'icon' => 'la la-user-tie',
                        'position' => 10
                    ]
                ]
            ];
        }

        return $res;
    }
}
