<?php


namespace Modules\Payout\Models;


use App\BaseModel;
use App\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Modules\Gig\Models\GigOrder;

class VendorPayout extends BaseModel
{

    use SoftDeletes;

    const PENDING = 'pending';

    protected $table = 'vendor_payouts';

    protected $casts = [
        'account_info'=>'array'
    ];

    public static function getAllStatuses(){
        return [
            'pending'=>[
                'title'=>__("Pending")
            ],
            'paid'=>[
                'title'=>__("Paid")
            ],
            'rejected'=>[
                'title'=>__("Rejected")
            ],
        ];
    }

    public function calculateTotal(){
        $this->total = GigOrder::query()
            ->where('payout_id',$this->id)
            ->sum('total');
        $this->save();
    }

    public function candidate(){
        return $this->belongsTo(User::class,'vendor_id');
    }
    public function getMethodNameAttribute(){
        $all = setting_item_array('vendor_payout_methods');
        foreach ($all as $item){
            if(!isset($item['id'])) continue;
            if($item['id'] == $this->payout_method) return $item['name'] ?? '';
        }
        return $this->payout_method;
    }

    public static function isEnable(){
        return setting_item('disable_payout') == false;
    }
}
