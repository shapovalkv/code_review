<?php

namespace Modules\Job\Exports;

use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Modules\Job\Models\JobCandidate;
use Modules\User\Models\Subscriber;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ApplicantsExport implements FromCollection, WithHeadings, WithMapping
{
    use Exportable;

    public function collection()
    {
        $candidateId = request()->query('candidate_id');
        $rows = JobCandidate::with(['jobInfo', 'candidateInfo', 'cvInfo', 'company', 'company.getAuthor'])
            ->whereHas('jobInfo', function ($query) {
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
            $rows->where('candidate_id', $candidateId);
        }
        $rows = $rows->orderBy('id', 'desc')->get();
        return $rows;
    }

    /**
     * @return array
     * @var Subscriber $jobCandidate
     */
    public function map($jobCandidate): array
    {
        return [
            ltrim($jobCandidate->candidateInfo->getAuthor->getDisplayName() ?? '', "=-"),
            ltrim($jobCandidate->jobInfo->title, "=-"),
            ltrim($jobCandidate->message, "=-"),
            ltrim(display_date($jobCandidate->created_date), "=-"),
            ltrim($jobCandidate->status, "=-")
        ];
    }

    public function headings(): array
    {
        return [
            'Candidate',
            'Job Title',
            'Message',
            'Date Applied',
            'Status'
        ];
    }
}
