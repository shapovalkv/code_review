<?php


namespace Modules\User\Models;


use App\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Modules\Order\Models\OrderItem;

/**
 * @property Carbon start_date
 * @property Carbon end_date
 * @property Plan plan
 * @property int status
 * @property array features
 * @property array plan_data
 * @property User user
 */
class UserPlan extends BaseModel
{

    public const CURRENT = 1;
    public const USED = 0;
    public const WAITING = 2;
    public const NOT_USED = 3;

    public const RELATION_PLAN = 'plan';
    public const RELATION_USER = 'user';
    public const RELATION_ORDER_ITEM = 'orderItem';

    protected $table = 'user_plan';

    protected $casts = [
        'end_date'  => 'datetime',
        'start_date' => 'datetime',
        'plan_data' => 'array',
        'features'  => 'array',
    ];

    protected $dates = ['start_date'];

    public function getIsValidAttribute()
    {
        if (!$this->end_date) return true;

        return $this->end_date->timestamp > time();
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'plan_id')->withTrashed();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'create_user');
    }

    public function getUsedAttribute()
    {
        switch ($this->user->role->code ?? '') {
            case "employer":
                if (!$this->user->company) return 0;
                return $this->user->company->jobsPublish()->count('id');
                break;
            case "candidate";
                return $this->user->gigsPublish()->count('id');
                break;
        }
    }

    public function hasFeature(string $slug): bool
    {
        return !empty($this->features[$slug]) && (int)$this->features[$slug] > 0;
    }

    public function decrementFeature(string $slug): void
    {
        $features = $this->features;

        if (!empty($features[$slug])) {
            $features[$slug] = (int)$features[$slug];
            $features[$slug]--;
        }

        foreach ($features as $feature => $value) {
            if ((int)$value <= 0) {
                unset($features[$feature]);
            }
        }

        if (empty($features)) {
            $this->status = self::USED;
        }

        $this->setAttribute('features', $features);
    }

    public function orderItem(): HasOne
    {
        return $this->hasOne(OrderItem::class, 'object_id');
    }
}
