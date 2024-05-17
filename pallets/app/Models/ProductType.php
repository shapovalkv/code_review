<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductType extends Model
{
    use HasFactory;

    protected $table = 'product_types';

    protected $fillable = [
        'name',
        'slug',
        'no_infeed_exclusions',
        'lr_infeed_not_compatible',
    ];

    public function lead_product_configuration() : HasOne
    {
        return $this->hasOne(LeadProductConfiguration::class);
    }
}
