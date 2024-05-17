<?php

namespace Modules\Marketplace;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Marketplace\Events\AutomaticMarketplace5daysExpiration;
use Modules\Marketplace\Events\AutomaticMarketplaceExpiration;
use Modules\Marketplace\Listeners\SendMailMarketplace5DayExpiration;
use Modules\Marketplace\Listeners\SendMailMarketplaceExpiredListener;
use Modules\Marketplace\Listeners\SendNotifyMarketplace5DayExpiration;
use Modules\Marketplace\Listeners\SendNotifyMarketplaceExpiredListener;
use Modules\User\Events\UserSponsoringAnnouncement;
use Modules\User\Listeners\SendEmailUserSponsoringAnnouncementListeners;
use Modules\User\Listeners\SendNotifyUserSponsoringAnnouncementListeners;


class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        AutomaticMarketplaceExpiration::class => [
            SendNotifyMarketplaceExpiredListener::class,
            SendMailMarketplaceExpiredListener::class,
        ],
        AutomaticMarketplace5daysExpiration::class => [
            SendMailMarketplace5DayExpiration::class,
            SendNotifyMarketplace5DayExpiration::class
        ],
        UserSponsoringAnnouncement::class => [
            SendEmailUserSponsoringAnnouncementListeners::class,
            SendNotifyUserSponsoringAnnouncementListeners::class
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
