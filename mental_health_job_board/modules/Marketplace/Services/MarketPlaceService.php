<?php

namespace Modules\Marketplace\Services;

use App\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Modules\Marketplace\Models\Marketplace;
use Modules\User\Models\UserPlan;

class MarketPlaceService
{
    public function countAnnouncementsInThisPeriod(User $user): int
    {
        $user->load(User::RELATION_USER_PLAN);

        $userPlan = $user->currentUserPlan;
        $userPlan?->load(UserPlan::RELATION_PLAN);

        // calculate months from start user plan
        $wholeMonthsFromStartOfPlan = (int)ceil($userPlan->start_date->endOfDay()->floatDiffInMonths(Carbon::now()->endOfDay()));
        // calculate last day in this month for period of user plan
        $lastDateOfPeriod = $userPlan->start_date->endOfDay()->addMonths($wholeMonthsFromStartOfPlan);
        $firstDateOfPeriod = (clone $lastDateOfPeriod)->startOfDay()->subMonth();

        return Marketplace::withTrashed()
            ->where('author_id', $user->id)
            ->whereDate('created_at', '>=', $firstDateOfPeriod)
            ->whereDate('created_at', '<=', $lastDateOfPeriod)
            ->whereNotNull('expiration_date')
            ->where('is_package', 0)
            ->whereDoesntHave(Marketplace::RELATION_ORDER_ITEM)
            ->count();
    }

    public function fillByAttrForCreateMarketplace(Marketplace $marketplace): Marketplace
    {
        $attr = [
            'title',
            'content',
            'cat_id',
            'website',
            'video_url',
            'video_cover_image_id',
            'image_id',
            'gallery',
            'thumbnail_id',
            'location_id',
            'map_lat',
            'map_lng',
            'map_zoom',
            'announcement_date',
            'announcement_status',
        ];

        $marketplace->fillByAttr($attr, Cache::get(auth()->id() . Marketplace::CACHE_KEY_DRAFT));

        return $marketplace;
    }
}
