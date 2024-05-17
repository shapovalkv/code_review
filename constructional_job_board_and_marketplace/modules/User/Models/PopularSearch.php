<?php

namespace Modules\User\Models;

use App\BaseModel;

class PopularSearch extends BaseModel
{
    protected $table = 'bc_popular_searches';
    protected $fillable = [
        'module',
        'keywords',
        'location',
        'request_count',
        'location_type',
        'location_state',
    ];
}
