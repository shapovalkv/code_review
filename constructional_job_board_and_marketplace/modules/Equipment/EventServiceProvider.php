<?php

namespace Modules\Equipment;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Equipment\Events\AutomaticEquipmentExpiration;
use Modules\Equipment\Listeners\SendMailEquipmentExpiredListener;
use Modules\Equipment\Listeners\SendNotifyEquipmentExpiredListener;


class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        AutomaticEquipmentExpiration::class => [
            SendNotifyEquipmentExpiredListener::class,
            SendMailEquipmentExpiredListener::class,
        ]
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
