<?php
/**
 * Created by PhpStorm.
 * User: h2 gaming
 * Date: 8/23/2019
 * Time: 10:33 PM
 */
namespace Modules\User\Events;

use Illuminate\Queue\SerializesModels;

class  UserPlanExpired
{
    use SerializesModels;
    public $user;
    public $plan;

    public function __construct($plan)
    {
        $this->plan = $plan;
    }
}
