<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class PalletizerModule extends Model
{
    use HasFactory;

    protected $table = 'palletizer_modules';

    protected $fillable = [
        'configuration',
        'assembly_no',
    ];

    protected $with = ['cadModel'];

    public function category() : HasOne
    {
        return $this->hasOne(PalletizerConfigurationSegment::class, 'id','segment_id');
    }

    public function gripperRequirements() : HasMany
    {
        return $this->hasMany(CellGripperRequirement::class, 'id','gripper_id');
    }

    public function eoatDetail() : HasOne
    {
        return $this->hasOne(EAOTDetail::class, 'palletizer_module_id','id');
    }

    public function palletConveyor() : HasOne
    {
        return $this->hasOne(PalletConveyor::class, 'palletizer_module_id','id');
    }

    public function boomItems() : HasMany
    {
        return $this->hasMany(Bom::class, 'palletizer_module_id', 'id');
    }

    public function cadModel(): MorphOne
    {
        return $this->morphOne(CadModel::class, 'cad_modelable');
    }

    public function robotDetail(): HasOne
    {
        return $this->hasOne(RobotDetail::class, 'palletizer_module_id','id');
    }
}
