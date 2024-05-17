<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Modules\Job\Events\AutomaticJobExpiration;
use Modules\Job\Models\Job;

class ExpiredJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'job:expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Search and move to draft expired jobs';

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
        if (!empty($jobs = $this->getExpiredJobs())) {
            foreach ($jobs as $job) {
                $job->status = 'draft';
                $job->is_featured = 0;
                $job->save();

                event(new AutomaticJobExpiration($job));
            }
        }
    }

    public function getExpiredJobs()
    {
        return Job::query()
            ->whereDate('expiration_date', '<=', Carbon::now())
            ->where('status', Job::PUBLISH)
            ->get();
    }
}
