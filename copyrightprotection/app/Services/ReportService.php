<?php

namespace App\Services;


use App\Events\AgentCreatedNewReportEvent;
use App\Exports\AtResourceReportExport;
use App\Exports\GoogleImagesReportExport;
use App\Exports\GoogleSearchReportExport;
use App\Exports\SocialMediaReportExport;
use App\Helpers\Sorter;
use App\Imports\ProjectReportImport;
use App\Models\ProjectReport;
use App\Models\UserProject;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ReportService
{
    public function report(UserProject $project, $data)
    {
        $projectReport = ProjectReport::create([
            'user_project_id' => $project->id,
            'agent_id' => Auth::id(),
            'report_date' => $data->input('report_date'),
        ]);

        $reportContent = new ProjectReportImport($projectReport->id);

        event(new AgentCreatedNewReportEvent($project));

        return Excel::import($reportContent, $data->file('project_report'));
    }
    public function googgleSearchExport(ProjectReport $projectReport)
    {
        $date = Carbon::now()->format('Ymd_His');
        return Excel::download(new GoogleSearchReportExport($projectReport), 'googleSearch_'.$projectReport->name.'_'.$date.'.xlsx');
    }

    public function googgleImageExport(ProjectReport $projectReport)
    {
        $date = Carbon::now()->format('Ymd_His');
        return Excel::download(new GoogleImagesReportExport($projectReport), 'googleImages_'.$projectReport->name.'_'.$date.'.xlsx');
    }

    public function socialMediaExport(ProjectReport $projectReport)
    {
        $date = Carbon::now()->format('Ymd_His');
        return Excel::download(new SocialMediaReportExport($projectReport), 'socialMedia_'.$projectReport->name.'_'.$date.'.xlsx');
    }

    public function atResourceExport(ProjectReport $projectReport)
    {
        $date = Carbon::now()->format('Ymd_His');
        return Excel::download(new AtResourceReportExport($projectReport), 'atResource_'.$projectReport->name.'_'.$date.'.xlsx');
    }

    public function getAgentReportsCount()
    {
        return auth()->user()->getAgentReports()->count();
    }

    public static function getReportsAndSorterOfProject($project, $perPage = 10)
    {
        //param name => table column name
        $orderByColumns = [
            'sortByAuthor' => 'author',
            'sortByReportDate' => 'report_date',
            'sortByGoogleSearch' => 'google_search_count',
            'sortByGoogleImage' => 'google_image_count',
            'sortBySocialMedia' => 'social_media_count',
            'sortByATSource' => 'at_source_count',
        ];
        $sorter = new Sorter($orderByColumns);


        $reports = UserProject::withTrashed()
            ->selectRaw('pr.id, pr.report_date AS report_date,user_project_id as project_id,
                CONCAT(u.first_name, \' \', u.last_name) as author,
                SUM(CASE WHEN rc.type = \'google_search\' THEN 1 ELSE 0 END) AS google_search_count,
                SUM(CASE WHEN rc.type = \'google_image\' THEN 1 ELSE 0 END) AS google_image_count,
                SUM(CASE WHEN rc.type = \'social_media\' THEN 1 ELSE 0 END) AS social_media_count,
                SUM(CASE WHEN rc.type = \'at_source\' THEN 1 ELSE 0 END) AS at_source_count')
            ->from('project_reports as pr')
            ->leftjoin('report_content as rc', 'rc.report_id', '=', 'pr.id')
            ->leftjoin('users as u', 'u.id', '=', 'pr.agent_id')
            ->whereRaw('pr.user_project_id = ' . $project->id)
            ->groupBy('pr.id');

        $reports->orderBy($sorter->getOrderByColumn(), $sorter->getOrderByDirection());


        if ($perPage === 'All') {
            $reports = $reports->get();
        } else {
            $reports = $reports->paginate($perPage)->withQueryString();
        }

        return [$reports, $sorter];
    }

}
