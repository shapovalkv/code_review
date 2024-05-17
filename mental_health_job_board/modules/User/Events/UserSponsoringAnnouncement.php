<?php
namespace Modules\User\Events;

use App\Notifications\AdminChannelServices;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class UserSponsoringAnnouncement
{
    use SerializesModels;
    public $user;
    public $announcement;

    public function __construct($user, $announcement)
    {
        $this->user = $user;
        $this->announcement = $announcement;
    }
}
