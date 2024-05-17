<?php


namespace Modules\Order;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Order\Events\OrderUpdated;
use Modules\Order\Events\PaymentUpdated;
use Modules\Order\Listeners\OrderUpdatedNotification;
use Modules\Order\Listeners\PaymentUpdatedListener;
use Modules\Order\Listeners\SendNotificationOrderUpdatedListener;

class EventServiceProvider extends ServiceProvider
{

    protected $listen = [
        PaymentUpdated::class =>[
            PaymentUpdatedListener::class
        ],
        OrderUpdated::class=>[
            OrderUpdatedNotification::class,
            SendNotificationOrderUpdatedListener::class
        ]
    ];
}
