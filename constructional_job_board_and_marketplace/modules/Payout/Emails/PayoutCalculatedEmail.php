<?php
namespace Modules\Payout\Emails;


use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Payout\Models\VendorPayout;

class PayoutCalculatedEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The order instance.
     *
     * @var VendorPayout
     */
    public $payout;

    /**
     * Create a new message instance.
     *
     * @param  VendorPayout $payout
     * @return void
     */
    public function __construct(VendorPayout $payout)
    {
        $this->payout = $payout;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(__('Your Market Earnings have been calculated'))->view('Candidate::email.payout',[
            'payout'=>$this->payout,
            'vendor'=>$this->payout->vendor
        ]);
    }

}
