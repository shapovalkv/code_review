<?php

namespace Modules\Job\Exports;

use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Modules\Job\Models\Job;
use Modules\Job\Models\JobCandidate;
use Modules\User\Models\Subscriber;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AppliedJobsExport implements FromCollection, WithHeadings, WithMapping
{
    use Exportable;

    public function collection()
    {
        return call_user_func([JobCandidate::class, 'search'], request(), null, Auth::id());
    }

    /**
     * @return array
     * @var Job $job
     */
    public function map($job): array
    {
        return [
            ltrim($job->jobInfo->title, "=-"),
            ltrim($job->jobInfo->content, "=-"),
            ltrim($job->jobInfo->category->name, "=-"),
            ltrim($job->jobInfo->hours, "=-"),
            ltrim($job->jobInfo->salary_type, "=-"),
            ltrim($job->jobInfo->salary_min, "=-"),
            ltrim($job->jobInfo->salary_min, "=-"),
            ltrim(display_date($job->created_date), "=-"),
            ltrim($job->status, "=-"),
        ];
    }

    public function headings(): array
    {
        return [
            'Job Title',
            'Job Content',
            'Job Category',
            'Job hours',
            'Job salary type',
            'Job salary_min',
            'Job salary_max',
            'Date Applied',
            'Status',
        ];
    }
}
