<?php

namespace Modules\User\Services;

use App\User;
use Illuminate\Database\Eloquent\Builder;

class UserService
{
    public function getUserBuilder(?string $s = null, ?string $role = null, ?string $orderBy = null, ?string $orderDirection = null): Builder
    {
        $builder = User::query();

        if (!empty($s)) {
            $builder->where(function (Builder $query) use ($s) {
                foreach (explode(' ', $s) as $str) {
                    $query->orWhere('first_name', 'LIKE', '%' . $str . '%');
                    $query->orWhere('users.id', $str);
                    $query->orWhere('phone', $str);
                    $query->orWhere('email', 'LIKE', '%' . $str . '%');
                    $query->orWhere('last_name', 'LIKE', '%' . $str . '%');
                }
            });
        }

        if ($role) {
            $builder->whereHas(User::RELATION_ROLE, static function(Builder $query) use ($role) {
                $query->where('name', $role);
            });
        }

        if ($orderBy) {
            if ($orderBy === 'role') {
                $builder->join('core_roles', 'users.role_id', '=', 'core_roles.id')->orderBy('core_roles.name', $orderDirection);
            } else {
                $builder->orderBy($orderBy, $orderDirection);
            }
        } else {
            $builder->orderBy('id', 'desc');
        }

        return $builder;
    }
}
