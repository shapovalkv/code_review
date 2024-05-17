<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeasurementSystem extends Model
{
    use HasFactory;

    protected $table = 'measurement_config';

    protected $fillable = [
        'field',
        'value',
    ];
}
