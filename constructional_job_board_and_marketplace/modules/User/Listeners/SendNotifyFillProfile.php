<?php

    namespace Modules\User\Listeners;

    use App\Notifications\AdminChannelServices;
    use App\Notifications\PrivateChannelServices;
    use Illuminate\Auth\Events\Verified;
    use Modules\Core\Models\Notification;
    use Modules\User\Events\NewVendorRegistered;
    use Modules\User\Events\VendorApproved;

    class SendNotifyFillProfile
    {

        public function handle(Verified $event)
        {
            $user = $event->user;
            $link = '';
            $message = '';
            if ($user->role_id == 2){
                if ($user->company && !$user->company->is_completed){
                    $link = route('user.company.profile');
                    $message =  __('Your email has been successfully verified. Please fill out your company profile details.');
                } else {
                    $link = route('user.dashboard');
                    $message =  __('Your email has been successfully verified.');
                }
            } elseif($user->role_id == 3){
                $link = route("user.profile.index");
                $message =  __('Your email has been successfully verified. Please fill out your profile details.');
            }

            $data = [
                'message_type' => Notification::SYSTEM_NOTIFICATION,
                'id' =>  $user->id,
                'event'   => 'Verified',
                'to'      => 'vendor',
                'name' =>  $user->display_name,
                'link' => $link,
                'type' => 'user_fill_profile_request',
                'message' => $message
            ];

            $user->notify(new PrivateChannelServices($data));
        }
    }
