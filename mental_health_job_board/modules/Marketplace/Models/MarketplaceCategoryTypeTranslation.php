<?php

namespace Modules\Marketplace\Models;

use App\BaseModel;
use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarketplaceCategoryTypeTranslation extends BaseModel
{
    protected $table = 'bc_marketplace_cat_type_trans';
    protected $fillable = [
        'name',
        'content',
    ];

}
