<?php


namespace Modules\Payout\Jobs;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Modules\User\Models\User;
use Modules\Payout\Models\VendorPayout;
use Modules\Gig\Models\GigOrder;
use Modules\Payout\Emails\PayoutCalculatedEmail;
use Modules\Payout\Models\VendorPayoutAccount;

class CreatePayoutForCandidateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $author_id;
    protected $year;
    protected $month;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($author_id,$year,$month)
    {
        $this->author_id = $author_id;
        $this->year = $year;
        $this->month = $month;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $vendor = User::find($this->author_id);
        if(!$vendor) return;

        $payout_account = VendorPayoutAccount::where(['vendor_id'=>$this->author_id,'is_main'=>1])->first();
        if(!$payout_account) return;

        $where = [
            'year'=>$this->year,
            'month'=>$this->month,
            'vendor_id'=>$this->author_id,
        ];
        $find = VendorPayout::query()->where($where)->first();
        if(!$find){
            $find = new VendorPayout();
            $find->fillByAttr(array_keys($where),$where);
            $find->status = VendorPayout::PENDING;

            $find->payout_method = $payout_account->payout_method;
            $find->account_info = $payout_account->account_info;

            $find->save();

            // Update Order Items
            $invoice_month = Carbon::createFromDate($this->year,$this->month,1);
            GigOrder::query()
                ->where('status',GigOrder::COMPLETED)
                ->where('created_at','<',$invoice_month->lastOfMonth()->format('Y-m-d 23:59:59'))
                ->where('author_id',$this->author_id)
                ->whereNull('payout_id')
                ->update([
                    'payout_id'=>$find->id
                ]);
            // Now recalculate total
            $find->calculateTotal();

            // Now send email to vendor :)
            Mail::to($vendor)
                ->queue(new PayoutCalculatedEmail($find));
        }
    }
}
