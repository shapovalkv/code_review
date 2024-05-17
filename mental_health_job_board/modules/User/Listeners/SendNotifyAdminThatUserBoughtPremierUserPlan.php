<?php

    namespace Modules\User\Listeners;

    use App\Notifications\AdminChannelServices;
    use App\Notifications\PrivateChannelServices;
    use Modules\User\Events\NewVendorRegistered;
    use Modules\User\Events\RequestCreditPurchase;
    use Modules\User\Events\UserBoughtPremierUserPlan;
    use Modules\User\Events\VendorApproved;

    class SendNotifyAdminThatUserBoughtPremierUserPlan
    {

        public function handle(UserBoughtPremierUserPlan $event)
        {
            $user = $event->user;
            $plan = $event->plan;
            $data = [
                'id' =>  $user->id,
                'event'=>'UserBoughtPremierUserPlan',
                'to'=>'admin',
                'name' =>  $user->display_name,
                'avatar' =>  $user->avatar_url,
                'link' => route('user.admin.plan_report.index'),
                'type' => 'plan_report',
                'message' => __(':name has bought :plan_title plan name', ['name' => $user->display_name, 'plan_title' => $plan->title])
            ];

            $user->notify(new AdminChannelServices($data));
        }
    }
