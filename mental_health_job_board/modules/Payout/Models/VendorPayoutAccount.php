<?php


namespace Modules\Payout\Models;


use App\BaseModel;

class VendorPayoutAccount extends BaseModel
{

    protected $table = 'vendor_payout_accounts';

    protected $fillable = ['payout_method','account_info'];

    protected $casts = [
        'account_info'=>'array'
    ];
}
