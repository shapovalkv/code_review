<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class PalletConveyor extends Model
{
    use HasFactory;

    protected $table = 'pallet_conveyor';

    protected $fillable = [
        'palletizer_module_id',
        'is_pallet',
        'is_conveyor',
    ];

    public function palletizer_module(): HasOne
    {
        return $this->hasOne(PalletizerModule::class, 'id','palletizer_module_id');
    }
}
