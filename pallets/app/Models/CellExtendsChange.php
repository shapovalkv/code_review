<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class CellExtendsChange extends Model
{
    use HasFactory;

    protected $table = 'cell_extends_changes';

    protected $fillable = [
        'cell_editable_field',
        'cell_editable_from_module_id',
        'cell_editable_to_module_id',
        'cell_substitute_field',
        'cell_substitute_module_id',
    ];

    public function module() : HasOne
    {
        return $this->hasOne(PalletizerModule::class, 'id', 'cell_editable_to_module_id');
    }
}
