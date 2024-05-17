<?php

namespace Modules\Equipment\Models;

use App\BaseModel;

class EquipmentCategoryTranslation extends BaseModel
{
    protected $table = 'bc_equipment_cat_trans';
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
