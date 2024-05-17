<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;

class Plan extends Model
{
    use HasFactory, HasApiTokens, Notifiable, Billable;

    protected $table = 'project_plans';

    protected $fillable = [
        'name',
        'slug',
        'stripe_plan',
        'price',
        'description',
    ];


    public function getRouteKeyName()
    {
        return 'slug';
    }
}
