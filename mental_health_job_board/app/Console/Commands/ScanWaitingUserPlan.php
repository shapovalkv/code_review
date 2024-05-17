<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Modules\Job\Models\Job;
use Modules\User\Models\Plan;
use Modules\User\Models\PlanFeature;
use Modules\User\Models\UserPlan;

class ScanWaitingUserPlan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user_plan:waiting';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan all expired user plan and de activate posts';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        UserPlan::query()
            ->whereDate('start_date', '<=', Carbon::now())
            ->whereDate('end_date', '>=', Carbon::now())
            ->where('status', UserPlan::WAITING)
            ->whereHas(UserPlan::RELATION_PLAN, static fn(Builder $builder) => $builder->where('plan_type', Plan::TYPE_RECURRING))
            ->chunkById(30, function ($userPlans) {
                /** @var UserPlan $userPlan */
                foreach ($userPlans as $userPlan) {
                    $userPlan->user->currentUserPlan->status = UserPlan::USED;
                    $userPlan->user->currentUserPlan->save();
                    $userPlan->status = UserPlan::CURRENT;
                    $userPlan->save();
                    if (isset($userPlan->user->company)) {
                        $userPlan->user->company->jobs()->limit($userPlan->features[PlanFeature::JOB_CREATE] ?? 0)->update([
                            'status' => Job::PUBLISH
                        ]);
                    }
                    Log::debug('User Plan Started for user: #'. $userPlan->create_user);
                }
            });

        return Command::SUCCESS;
    }
}
