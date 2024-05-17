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

class JobsExport implements FromCollection, WithHeadings, WithMapping
{
    use Exportable;

    public function collection()
    {
        $candidateId = request()->query('candidate_id');
        $jobs = Job::with(['location', 'translations', 'category', 'company', 'jobType', 'skills', 'candidates', 'candidates.candidateInfo.user'])
            ->whereHas('company', function ($query) {
                $jobId = request()->query('job_id');
                $companyId = request()->query('company_id');
                if (!Auth::user()->hasPermission('job_manage_others')) {
                    $companyId = Auth::user()->company->id ?? '';
                    $query->where('company_id', $companyId);
                }
                if ($companyId && Auth::user()->hasPermission('job_manage_others')) {
                    $query->where('company_id', $companyId);
                }
                if ($jobId) {
                    $query->where("id", $jobId);
                }
            });

        if ($candidateId && Auth::user()->hasPermission('job_manage_others')) {
            $jobs->where('candidate_id', $candidateId);
        }
        return $jobs->orderBy('id', 'desc')->get();
    }

    /**
     * @return array
     * @var Job $job
     */
    public function map($job): array
    {
        return [
            ltrim($job->title, "=-"),
            ltrim($job->content, "=-"),
            ltrim($job->category->name, "=-"),
            ltrim($job->hours, "=-"),
            ltrim($job->salary_type, "=-"),
            ltrim($job->salary_min, "=-"),
            ltrim($job->salary_min, "=-"),
            ltrim(display_date($job->created_date), "=-"),
            ltrim($job->status, "=-"),
            ltrim($job->candidates->map(function ($candidates) {
                return $candidates->candidateInfo->user->name;
            }), "=-")

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
            'Candidates'
        ];
    }
}
