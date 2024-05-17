<?php

namespace Modules\Candidate\Services;


use Illuminate\Database\Eloquent\Builder;
use Modules\Candidate\Models\Candidate;

class CandidateService
{

    public function getUserBuilder(?string $s = null, ?int $categoryId = null, $allowSearch = null, ?string $status = null, ?string $orderBy = null, ?string $orderDirection = null): Builder
    {
        $builder = Candidate::query()->with(Candidate::RELATION_USER)->whereHas(Candidate::RELATION_USER);
        if (!empty($s)) {
            $builder->whereHas(Candidate::RELATION_USER, static function (Builder $query) use ($s) {
                $query->where(function ($query) use ($s) {
                    foreach (explode(' ', $s) as $str) {
                        $query->orWhere('users.first_name', 'LIKE', '%' . $str . '%');
                        $query->orWhere('users.id', $str);
                        $query->orWhere('users.phone', $str);
                        $query->orWhere('users.email', 'LIKE', '%' . $str . '%');
                        $query->orWhere('users.last_name', 'LIKE', '%' . $str . '%');
                    }
                });
            });
        }

        if (!empty($categoryId)) {
            $builder->whereHas(Candidate::RELATION_CATEGORIES, function($query) use ($categoryId){
                $query->where('cat_id', $categoryId);
            });
        }

        if (!empty($status)) {
            $builder->whereHas(Candidate::RELATION_USER, static function (Builder $query) use ($status) {
                $query->where('status',  $status);
            });
        }

        if (!empty($allowSearch)) {
            $builder->where('allow_search', $allowSearch);
        }

        if ($orderBy) {
            $builder->join('users', 'bc_candidates.id', '=', 'users.id')
                ->orderBy($orderBy, $orderDirection);
        } else {
            $builder->orderBy('id', 'desc');
        }

        return $builder;
    }

}
