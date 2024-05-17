<?php

namespace Modules\Equipment\Models;

use App\BaseModel;
use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipmentCategoryTypeTranslation extends BaseModel
{
    protected $table = 'bc_equipment_cat_type_trans';
    protected $fillable = [
        'name',
        'content',
    ];

}
