<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Modules\Job\Events\AutomaticJobExpiration;
use Modules\Job\Models\Job;
use Modules\Marketplace\Events\AutomaticMarketplace5daysExpiration;
use Modules\Marketplace\Events\AutomaticMarketplaceExpiration;
use Modules\Marketplace\Models\Marketplace;

class ExpiredAnnouncement5Days extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'announcement:expired5days';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Search and notify users that have 5 days announcements expiration';

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
        if (!empty($announcements = $this->getExpiredAnnouncements())) {
            foreach ($announcements as $announcement) {
                event(new AutomaticMarketplace5daysExpiration($announcement));
            }
        }
    }

    public function getExpiredAnnouncements()
    {
        return Marketplace::query()
            ->whereDate('expiration_date', Carbon::now()->addDays(5)->toDateString())
            ->get();
    }
}
