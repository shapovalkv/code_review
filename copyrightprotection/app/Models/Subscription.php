<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;

class Subscription extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'subscriptions';

    public function plan()
    {
        return $this->hasOne(ProjectPlan::class, 'id', 'name');
    }

    public function userProject()
    {
        return $this->hasOne(UserProject::class, 'id', 'user_project_id');
    }
}
