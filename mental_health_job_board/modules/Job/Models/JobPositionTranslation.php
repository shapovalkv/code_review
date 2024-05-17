<?php
namespace Modules\Job\Models;

use App\BaseModel;
use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobPositionTranslation extends BaseModel
{
    protected $table = 'bc_job_position_translations';
    protected $fillable = ['name', 'description'];
    protected $seo_type = 'job_position_translation';
    protected $cleanFields = [
        'description'
    ];
}
