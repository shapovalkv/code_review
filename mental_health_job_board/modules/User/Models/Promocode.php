<?php


namespace Modules\User\Models;


use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Modules\Order\Models\OrderItem;

/**
 * @property float value
 * @property Collection|Plan[] plans
 * @property OrderItem[] orderItems
 * @property string code
 * @property string title
 * @property bool is_percent
 * @property bool is_annual
 * @property Carbon expiration_date
 */
class Promocode extends Model
{
    public const RELATION_PLANS = 'plans';
    public const RELATION_USERS = 'users';
    public const RELATION_ORDER_ITEMS = 'orderItems';

    use SoftDeletes;

    protected $table = 'promocodes';

    protected $casts = [
        'is_percent' => 'boolean',
        'is_annual'  => 'boolean',
    ];

    protected $fillable = [
        'title',
        'code',
        'value',
        'is_percent',
        'is_annual',
        'expiration_date',
    ];

    protected $dates = [
        'expiration_date'
    ];

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'promocode_id');
    }

    public function plans(): BelongsToMany
    {
        return $this->belongsToMany(Plan::class, (new PromocodePlan)->getTable(), 'promocode_id')->using(PromocodePlan::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, (new PromocodeUsage)->getTable(), 'promocode_id')->using(PromocodeUsage::class);
    }
}
