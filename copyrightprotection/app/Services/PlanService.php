<?php

namespace App\Services;

use App\Models\Plan;
use App\Models\UserProject;

class PlanService
{
    public function list() {
        return Plan::all();
    }

    public function subscribe($data)
    {
        $plan = Plan::find($data->plan);

        if ($data->user()->selectedProject->projectSubscription->stripe_status === 'active'){
            $this->cancel($data->user()->selectedProject);
        }

        return $data->user()->selectedProject->newSubscription($data->plan, $plan->stripe_plan)
            ->create($data->token);
    }

    public function cancel(UserProject $userProject)
    {
        if ($userProject->projectSubscription) {
            return $userProject->subscription($userProject->projectSubscription->name)->cancelNow();
        }
        return false;
    }

}
