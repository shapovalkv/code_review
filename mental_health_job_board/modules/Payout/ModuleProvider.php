<?php
namespace Modules\Payout;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Modules\Company\Models\Company;
use Modules\Core\Helpers\SitemapHelper;
use Modules\Gig\Models\Gig;
use Modules\ModuleServiceProvider;
use Modules\Payout\Commands\CreatePayoutsCommand;
use Modules\Payout\Models\VendorPayout;

class ModuleProvider extends ModuleServiceProvider
{

    public function boot(SitemapHelper $sitemapHelper){


    }
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            CreatePayoutsCommand::class,
        ]);
        if ($this->app->runningInConsole()) {

        }
        $this->app->register(RouteServiceProvider::class);
    }

    public static function getAdminMenu()
    {
        $payout_menus = [
            'payout'=>[
                "position"=>27,
                'url'        => route('payout.admin.index'),
                'title'      => __("Payouts"),
                'icon'       => 'ion-md-card',
                'permission' => 'admin_payout_manage',
            ]
        ];
        return $payout_menus;
    }

}
