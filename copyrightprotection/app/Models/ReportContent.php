<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportContent extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'report_content';
    protected $primaryKey = 'id';
    protected $fillable = [
        'report_id',
        'type',
        'content',
    ];
}
