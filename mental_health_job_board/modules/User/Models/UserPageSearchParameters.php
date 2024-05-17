<?php

namespace Modules\User\Models;

use App\BaseModel;

class UserPageSearchParameters extends BaseModel
{
    protected $table = 'bc_user_page_search_parameters';

    protected $fillable = [
        'name',
        'page',
        'user_id',
        'parameters'
    ];

    protected $casts = [
        'parameters' => 'array',
    ];

}
