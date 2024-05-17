<?php

namespace Modules\User\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Modules\User\Models\UserPlan;

class PlanLogService
{

    public function getGroupedReportBuilder(array $statusIds, array $planIds, ?int $userId = null, ?Carbon $from = null, ?Carbon $to = null, ?string $orderBy = null, ?string $orderDirection = null): Builder
    {

        $builder = UserPlan::query()->with([UserPlan::RELATION_USER, UserPlan::RELATION_PLAN]);

        if ($from) {
            $builder->where('created_at', '>=', $from);
        }

        if ($to) {
            $builder->where('created_at', '<=', $to);
        }

        if ($planIds !== []) {
            $builder->whereIn('plan_id', $planIds);
        }

        if ($statusIds !== []) {
            $builder->whereIn('status', $statusIds);
        }

        if ($userId) {
            $builder->where('create_user', $userId);
        }

        if ($orderBy) {
            if ($orderBy === 'plan') {
                $builder->select('user_plan.*')->join('bc_plans', 'user_plan.plan_id', '=', 'bc_plans.id')->orderBy('bc_plans.title', $orderDirection);
            } elseif ($orderBy === 'user') {
                $builder->select('user_plan.*')->join('users', 'user_plan.create_user', '=', 'users.id')->orderBy('users.name', $orderDirection);
            } else {
                $builder->orderBy($orderBy, $orderDirection);
            }
        } else {
            $builder->orderBy('id', 'desc');
        }

        return $builder;
    }

}
