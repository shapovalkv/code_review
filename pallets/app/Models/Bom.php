<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bom extends Model
{
    use HasFactory;

    protected $table = 'bom';

    protected $fillable = [
        'palletizer_module_id',
        'part_number',
        'description',
        'qty',
        'in_assy',
        'purchased_in_assy',
        'in_boom',
        'boom_category',
        'manufacturer',
        'vendor',
        'price_each',
        'quoted_price',
        'notes',
    ];
}
