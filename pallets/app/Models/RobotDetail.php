<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RobotDetail extends Model
{
    use HasFactory;

    protected $with = [
        'palletizer_module.cadModel'
    ];

    protected $table = 'robot_details';

    protected $fillable = [
        'brand',
        'model_number',
        'palletizer_module_id',
        'payload_weight',
        'reach_distance',
        'concatenated_description',
        'robot_base_height',
        'reach_center_height',
        'max_floor_reach',
        'max_conveyor_reach',
        'reach_required',
        'in_scope',
        'payload',
        'reach',
        'valid',
    ];

    public function palletizer_module(): HasOne
    {
        return $this->hasOne(PalletizerModule::class, 'id','palletizer_module_id');
    }
}
