<?php

namespace Modules\Equipment\Models;

use App\BaseModel;
use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipmentCategory extends BaseModel
{
    use SoftDeletes;
    use NodeTrait;

    protected $table = 'bc_equipment_cat';
    protected $fillable = [
        'name',
        'content',
        'slug',
        'status',
        'parent_id',
        'news_cat_id',
        'image_id',
        'faqs',
    ];
    protected $slugField = 'slug';
    protected $slugFromField = 'name';

    protected $casts = [
        'faqs' => 'array'
    ];
    protected $attributes = [
        'status' => 'publish'
    ];
    protected $hidden = ['_lft', '_rgt'];

    public static function getModelName()
    {
        return __("equipment Category");
    }

    public static function searchForMenu($q = false)
    {
        $query = static::select('id', 'name');
        if (strlen($q)) {
            $query->where('name', 'like', "%" . $q . "%");
        }
        $a = $query->limit(10)->get();
        return $a;
    }

    public function getDetailUrl()
    {
        return route('equipment.category', ['slug' => $this->slug]);
    }

    public static function getLinkForPageSearch($locale = false, $param = [])
    {
        return route('equipment.index', $param);
    }

    public function dataForApi()
    {
        $translation = $this->translateOrOrigin(app()->getLocale());
        return [
            'id' => $this->id,
            'name' => $translation->name,
            'slug' => $this->slug,
        ];
    }

    public function types()
    {
        return $this->hasMany(EquipmentCategoryType::class, 'cat_id');
    }

    public function openEquipment()
    {
        return $this->hasMany(Equipment::class, 'cat_id', 'id')
            ->where('status', 'publish');
    }

}
