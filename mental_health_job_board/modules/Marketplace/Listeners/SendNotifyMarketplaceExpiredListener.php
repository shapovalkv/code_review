<?php

namespace Modules\Marketplace\Listeners;

use App\Notifications\PrivateChannelServices;
use Modules\Core\Models\Notification;
use Modules\Marketplace\Events\AutomaticMarketplaceExpiration;
use Modules\Marketplace\Models\Marketplace;


class SendNotifyMarketplaceExpiredListener
{

    public function handle(AutomaticMarketplaceExpiration $event)
    {
        /** @var Marketplace $row */
        $row = $event->row;
        if ($row->company) {
            $user = $row->company->getAuthor;
            $data = [
                'id'      => $row->id,
                'event'   => 'AutomaticMarketplaceExpiration',
                'to'      => 'employer',
                'name'    => $user->display_name ?? '',
                'avatar'  => '',
                'link'    => route("marketplace.vendor.index"),
                'type'    => 'Marketplace_expiration',
                'message' => __('Your Announcement ":Announcement" post has expired. You can renew the Marketplace post within', ['Announcement' => $row->title ?? ''])
            ];

            $user->notify(new PrivateChannelServices($data));
        }
    }
}
