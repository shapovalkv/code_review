<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ProjectService;
use App\Services\SubscriptionService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request, SubscriptionService $subscriptionService, ProjectService $projectService)
    {
        $selectedYear = (integer) $request->get('year', Carbon::now()->format('Y'));

        $years = $subscriptionService->getYears();
        $chartData = $subscriptionService->monthlyRevenueByYear($selectedYear);
        $totalProjects = $projectService->totalProjects();
        $totalActiveSubscriptions = $subscriptionService->totalActiveSubscriptions();

        return view('admin.dashboard.index', compact('years', 'selectedYear', 'chartData', 'totalProjects', 'totalActiveSubscriptions'));
    }
}
