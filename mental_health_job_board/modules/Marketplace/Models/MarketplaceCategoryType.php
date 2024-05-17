<?php

namespace Modules\Marketplace\Models;

use App\BaseModel;
use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarketplaceCategoryType extends BaseModel
{
    use SoftDeletes;

    protected $table = 'bc_marketplace_cat_types';
    protected $fillable = [
        'name',
        'content',
        'slug',
        'status',
        'cat_id',
        'image_id'
    ];
    protected $slugField = 'slug';
    protected $slugFromField = 'name';

    protected $attributes = [
        'status' => 'publish'
    ];

    protected $casts = [
        'cat_children' => 'array'
    ];

    public static function getModelName()
    {
        return __("Marketplace Category Type");
    }

    public function cat()
    {
        return $this->belongsTo(MarketplaceCategory::class, 'cat_id');
    }


    public function children()
    {
        if (empty($this->cat_children) and !is_array($this->cat_children)) return [];

        return MarketplaceCategory::query()->whereIn('id', $this->cat_children)->get();
    }
}
