<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use SoftDeletes, HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    const ROLE_AGENT = 'agent';
    const ROLE_CUSTOMER = 'customer';
    const ROLE_ADMIN = 'super_admin';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'selected_project_id',
        'social_id',
        'social_type'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function projects()
    {
        return $this->hasMany(UserProject::class,'user_id');
    }

    public function getSelectedProject($projectId)
    {
        return $this->hasOne(UserProject::class, 'user_id')->where('id', $projectId)->first();
    }

    public function selectedProject()
    {
        return $this->hasOne(UserProject::class, 'id', 'selected_project_id');
    }

    public function newProject()
    {
        return $this->hasOne(UserProject::class)->where('status', '=', UserProject::DRAFT)->first();
    }

    public function getAgentProjects()
    {
        return $this->hasMany(UserProject::class,'assigned_agent_id');
    }

    public function getAgentReports()
    {
        return $this->hasMany(ProjectReport::class,'agent_id');
    }

    public function AgentAssignedToProject(UserProject $project)
    {
        return (bool) $this->getAgentProjects()->where('user_projects.id', $project->id)->count();
    }

    public function CustomerHasProject(UserProject $project)
    {
        return (bool) $this->projects()->where('user_projects.id', $project->id)->count();
    }

}
