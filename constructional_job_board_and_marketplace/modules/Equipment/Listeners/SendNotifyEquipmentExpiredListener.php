<?php

namespace Modules\Equipment\Listeners;

use App\Notifications\PrivateChannelServices;
use Modules\Core\Models\Notification;
use Modules\Equipment\Events\AutomaticEquipmentExpiration;


class SendNotifyEquipmentExpiredListener
{

    public function handle(AutomaticEquipmentExpiration $event)
    {
        $row = $event->row;
        $user = $row->company->getAuthor ?? $row->jobInfo->user;
        $data = [
            'message_type' => Notification::SYSTEM_NOTIFICATION,
            'id' => $row->id,
            'event' => 'AutomaticJobExpiration',
            'to' => 'employer',
            'name' => $user->display_name ?? '',
            'avatar' => '',
            'link' => route("seller.all.equipments"),
            'type' => 'equipment_expiration',
            'message' => __('Your equipment ":equipment" post has been expired. You can renew the equipment post within', ['equipment' => $row->title ?? ''])
        ];

        $user->notify(new PrivateChannelServices($data));
    }
}
