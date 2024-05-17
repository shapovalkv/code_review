<?php

namespace Modules\Marketplace\Models;

use App\BaseModel;

class MarketplaceCategoryTranslation extends BaseModel
{
    protected $table = 'bc_marketplace_cat_trans';
    protected $fillable = [
        'name',
        'content',
        'faqs'
    ];
    protected $cleanFields = [
        'content'
    ];
    protected $casts = [
        'faqs' => 'array'
    ];
}
