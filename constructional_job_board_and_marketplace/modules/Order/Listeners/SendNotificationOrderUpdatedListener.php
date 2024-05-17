<?php

namespace Modules\Order\Listeners;

use App\Notifications\PrivateChannelServices;
use Modules\Order\Events\OrderUpdated;

class SendNotificationOrderUpdatedListener
{
    public function handle(OrderUpdated $event)
    {
        $order = $event->_order;
        switch ($order->status) {
            case "completed":
                // Send Notification
                $user = $order->customer;
                $data = [
                    'id' => $user->id,
                    'name' => $user->display_name,
                    'avatar' => $user->avatar_url,
                    'link' => route('order.detail', ['id' => $order->id]),
                    'type' => 'user_order_updated',
                    'message' => __('Hello :name, Thank you for your order: #:order_id, price: :price $', [
                        'name' => $user->display_name, 'order_id' => $order->id, 'price' => $order->total
                    ])
                ];

                $user->notify(new PrivateChannelServices($data));
                break;
        }
    }
}
