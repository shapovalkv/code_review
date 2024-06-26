<?php
namespace Modules\User;


use App\Events\NewMessageToChat;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Booking\Events\VendorLogPayment;
use Modules\Booking\Listeners\VendorLogPaymentListen;
use Modules\Order\Events\OrderUpdated;
use Modules\User\Events\AdminUpdateVerificationData;
use Modules\User\Events\NewVendorRegistered;
use Modules\User\Events\RequestCreditPurchase;
use Modules\User\Events\SendMailUserRegistered;
use Modules\User\Events\UpdateCreditPurchase;
use Modules\User\Events\UserBoughtPremierUserPlan;
use Modules\User\Events\UserPlanExpired;
use Modules\User\Events\UserSubscriberSubmit;
use Modules\User\Events\UserVerificationSubmit;
use Modules\User\Events\VendorApproved;
use Modules\User\Listeners\ClearUserTokens;
use Modules\User\Listeners\SendAdminUpdateVerifyDataEmail;
use Modules\User\Listeners\SendChatMessageToMailListener;
use Modules\User\Listeners\SendMailUserPlanExpired;
use Modules\User\Listeners\SendMailUserRegisteredListen;
use Modules\User\Listeners\SendNotifyAdminThatUserBoughtPremierUserPlan;
use Modules\User\Listeners\SendNotifyRequestCreditPurchase;
use Modules\User\Listeners\SendNotifyUpdateCreditPurchase;
use Modules\User\Listeners\SendNotifyUpdateVerificationData;
use Modules\User\Listeners\SendNotifyVerificationData;
use Modules\User\Listeners\SendUserNotifyPlanExpired;
use Modules\User\Listeners\SendUserSubmitVerifyDataEmail;
use Modules\User\Listeners\SendVendorApprovedMail;
use Modules\User\Listeners\SendVendorRegisterdEmail;
use Modules\User\Listeners\UpdateUserPlanListener;
use Modules\User\Listeners\UserSubscriberSubmitListeners;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        UserVerificationSubmit::class => [
            SendUserSubmitVerifyDataEmail::class,
            SendNotifyVerificationData::class
        ],
        AdminUpdateVerificationData::class => [
            SendAdminUpdateVerifyDataEmail::class,
            SendNotifyUpdateVerificationData::class
        ],
        RequestCreditPurchase::class => [
            SendNotifyRequestCreditPurchase::class
        ],
        UpdateCreditPurchase::class => [
            SendNotifyUpdateCreditPurchase::class
        ],
        UserSubscriberSubmit::class => [
            UserSubscriberSubmitListeners::class
        ],
        OrderUpdated::class=>[
            UpdateUserPlanListener::class
        ],
        PasswordReset::class=>[
            ClearUserTokens::class
        ],
        UserBoughtPremierUserPlan::class=>[
            SendNotifyAdminThatUserBoughtPremierUserPlan::class
        ],
        UserPlanExpired::class=>[
            SendMailUserPlanExpired::class,
            SendUserNotifyPlanExpired::class
        ],
        NewMessageToChat::class => [
            SendChatMessageToMailListener::class
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
