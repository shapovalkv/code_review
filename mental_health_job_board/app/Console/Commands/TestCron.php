<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Modules\User\Events\UserPlanExpired;
use Modules\User\Models\User;
use Modules\User\Models\UserPlan;

class TestCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test cron';

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
    public function handle()
    {
        UserPlan::query()->where('id',50)->chunkById(30,function($user_plans){
            foreach ($user_plans as $user_plan){
                $user_plan->status = 0;
                $user_plan->save();

                event(new UserPlanExpired($user_plan));

                Log::debug("User Plan Expired for user: #".$user_plan->create_user);
            }
        });
    }
}
