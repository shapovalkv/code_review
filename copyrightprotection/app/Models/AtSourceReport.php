<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AtSourceReport extends Model
{
    use HasFactory;

    protected $table = 'at_resorce_report';
    protected $primaryKey = 'id';
    protected $fillable = [
        'content',
        'report_id',
    ];
}
