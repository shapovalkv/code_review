<?php


namespace Modules\Payout\Traits;


use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\DB;
use Modules\Gig\Models\GigOrder;
use Modules\Payout\Models\VendorPayout;
use Modules\Payout\Models\VendorPayoutAccount;

trait HasPayout
{

    public function payouts(){
        return $this->hasMany(VendorPayout::class,'vendor_id');
    }

    public function current_payout(){
        return $this->hasOne(VendorPayout::class,'vendor_id')->orderByDesc('id');
    }

    public function payout_account(){
        return $this->hasOne(VendorPayoutAccount::class,'vendor_id');
    }

    public function getAvailablePayoutAmountAttribute(){
        $query = GigOrder::query();

        $total =  $query
            ->whereIn('status',['completed'])
            ->where('vendor_id',$this->id)
            ->whereNull('payout_id')
            ->sum(DB::raw('price * qty - discount_amount - commission_amount - tax_amount'));
        return max(0,$total);
    }

}
