<?php

namespace App\Http\Controllers;

use App\Models\UserProject;
use App\Services\DashboardService;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, DashboardService $dashboardService, ReportService $reportService)
    {
        $user = auth()->user();
        $project = $user->selectedProject;

        list($project_reports, $sorter) = $reportService->getReportsAndSorterOfProject($project);
        return view('user.dashboard', [
            'project_reports' => $project_reports,
            'sorter' => $sorter,
            'monthly_chart_data' => $dashboardService->getMonthlyChartData($project, $request)->paginate(1, ['*'], 'monthly_chart_data')->withQueryString(),
            'reportYears' => $project->projectReportsYears(),
            'chartDataLinks' => $dashboardService->monthlyLinksByYear($request),
        ]);
    }

    public function createProject()
    {
        return view('user.create-project-wizard');
    }

    public function selectProject(Request $request, UserProject $project, DashboardService $dashboardService)
    {
        if (!$project) {
            return response()->json(['message' => 'Project did\'nt find'], 404);
        }

        $dashboardService->updateSelectedProject($project);
        $redirectResponse = Redirect::back();
        $previousUrl = $redirectResponse->getTargetUrl();

        return redirect(strtok($previousUrl, '?'));
    }
}
