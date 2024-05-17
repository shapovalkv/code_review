<?php

namespace Modules\Marketplace\Exports;

use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Modules\Marketplace\Models\Marketplace;
use Modules\Job\Models\Job;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MarketplaceExport implements FromCollection, WithHeadings, WithMapping
{
    use Exportable;

    public function collection()
    {
        $Marketplaces = Marketplace::with(['author', 'company', 'location', 'translations', 'MarketplaceCategory'])
            ->whereHas('company', function ($query) {
                $jobId = request()->query('job_id');
                $companyId = request()->query('company_id');
                if (!Auth::user()->hasPermission('marketplace_manage_others')) {
                    $companyId = Auth::user()->company->id ?? '';
                    $query->where('company_id', $companyId);
                }
                if ($companyId && Auth::user()->hasPermission('marketplace_manage_others')) {
                    $query->where('company_id', $companyId);
                }

                if ($jobId) {
                    $query->where("id", $jobId);
                }
            });

        if (Auth::check()){
            $Marketplaces->where('create_user', Auth::id());
        }

        return $Marketplaces->orderBy('id', 'desc')->get();
    }

    /**
     * @return array
     * @var Job $job
     */
    public function map($Marketplace): array
    {
        return [
            ltrim($Marketplace->title, "=-"),
            ltrim($Marketplace->content, "=-"),
            ltrim($Marketplace->MarketplaceCategory->name, "=-"),
            ltrim(display_date($Marketplace->created_date), "=-"),
            ltrim($Marketplace->status, "=-"),
        ];
    }

    public function headings(): array
    {
        return [
            'Marketplace Title',
            'Marketplace Content',
            'Marketplace Category',
            'Marketplace Created date',
            'Marketplace Status',
        ];
    }
}
