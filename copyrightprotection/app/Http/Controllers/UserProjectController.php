<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectReportImportRequest;
use App\Imports\ProjectReportImport;
use App\Models\ProjectReport;
use App\Models\User;
use App\Models\UserProject;
use App\Services\ProjectService;
use App\Services\ReportService;
use App\Services\WhitelistedAccountsService;
use App\Services\WhitelistedKeywordsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class UserProjectController extends Controller
{

    public function agentDashboard(ProjectService $projectService, ReportService $reportService)
    {
        $projectCount = $projectService->getAgentProjectsCount(auth()->user());
        $reportsCount = $reportService->getAgentReportsCount(auth()->user());

        return view('agent.dashboard.index', compact('projectCount', 'reportsCount'));
    }

    public function userProject(UserProject $project, ReportService $reportService)
    {
        list($project_reports, $sorter) = $reportService->getReportsAndSorterOfProject($project);
        return view('agent.user-project', [
            'project' => $project,
            'project_reports' =>$project_reports,
            'sorter' =>$sorter,
            'whitelisted_accounts' => $project->whitelistedAccounts()->paginate(10, ['*'], 'whitelisted_accounts'),
            'whitelisted_keywords' => $project->whitelistedKeywords()->paginate(10, ['*'], 'whitelisted_keywords'),
            'legal_documents' => $project->legalDocuments,
        ]);
    }

    public function userReport(UserProject $project, ProjectReport $report)
    {
        return view('agent.user-report', [
            'project' => $project,
            'report' => $report,
            'google_searches' => $report->googleSearchReports()->paginate(10, ['*'], 'google_searches'),
            'google_images' => $report->googleImagesReports()->paginate(10, ['*'], 'google_images'),
            'social_medias' => $report->socialMediaReports()->paginate(10, ['*'], 'social_medias'),
            'at_sources' => $report->atSourceReports()->paginate(10, ['*'], 'at_sources'),
        ]);
    }

    public function exportWhitelistedAccounts(UserProject $project, WhitelistedAccountsService $accountsService)
    {
        return $accountsService->export($project);
    }

    public function exportWhitelistedKeywords(UserProject $project, WhitelistedKeywordsService $keywordsService)
    {
        return $keywordsService->export($project);
    }

    public function report(UserProject $project, ProjectReportImportRequest $request, ReportService $reportService)
    {
        $reportService->report($project, $request);

        return redirect()->back()->with('success', __('messages.report_uploaded'));
    }

    public function exportGoogleSearchReport(ProjectReport $projectReport, ReportService $reportService)
    {
        return $reportService->googgleSearchExport($projectReport);
    }

    public function exportGoogleImagesReport(ProjectReport $projectReport, ReportService $reportService)
    {
        return $reportService->googgleImageExport($projectReport);
    }

    public function exportSocialMediaReport(ProjectReport $projectReport, ReportService $reportService)
    {
        return $reportService->socialMediaExport($projectReport);
    }

    public function exportAtResourceReport(ProjectReport $projectReport, ReportService $reportService)
    {
        return $reportService->atResourceExport($projectReport);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
