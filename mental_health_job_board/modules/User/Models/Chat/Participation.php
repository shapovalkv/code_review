<?php

namespace Modules\User\Models\Chat;

use Illuminate\Database\Eloquent\SoftDeletes;
use Musonza\Chat\Models\Participation as Base;

class Participation extends Base
{
    use SoftDeletes;

    public const RELATION_USER = 'messageable';
}
