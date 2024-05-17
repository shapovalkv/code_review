<?php
namespace Modules\Contact;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Contact\Events\UserSentHelpMessageEvent;
use Modules\Contact\Listeners\SendNotifyHelpMessage;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        UserSentHelpMessageEvent::class => [
            SendNotifyHelpMessage::class,
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
