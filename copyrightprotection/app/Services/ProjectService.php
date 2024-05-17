<?php

namespace App\Services;

use App\Helpers\Sorter;
use App\Models\User;
use App\Models\UserProject;
use Illuminate\Support\Facades\Auth;

class ProjectService
{
    public function getAgentProjectsCount(User $agent)
    {
        return UserProject::where('assigned_agent_id', $agent->id)->count();
    }

    public function allProjects($perPage = 10) {
        return UserProject::with('author', 'subscription', 'plan')->paginate($perPage);
    }

    public function getAgentProjectOwners(User $agent)
    {
        $ids = UserProject::selectRaw('DISTINCT(user_id)')
            ->where('assigned_agent_id', $agent->id)
            ->get()
            ->pluck('user_id')
            ->toArray();

        return User::find($ids);

    }

    public static function getProjectsAndSorter($perPage = 10)
    {
        //param name => table column name
        $orderByColumns = [
            'sortByUser' => 'customer',
            'sortByName' => 'name',
            'sortByStatus' => 'up.status',
            'sortByPlan' => 'plan_price',
            'sortByAgent' => 'agent',
            'sortByReportDate' => 'pr.report_date',
        ];
        $sorter = new Sorter($orderByColumns);


        $projects = UserProject::withTrashed()
            ->selectRaw('up.id as id, up.name as name, up.status as status,
        CONCAT(u.first_name, \' \', u.last_name) as customer, up.user_id as customer_id,
        CONCAT(a.first_name, \' \', a.last_name) as agent, a.id as agent_id,
        u.email as customer_email, u.phone as customer_phone,
        p.name as plan_name, p.id as plan_id, p.price as plan_price, pr.report_date')
            ->from('user_projects as up')
            ->leftjoin('users as u', 'up.user_id', '=', 'u.id')
            ->leftjoin('users as a', 'up.assigned_agent_id', '=', 'a.id')
            ->leftjoin('project_plans as p', 'up.selected_plan_id', '=', 'p.id')
            ->leftjoin('project_reports as pr', 'up.id', '=', 'pr.user_project_id')
            ->whereRaw('(pr.id = (SELECT id FROM project_reports WHERE user_project_id = up.id ORDER BY report_date DESC LIMIT 1) OR pr.id is null)')
            ->whereNull('up.deleted_at');

        $filterByUser = request()->input('filterByUser');
        $filterByStatus = request()->input('filterByStatus');
        $filterByPlan = request()->input('filterByPlan');
        $filterByAgent = request()->input('filterByAgent');

        $currentUser = auth()->user();
        if ($currentUser->hasRole(USER::ROLE_AGENT)) {
            $filterByAgent = $currentUser->id;
        }

        $projects
            ->when($filterByUser, function ($q) use ($filterByUser) {
                return $q->where('up.user_id', $filterByUser);
            })
            ->when($filterByStatus, function ($q) use ($filterByStatus) {
                return $q->where('status', $filterByStatus);
            })
            ->when($filterByPlan, function ($q) use ($filterByPlan) {
                return $q->where('p.id', $filterByPlan);
            })
            ->when($filterByAgent, function ($q) use ($filterByAgent) {
                if ($filterByAgent === '-1') {
                    return $q->whereNull('a.id');
                } else {
                    return $q->where('a.id', $filterByAgent);
                }
            });

        $projects->orderBy($sorter->getOrderByColumn(), $sorter->getOrderByDirection());


        if ($perPage === 'All') {
            $projects = $projects->get();
        } else {
            $projects = $projects->paginate($perPage)->withQueryString();
        }

        return [$projects, $sorter];
    }

    public function getUserProjects(User $user, $perPage = 10)
    {
        return $user->projects()->with('plan')->paginate($perPage);
    }

    public function getUserProjectsOfAgent(User $user, User $agent, $perPage = 10)
    {
        return $user->projects()->where('assigned_agent_id', $agent->id)->with('plan')->paginate($perPage);
    }

    public function totalProjects()
    {
        return UserProject::query()->count();
    }


}
