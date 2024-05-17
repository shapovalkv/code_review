<?php

namespace App\Services;

use App\Models\Subscription;
use Illuminate\Support\Facades\DB;

class SubscriptionService
{

    public function monthlyRevenueByYear($year) {

        $monMoney = [
            '1' => [ 'name' => 'January', 'money' => 0 ],
            '2' => [ 'name' => 'February', 'money' => 0 ],
            '3' => [ 'name' => 'March', 'money' => 0 ],
            '4' => [ 'name' => 'April', 'money' => 0 ],
            '5' => [ 'name' => 'May', 'money' => 0 ],
            '6' => [ 'name' => 'June', 'money' => 0 ],
            '7' => [ 'name' => 'July', 'money' => 0 ],
            '8' => [ 'name' => 'August', 'money' => 0 ],
            '9' => [ 'name' => 'September', 'money' => 0 ],
            '10' => [ 'name' => 'October', 'money' => 0 ],
            '11' => [ 'name' => 'November', 'money' => 0 ],
            '12' => [ 'name' => 'December', 'money' => 0 ],
        ];

        $rows = Subscription::selectRaw('MONTH(s.created_at) as month, sum(pp.price) as revenue')
            ->from('subscriptions as s')
            ->leftjoin('user_projects as up', 'up.id', '=', 's.user_project_id')
            ->leftjoin('project_plans as pp', 'pp.id', '=', 'up.selected_plan_id')
            ->whereRaw('YEAR(s.created_at) = ' . $year)
            ->groupBy(DB::raw('MONTH(s.created_at)'))
            ->get()
            ->pluck('revenue', 'month');

        foreach($rows as $key => $row) {
            $monMoney[$key]['money'] = $row;
        }

        return $monMoney;
    }

    public function totalActiveSubscriptions()
    {
        return Subscription::query()->where('stripe_status', 'active')->count();
    }

    public function getYears()
    {
        return DB::table('subscriptions')
            ->select(DB::raw('YEAR(created_at) as year'))
            ->distinct()
            ->get()
            ->pluck('year')
            ->toArray();
    }

}
