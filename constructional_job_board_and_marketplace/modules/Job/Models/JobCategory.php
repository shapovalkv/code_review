<?php

namespace Modules\Job\Models;

use App\BaseModel;
use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use phpDocumentor\Reflection\Types\This;

class JobCategory extends BaseModel
{
    use SoftDeletes;
    use NodeTrait;

    protected $table = 'bc_categories';
    protected $fillable = [
        'name',
        'content',
        'status',
        'parent_id',
        'icon',
        'image_id'
    ];
    protected $slugField = 'slug';
    protected $slugFromField = 'name';
    protected $seo_type = 'job_category';
    protected $hidden = ['_lft', '_rgt'];

    public static function scopeBySlug($query, $slug)
    {
        return $query->where('slug', $slug);;
    }

    public static function getModelName()
    {
        return __("Category");
    }

    public static function searchForMenu($q = false)
    {
        $query = static::select('id', 'name');
        if (strlen($q)) {

            $query->where('title', 'name', "%" . $q . "%");
        }
        $a = $query->limit(10)->get();
        return $a;
    }

    public function openJobs()
    {
        return $this->hasMany(Job::class, 'category_id', 'id')
            ->where('expiration_date', '>=', date('Y-m-d H:s:i'))
            ->where('status', 'publish');
    }

    public function getDetailUrl($locale = false)
    {
        return route('job.detail', ['slug' => $this->slug]);
    }


    public function dataForApi($forSingle = false)
    {
        $translation = $this->translateOrOrigin(app()->getLocale());
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $translation->name,
            'image_url' => $this->getImageUrlAttribute(),
            'open_jobs_count' => $this->openJobs()->count() + $this->descendants->map(function ($subCategory) {
                    return $subCategory->openJobs()->count();
                })->sum(),
            'descendants' => $this->descendants->map(function ($subCategory) {
                $subCategory->open_jobs_count = $subCategory->openJobs()->count();
                return $subCategory->only(['id', 'name', 'slug', 'name', 'content', 'image_url', 'open_jobs_count']);
            })
        ];
    }
}
