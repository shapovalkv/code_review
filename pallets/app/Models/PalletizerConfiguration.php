<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PalletizerConfiguration extends Model
{
    use HasFactory;

    protected $table = 'palletizer_configurations';

    protected $fillable = ['name','slug', 'segment', 'cost', ];
}
