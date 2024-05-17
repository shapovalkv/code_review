<?php

namespace Modules\Job\Models;

use App\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kalnoy\Nestedset\NodeTrait;

class JobPosition extends BaseModel
{
    use SoftDeletes;
    use NodeTrait;
    protected $table = 'bc_job_positions';
    protected $fillable = [
        'name',
        'description',
        'status',
    ];
    protected $slugField     = 'slug';
    protected $slugFromField = 'name';
    protected $seo_type = 'job_position';

    public static function getModelName()
    {
        return __("Position");
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
}
