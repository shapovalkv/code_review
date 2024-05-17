<?php

namespace Modules\Equipment\Exports;

use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Modules\Equipment\Models\Equipment;
use Modules\Job\Models\Job;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EquipmentExport implements FromCollection, WithHeadings, WithMapping
{
    use Exportable;

    public function collection()
    {
        $equipments = Equipment::with(['author', 'company', 'location', 'translations', 'equipmentCategory'])
            ->whereHas('company', function ($query) {
                $jobId = request()->query('job_id');
                $companyId = request()->query('company_id');
                if (!Auth::user()->hasPermission('equipment_manage_others')) {
                    $companyId = Auth::user()->company->id ?? '';
                    $query->where('company_id', $companyId);
                }
                if ($companyId && Auth::user()->hasPermission('equipment_manage_others')) {
                    $query->where('company_id', $companyId);
                }

                if ($jobId) {
                    $query->where("id", $jobId);
                }
            });

        if (Auth::check()){
            $equipments->where('create_user', Auth::id());
        }

        return $equipments->orderBy('id', 'desc')->get();
    }

    /**
     * @return array
     * @var Job $job
     */
    public function map($equipment): array
    {
        return [
            ltrim($equipment->title, "=-"),
            ltrim($equipment->content, "=-"),
            ltrim($equipment->equipmentCategory->name, "=-"),
            ltrim(display_date($equipment->created_date), "=-"),
            ltrim($equipment->status, "=-"),
        ];
    }

    public function headings(): array
    {
        return [
            'Equipment Title',
            'Equipment Content',
            'Equipment Category',
            'Equipment Created date',
            'Equipment Status',
        ];
    }
}
