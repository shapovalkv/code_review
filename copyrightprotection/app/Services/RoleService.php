<?php

namespace App\Services;

use Spatie\Permission\Models\Role;

class RoleService
{
    public function checkExists($role)
    {
        return Role::where('name', $role)->first();
    }

}
