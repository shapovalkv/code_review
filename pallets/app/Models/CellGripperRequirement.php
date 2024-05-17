<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CellGripperRequirement extends Model
{
    use HasFactory;

    protected $table = 'cell_gripper_requirements';

    protected $fillable = [
        'gripper_id',
        'infeed_id',
        'left_side_id',
        'right_side_id',
    ];
}
