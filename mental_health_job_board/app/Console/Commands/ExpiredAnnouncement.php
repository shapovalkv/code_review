<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Modules\Job\Events\AutomaticJobExpiration;
use Modules\Job\Models\Job;
use Modules\Marketplace\Events\AutomaticMarketplaceExpiration;
use Modules\Marketplace\Models\Marketplace;

class ExpiredAnnouncement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'announcement:expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Search and move to draft expired announcements';

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
                $announcement->status = 'draft';
                $announcement->is_featured = 0;
                $announcement->save();

                event(new AutomaticMarketplaceExpiration($announcement));
            }
        }
    }

    public function getExpiredAnnouncements()
    {
        return Marketplace::query()
            ->whereDate('expiration_date', '<=', Carbon::now())
            ->where('status', Marketplace::STATUS_PUBLISH)
            ->get();
    }
}
