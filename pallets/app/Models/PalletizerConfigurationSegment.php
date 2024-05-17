<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PalletizerConfigurationSegment extends Model
{
    use HasFactory;

    protected $table = 'palletizer_configurations_segments';

    protected $fillable = ['name', 'slug'];
}
