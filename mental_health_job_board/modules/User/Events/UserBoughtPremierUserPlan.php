<?php
namespace Modules\User\Events;

use App\Notifications\AdminChannelServices;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class UserBoughtPremierUserPlan
{
    use SerializesModels;
    public $user;
    public $plan;

    public function __construct($user, $plan)
    {
        $this->user = $user;
        $this->plan = $plan;
    }
}
