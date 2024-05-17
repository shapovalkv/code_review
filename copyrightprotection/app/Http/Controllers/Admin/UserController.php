<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AddUserRequest;
use App\Http\Requests\Admin\EditUserRequest;
use App\Models\User;
use App\Services\ProjectService;
use App\Services\RoleService;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index(Request $request, UserService $userService, RoleService $roleService)
    {
        $roleParam = $request->query('role', User::ROLE_CUSTOMER);
        $role = $roleService->checkExists($roleParam);
        if (!$role) {
            abort(404);
        }
        $users = $userService->paginatedList($role->name);
        return view('admin.users.index', compact('users', 'role'));
    }

    public function create(Request $request, RoleService $roleService)
    {
        $roleParam = $request->role;
        $role = $roleService->checkExists($roleParam);
        if (!$role) {
            abort(404);
        }

        return view('admin.users.create', compact('role'));
    }

    public function store(AddUserRequest $request, RoleService $roleService, UserService $userService)
    {
        return $userService->create($request, $roleService);
    }

    public function edit(Request $request, User $user)
    {
        $role = $user->roles()->first();
        return view('admin.users.edit', compact('user', 'role'));
    }

    public function update(EditUserRequest $request, User $user, UserService $userService)
    {
        return $userService->update($request, $user);
    }

    public function destroy(User $user, UserService $userService)
    {
        return $userService->destroy($user);
    }

    public function customer(ProjectService $projectService, User $customer)
    {
        if(auth()->user()->hasRole(User::ROLE_AGENT)) {
            $projects = $projectService->getUserProjectsOfAgent($customer, auth()->user(), 10);
        } else {
            $projects = $projectService->getUserProjects($customer, 10);
        }
        return view('admin.users.customers.view', compact('customer', 'projects'));
    }

}
