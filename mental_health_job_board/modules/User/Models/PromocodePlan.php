<?php


namespace Modules\User\Models;


use Illuminate\Database\Eloquent\Relations\Pivot;

class PromocodePlan extends Pivot
{
    protected $table = 'promocodes_plans';
}
