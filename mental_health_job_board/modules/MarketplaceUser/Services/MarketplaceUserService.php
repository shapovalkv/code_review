<?php
namespace Modules\MarketplaceUser\Services;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use Modules\MarketplaceUser\Models\MarketplaceUser;

class MarketplaceUserService
{
    public function getMarketplaceUserBuilder(?string $s = null, ?string $status = null, ?string $orderBy = null, ?string $orderDirection = null): Builder
    {
        $builder = MarketplaceUser::query()->with(MarketplaceUser::RELATION_USER);
        if (!empty($s)) {
            $builder->whereHas(MarketplaceUser::RELATION_USER, static function (Builder $query) use ($s) {
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

        if (!empty($status)) {
            $builder->whereHas(MarketplaceUser::RELATION_USER, static function (Builder $query) use ($status) {
                $query->where('status',  $status);
            });
        }

        if ($orderBy) {
            $builder->join('users', 'bc_marketplace_users.id', '=', 'users.id')
                ->orderBy($orderBy, $orderDirection);
        } else {
            $builder->orderBy('id', 'desc');
        }

        return $builder;
    }
}
