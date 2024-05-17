<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Laravel\Cashier\Billable;

class UserProject extends Model
{
    use SoftDeletes, HasFactory, Billable;

    protected $table = 'user_projects';
    protected $primaryKey = 'id';

    const DRAFT = 'draft';
    const CREATED = 'created';
    const ACTIVE = 'active';
    const IN_ACTIVE = 'in_active';

    const STATUSES = [
        self::DRAFT => 'Draft',
        self::CREATED => 'Created',
        self::ACTIVE => 'Active',
        self::IN_ACTIVE => 'In active',
    ];

    protected $fillable = [
        'name',
        'user_id',
        'step'
    ];
    private int $step;
    private string $status;

    public function author()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function agent()
    {
        return $this->hasOne(User::class, 'id', 'assigned_agent_id');
    }

    public function whitelistedAccounts()
    {
        return $this->hasMany(WhitelistedAccount::class, 'user_project_id');
    }

    public function whitelistedKeywords()
    {
        return $this->hasMany(WhitelistedKeyword::class, 'user_project_id');
    }

    public function legalDocuments()
    {
        return $this->hasMany(File::class, 'user_project_id');
    }

    public function projectReports()
    {
        return $this->hasMany(ProjectReport::class, 'user_project_id');
    }

    public function projectReportsYears()
    {
        return $this->hasMany(ProjectReport::class, 'user_project_id')
            ->select(DB::raw('YEAR(report_date) as year'))
            ->distinct()
            ->orderBy('year', 'desc')
            ->get()
            ->pluck('year')
            ->toArray();
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function projectSubscription()
    {
        return $this->hasOne(Subscription::class, 'user_project_id', 'id');
    }

    public function plan()
    {
        return $this->hasOne(Plan::class, 'id', 'selected_plan_id');
    }
}
