<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhitelistedAccount extends Model
{
    use HasFactory;

    protected $table = 'whitelisted_accounts';
    protected $primaryKey = 'id';

    protected $fillable = [
        'content',
        'user_project_id'
    ];
}
