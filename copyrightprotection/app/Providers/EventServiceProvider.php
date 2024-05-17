<?php

namespace App\Providers;

use App\Events\AgentCreatedNewReportEvent;
use App\Events\CopyrightAssignedEvent;
use App\Events\CopyrightUnassignedEvent;
use App\Events\NewCopyrightEvent;
use App\Listeners\SendNotificationCopyrightAssignedListener;
use App\Listeners\SendNotificationCopyrightUnassignedListener;
use App\Listeners\SendNotificationNewCopyrightListener;
use App\Listeners\SendNotificationNewProjectReporthtListener;
use App\Listeners\StripeEventListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Laravel\Cashier\Events\WebhookReceived;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        NewCopyrightEvent::class => [
            SendNotificationNewCopyrightListener::class
        ],
        CopyrightAssignedEvent::class => [
            SendNotificationCopyrightAssignedListener::class
        ],
        CopyrightUnassignedEvent::class => [
            SendNotificationCopyrightUnassignedListener::class
        ],
        WebhookReceived::class => [
            StripeEventListener::class
        ],
        AgentCreatedNewReportEvent::class => [
            SendNotificationNewProjectReporthtListener::class
        ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
