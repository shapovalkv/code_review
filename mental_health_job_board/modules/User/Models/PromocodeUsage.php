<?php


namespace Modules\User\Models;


use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property int user_id
 * @property int promocode_id
 */
class PromocodeUsage extends Pivot
{
    protected $table = 'promocodes_usages';
}
