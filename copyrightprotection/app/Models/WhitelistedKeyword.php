<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhitelistedKeyword extends Model
{
    use HasFactory;

    protected $table = 'whitelisted_keywords';
    protected $primaryKey = 'id';

    protected $fillable = [
        'content',
        'user_project_id'
    ];
}
