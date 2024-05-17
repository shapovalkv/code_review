<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SocialMediaReport extends Model
{
    use HasFactory;

    protected $table = 'social_media_report';
    protected $primaryKey = 'id';

    protected $fillable = [
        'content',
        'report_id',
    ];
}
