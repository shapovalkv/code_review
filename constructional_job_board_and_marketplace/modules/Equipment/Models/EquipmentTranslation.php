<?php

namespace Modules\Equipment\Models;

use App\BaseModel;

class EquipmentTranslation extends BaseModel
{
    protected $table = 'bc_equipment_translations';

    protected $fillable = [
        'title',
        'content',
        'packages',
        'package_compare',
        'requirements',
        'faqs',
    ];

    protected $slugField = false;
    protected $seo_type = 'equipment_translation';

    protected $cleanFields = [
        'content'
    ];
    protected $casts = [
        'packages' => 'array',
        'package_compare' => 'array',
        'requirements' => 'array',
        'faqs' => 'array',
    ];

    public function getSeoType()
    {
        return $this->seo_type;
    }

    public static function boot()
    {
        parent::boot();
        static::saving(function ($table) {
            unset($table->extra_price);
            unset($table->price);
            unset($table->sale_price);
        });
    }
}
