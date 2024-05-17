<?php

    namespace Modules\User\Listeners;

    use App\Notifications\AdminChannelServices;
    use App\Notifications\PrivateChannelServices;
    use Modules\User\Events\NewVendorRegistered;
    use Modules\User\Events\RequestCreditPurchase;
    use Modules\User\Events\UserBoughtPremierUserPlan;
    use Modules\User\Events\UserSponsoringAnnouncement;
    use Modules\User\Events\VendorApproved;

    class SendNotifyUserSponsoringAnnouncementListeners
    {

        public function handle(UserSponsoringAnnouncement $event)
        {
            $user = $event->user;
            $announcement = $event->announcement;
            $data = [
                'id' =>  $user->id,
                'event'=>'UserBoughtPremierUserPlan',
                'to'=>'admin',
                'name' =>  $user->display_name,
                'avatar' =>  $user->avatar_url,
                'link' => route("marketplace.vendor.index"),
                'type' => 'plan_report',
                'message' => __('Thank you for sponsoring your Announcement. Your post is now published. It will be posted for 31 days and will expire :expiration_date.', ['expiration_date' => date(get_date_format(), strtotime($announcement->expiration_date))])
            ];

            $user->notify(new PrivateChannelServices($data));
        }
    }
