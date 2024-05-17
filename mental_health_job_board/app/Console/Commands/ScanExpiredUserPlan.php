<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Modules\User\Events\UserPlanExpired;
use Modules\User\Models\UserPlan;

class ScanExpiredUserPlan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user_plan:expired';

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
            ->whereDate('end_date', '<=', Carbon::now())
            ->where('status', UserPlan::CURRENT)
            ->chunkById(30, function ($user_plans) {
                foreach ($user_plans as $user_plan) {
                    $user_plan->status = UserPlan::USED;
                    $user_plan->save();

                    event(new UserPlanExpired($user_plan));

                    Log::debug("User Plan Expired for user: #" . $user_plan->create_user);
                    if (isset($user_plan->user->company)) {
                        $user_plan->user->company->jobs()->update([
                            'status' => 'draft'
                        ]);
                    }
                }
            });

        return Command::SUCCESS;
    }
}
