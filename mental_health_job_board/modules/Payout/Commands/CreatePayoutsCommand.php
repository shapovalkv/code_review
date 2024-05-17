<?php

namespace Modules\Payout\Commands;

use Illuminate\Console\Command;
use Modules\Gig\Models\GigOrder;
use Modules\Payout\Jobs\CreatePayoutForCandidateJob;

class CreatePayoutsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'candidate:create_payouts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate prev month payouts for all candidate';

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
        $invoice_month = now()->firstOfMonth();
        $prevMonth = now()->subMonth();
        // Get all vendor have completed order item this month
        $query = GigOrder::query()->select(['id','author_id'])
            ->where('status',GigOrder::COMPLETED)
            ->where('created_at','<',$invoice_month->format('Y-m-d 00:00:00'))
            ->whereNull('payout_id')
            ->groupBy('author_id');

        $query->chunkById(20,function($vendors) use ($prevMonth) {
            foreach ($vendors as $vendor){
                CreatePayoutForCandidateJob::dispatch($vendor->author_id,$prevMonth->format('Y'),$prevMonth->format('m'));
            }
        });
    }
}
