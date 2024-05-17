<?php
namespace Modules\Company;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Company\Events\EmployerFilledCompanyProfile;
use Modules\Company\Listeners\SendNotifyToEmployerSelectSubscriptionListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        EmployerFilledCompanyProfile::class => [
            SendNotifyToEmployerSelectSubscriptionListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
