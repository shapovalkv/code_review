<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'job_title',
        'company',
        'status',
        'local_distributor',
        'hs_contact_id'
    ];

    public function lead_product_configuration() : HasOne
    {
        return $this->hasOne(LeadProductConfiguration::class);
    }
}
