<?php

namespace Modules\Company\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\JoinClause;
use Modules\Company\Models\Company;

class CompanyService
{

    public function getCompanyBuilder(?string $s = null, ?int $categoryId = null, ?string $orderBy = null, ?string $orderDirection = null): Builder
    {
        $builder = Company::query();

        if ($categoryId) {
            $builder->where('bc_companies.category_id', $categoryId);
        }
        if ($s) {
            $builder->where(static function (Builder $query) use ($s) {
                foreach (explode(' ', $s) as $str) {
                    $query->orWhere('bc_companies.name', 'LIKE', '%' . $str . '%')
                        ->orWhere('users.first_name', 'LIKE', '%' . $str . '%')
                        ->orWhere('users.last_name', 'LIKE', '%' . $str . '%');
                }
            });
        }

        $builder->select('bc_companies.*')
            ->join('users', 'users.id', '=', 'bc_companies.owner_id');

        if ($orderBy) {
            if ($orderBy === 'plan' || $orderBy === 'plan_expires') {
                $builder
                    ->leftJoin('user_plan', function (JoinClause $join) {
                        $join->on('users.id', '=', 'user_plan.create_user')
                            ->where('user_plan.status', true);
                    });
                if ($orderBy === 'plan') {
                    $builder->leftJoin('bc_plans', 'user_plan.plan_id', '=', 'bc_plans.id')
                        ->orderBy('bc_plans.title', $orderDirection);
                } else if ($orderBy === 'plan_expires') {
                    $builder
                        ->orderBy('user_plan.end_date', $orderDirection);
                }
            } else if ($orderBy === 'employer') {
                $builder->orderBy('users.name', $orderDirection);
            } else {
                $builder->orderBy('bc_companies.' . $orderBy, $orderDirection);
            }
        } else {
            $builder->orderBy('id', 'desc');
        }

        return $builder;
    }

    public function getCompanyPagination(?string $s = null, ?int $categoryId = null, ?string $orderBy = null, ?string $orderDirection = null): LengthAwarePaginator
    {
        $paginator = $this->getCompanyBuilder($s, $categoryId, $orderBy, $orderDirection)->paginate(20);

        $collection = $this->sortCollection(new Collection($paginator->items()), $orderBy, $orderDirection);

        return new \Illuminate\Pagination\LengthAwarePaginator($collection, $paginator->total(), $paginator->perPage(), $paginator->currentPage());
    }

    public function sortCollection(Collection $collection, ?string $orderBy = null, ?string $orderDirection = null): Collection
    {
        if ($orderBy === 'plan') {
            $collection->sortBy(fn(Company $company) => $company->user->currentUserPlan->plan->title ?? null, SORT_REGULAR, $orderDirection === 'desc');
        }

        return $collection;
    }

}
