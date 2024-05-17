<?php

namespace Modules\Job\Models;

use App\BaseModel;

class JobTranslation extends BaseModel
{
    protected $table = 'bc_job_translations';
    protected $fillable = [
        'title',
        'content',
        'key_responsibilities',
        'skills_and_exp'
    ];

    protected $cleanFields = [
        'content'
    ];
}
