<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoogleSearchReport extends Model
{
    use HasFactory;

    protected $table = 'google_search_report';
    protected $primaryKey = 'id';

    protected $fillable = [
        'content',
        'report_id',
    ];
}
