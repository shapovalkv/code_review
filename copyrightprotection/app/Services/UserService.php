<?php

namespace App\Services;

use App\Http\Requests\Admin\AddUserRequest;
use App\Http\Requests\Admin\EditUserRequest;
use App\Models\User;
use App\Models\UserProject;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function paginatedList($role, $perPage = 10)
    {
        return User::withTrashed()->whereHas('roles', function ($query) use ($role) {
            $query->where('name', $role);
        })->paginate($perPage)->withQueryString();
    }

    public function list($role) {
        return User::whereHas('roles', function ($query) use ($role) {
            $query->where('name', $role);
        })->get();
    }

    public function create(AddUserRequest $request, RoleService $roleService)
    {
        $roleParam = $request->input('role');
        $role = $roleService->checkExists($roleParam);
        if (!$roleService->checkExists($roleParam)) {
            return redirect(route('admin.users'))->with('error', 'Role error!');
        }

        $data = $request->validated();

        $data['password'] = Hash::make($data['password']);

        $user = new User($data);

        if ($user->save()) {
            $user->syncRoles([$role->id]);
            return redirect(route('admin.users', ['role' => $role->name]))->with('success', __('messages.user_created', ['user_role' => $role->title]));
        }

        return redirect(route('admin.users', ['role' => $role->name]))->with('error', __('messages.user_created_fail'));
    }

    public function update(EditUserRequest $request, User $user)
    {
        $data = $request->validated();

        if ($data['password']) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $result = $user->update($data);

        $role = $user->roles()->first();

        if ($result) {
            return redirect(route('admin.users', ['role' => $role->name]))->with('success', __('messages.user_updated', ['user_role' => $role->title]));
        }
        return redirect(route('admin.users', ['role' => $role->name]))->with('error', __('messages.user_updated_fail'));
    }

    public function destroy(User $user)
    {
        $role = $user->roles()->first();
        $result = $user->delete();

        if ($result) {
            return redirect(route('admin.users', ['role' => $role->name]))->with('success', __('messages.user_deleted', ['user_role' => $role->title]));
        }
        return redirect(route('admin.users', ['role' => $role->name]))->with('error', __('messages.user_deleted_fail'));
    }

    public function getContactUserAgent($user)
    {
        $agentData = collect();

        if ($user && $user->hasRole(User::ROLE_CUSTOMER)){
            $project = $user->projects()->with('agent')->where('status', '=', UserProject::ACTIVE)->whereNotNull('assigned_agent_id')->first();
            if ($project){
                $agentData = collect([
                    'agent_name' => $project->agent->first_name . ' ' . $project->agent->last_name,
                    'agent_phone' => $project->agent->phone,
                    'agent_email' => $project->agent->email,
                ]);
            }
        }

        return $agentData;
    }

}
