<?php


namespace Modules\User\Models;


use App\BaseModel;
use Carbon\Carbon;

class UserPlan extends BaseModel
{
    protected $table = 'user_plan';

    protected $casts = [
        'end_date' => 'datetime',
        'plan_data' => 'array',
        'features' => 'array',
    ];

    public function getIsValidAttribute()
    {
        if (!$this->end_date) return true;

        return $this->end_date->timestamp > time();
    }

    public function cancelled()
    {
        return $this->cancelled_at !== null;
    }

    public function cancel()
    {
        $this->cancelled_at = Carbon::now();
        return $this->save();
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'create_user');
    }

    public function getUsedAttribute()
    {
        switch ($this->user->role->code ?? '') {
            case "employer":
                if (!$this->user->company) return 0;
                return $this->user->company->jobs()->count('id');
                break;
            case "candidate";
                return $this->user->gigs()->count('id');
                break;
        }
    }
}
