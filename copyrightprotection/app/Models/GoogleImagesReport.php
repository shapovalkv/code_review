<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoogleImagesReport extends Model
{
    use HasFactory;

    protected $table = 'google_images_report';
    protected $primaryKey = 'id';

    protected $fillable = [
        'content',
        'report_id',
    ];
}
