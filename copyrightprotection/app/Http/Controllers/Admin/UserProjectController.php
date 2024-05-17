<?php

namespace App\Http\Controllers\Admin;

use App\Events\CopyrightAssignedEvent;
use App\Events\CopyrightUnassignedEvent;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProject;
use App\Services\PlanService;
use App\Services\ProjectService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ProjectService $projectService, UserService $userService, PlanService $planService)
    {
        $user = auth()->user();

        $plans = $planService->list();
        if ($user->hasRole(User::ROLE_AGENT)) {
            $customers = $projectService->getAgentProjectOwners($user);
        } else {
            $customers = $userService->list(User::ROLE_CUSTOMER);
        }

        $agents = $userService->list(User::ROLE_AGENT);

        $perPage = request()->input('perPage', 10);
        list($projects, $sorter) = $projectService->getProjectsAndSorter($perPage);

        return view('admin.projects.index',
            compact('projects', 'sorter', 'agents', 'customers', 'plans', 'perPage'));
    }

    public function assignAgent(Request $request, UserProject $project)
    {
        $agentId = $request->agentId !== '0' ? $request->agentId : null;
        $assignedAgentId = $project->assigned_agent_id;
        $project->assigned_agent_id = $agentId;
        $result = (bool)$project->save();

        $successMessage = (bool)$agentId ? __('messages.agent_assigned') : __('messages.agent_unassigned');
        $errorMessage = __('messages.agent_assigned_fail');
        if (!is_null($agentId)) {
            try {
                event(new CopyrightAssignedEvent($project, User::find($agentId)));
            } catch (\Exception $e) {}

            if (isset($e)) {
                $result = false;
            }
        }

        if ($assignedAgentId) {
            try {
                event(new CopyrightUnassignedEvent($project, User::find($assignedAgentId)));
            } catch (\Exception $e) {}

            if (isset($e)) {
                $result = false;
            }
        }

        return response()->json([
            'message' => $result ? $successMessage : $errorMessage,
            'success' => $result,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
