<?php

    namespace Modules\User\Listeners;

    use App\Notifications\AdminChannelServices;
    use App\Notifications\PrivateChannelServices;
    use App\User;
    use Modules\Core\Models\Notification;
    use Modules\User\Events\NewVendorRegistered;
    use Modules\User\Events\RequestCreditPurchase;
    use Modules\User\Events\UpdateCreditPurchase;

    class SendNotifyUpdateCreditPurchase
    {

        public function handle(UpdateCreditPurchase $event)
        {
            $user = $event->user;
            $payment = $event->payment;
            $data = [
                'message_type' => Notification::SYSTEM_NOTIFICATION,
                'id' =>  $user->id,
                'event'=>'UpdateCreditPurchase',
                'to'=>'customer',
                'name' =>  $user->display_name,
                'avatar' =>  $user->avatar_url,
                'link' => route('user.wallet'),
                'type' => 'wallet_request',
                'message' => __('Administrator has approved your Credit amount')
            ];


            $customer = User::where('id',$payment->create_user)->where('status', 'publish')->first();
            if($customer) $customer->notify(new PrivateChannelServices($data));
        }
    }
