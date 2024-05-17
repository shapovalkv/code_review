<?php
namespace Modules\Candidate;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Candidate\Events\CandidateChangeJobInviteStatus;
use Modules\Candidate\Listeners\SendMailChangeInviteStatusListen;
use Modules\Candidate\Listeners\SendNotifyChangeInviteStatusListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        CandidateChangeJobInviteStatus::class => [
            SendMailChangeInviteStatusListen::class,
            SendNotifyChangeInviteStatusListener::class
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
