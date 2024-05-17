<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class EAOTDetail extends Model
{
    use HasFactory;

    protected $table = 'eoat_details';

    protected $fillable = [
        'name',
        'weight',
        'z_height',
        'y_offset',
    ];


    public function cadModel(): MorphOne
    {
        return $this->morphOne(CadModel::class, 'cad_modelable');
    }

    public function palletizerModule(): BelongsTo
    {
        return $this->BelongsTo(PalletizerModule::class);
    }
}
