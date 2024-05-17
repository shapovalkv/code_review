<?php

namespace App\Services;

use App\Models\Subscription;
use App\Models\UserProject;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;

class DashboardService
{
    public function updateSelectedProject(UserProject $project)
    {
        return Auth::user()->update(['selected_project_id' => $project->id]);
    }

    public function getMonthlyChartData(UserProject $project, $data)
    {
        return $project
            ->projectReports()
            ->whereMonth('report_date', $data->monthly_chart_data_month ?? Carbon::now()->month)
            ->whereYear('report_date', $data->monthly_chart_data_year ?? Carbon::now()->year)
            ->with(['googleSearchReports', 'googleImagesReports', 'socialMediaReports', 'atSourceReports']);
    }

    public function monthlyLinksByYear($data) {

        $monLinks = [
            '1' => [ 'name' => 'January', 'links' => 0 ],
            '2' => [ 'name' => 'February', 'links' => 0 ],
            '3' => [ 'name' => 'March', 'links' => 0 ],
            '4' => [ 'name' => 'April', 'links' => 0 ],
            '5' => [ 'name' => 'May', 'links' => 0 ],
            '6' => [ 'name' => 'June', 'links' => 0 ],
            '7' => [ 'name' => 'July', 'links' => 0 ],
            '8' => [ 'name' => 'August', 'links' => 0 ],
            '9' => [ 'name' => 'September', 'links' => 0 ],
            '10' => [ 'name' => 'October', 'links' => 0 ],
            '11' => [ 'name' => 'November', 'links' => 0 ],
            '12' => [ 'name' => 'December', 'links' => 0 ],
        ];

        $rows = Subscription::selectRaw('count(c.id) as links_count, month(r.report_date) as month')
            ->from('report_content as c')
            ->leftjoin('project_reports as r', 'c.report_id', '=', 'r.id')
            ->leftjoin('user_projects as p', 'r.user_project_id', '=', 'p.id')
            ->whereYear('r.report_date', $data->links_chart_data_year ?? Carbon::now()->year)
            ->where('p.id', auth()->user()->selected_project_id)
            ->groupBy(DB::raw('MONTH(r.report_date)'))
            ->get()
            ->pluck('links_count', 'month');

        foreach($rows as $key => $row) {
            $monLinks[$key]['links'] = $row;
        }

        return $monLinks;
    }


}
