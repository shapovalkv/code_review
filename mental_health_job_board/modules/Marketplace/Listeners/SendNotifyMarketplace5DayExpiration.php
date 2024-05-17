<?php

namespace Modules\Marketplace\Listeners;

use App\Notifications\PrivateChannelServices;
use Modules\Job\Models\Job;
use Modules\Marketplace\Events\AutomaticMarketplace5daysExpiration;


class SendNotifyMarketplace5DayExpiration
{

    public function handle(AutomaticMarketplace5daysExpiration $event)
    {
        /** @var Job $post */
        $post = $event->row;
        if ($post->company) {
            $user = $post->company->getAuthor;
            $data = [
                'id'      => $post->id,
                'event'   => 'AutomaticMarketplaceExpiration',
                'to'      => 'employer',
                'name'    => $user->display_name ?? '',
                'avatar'  => '',
                'link'    => route("marketplace.vendor.index"),
                'type'    => 'Marketplace_expiration',
                'message' => __('Your Announcement ":Announcement" will expire in 5 days', ['Announcement' => $post->title ?? ''])
            ];

            $user->notify(new PrivateChannelServices($data));
        }
    }
}
