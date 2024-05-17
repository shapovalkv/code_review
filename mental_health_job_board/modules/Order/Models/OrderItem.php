<?php


namespace Modules\Order\Models;


use App\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\User\Models\Plan;
use Modules\User\Models\Promocode;

/**
 * @property array meta
 * @property int object_id
 * @property string object_model
 * @property float price
 * @property Promocode|null promocode
 * @property int|null promocode_id
 */
class OrderItem extends BaseModel
{

    use SoftDeletes;

    public const COMPLETED = 'completed';
    public const RELATION_PLAN = 'plan';
    public const RELATION_PROMOCODE = 'promocode';

    protected $table = 'bc_order_items';

    protected $casts = [
        'meta'=>'array'
    ];

    public function model()
    {
        $keys = get_bookable_services();
        if(array_key_exists($this->object_model,$keys)){
            return $keys[$this->object_model]::withTrashed()->find($this->object_id);
        }
        return false;
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'object_id')->withTrashed();
    }

    public function item()
    {
        if(!empty($this->meta['model']) && !empty($this->meta['model_id'])) {
            return $this->meta['model']::withTrashed()->find($this->meta['model_id']);
        }

        return null;
    }

    public function order(){
        return $this->belongsTo(Order::class,'order_id');
    }

    public function promocode(): BelongsTo
    {
        return $this->belongsTo(Promocode::class)->withTrashed();
    }

    public function getStatusNameAttribute()
    {
        return booking_status_to_text($this->status);
    }

    public function getSubtotalAttribute(){
        return $this->price * $this->qty + $this->extra_price_total;
    }

    public function getExtraPriceTotalAttribute(){
        $t = 0;
        if(!empty($this->meta['extra_prices']))
        {
            foreach ($this->meta['extra_prices'] as $extra_price){
                $t += (float)($extra_price['price']);
            }
        }
        return $t;
    }

}
