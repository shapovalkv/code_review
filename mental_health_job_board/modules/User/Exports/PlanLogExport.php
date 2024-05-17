<?php

namespace Modules\User\Exports;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Maatwebsite\Excel\Concerns\WithMapping;
use Modules\Order\Models\OrderItem;
use Modules\User\Models\UserPlan;
use Modules\User\Services\PlanReportService;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PlanLogExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, ShouldAutoSize
{
    use Exportable;

    private Builder $builder;

    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
    }

    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'D' => NumberFormat::FORMAT_DATE_XLSX22,
            'E' => NumberFormat::FORMAT_DATE_XLSX22,
        ];
    }

    public function collection(): Collection
    {
        return $this->builder->get();
    }

    /**
     * @param UserPlan $row
     * @return array
     */
    public function map($row): array
    {
        return [
            ltrim($row->id, "=-"),
            ltrim($row->plan->title, "=-"),
            ltrim($row->user->name, "=-"),
            ltrim($row->start_date, "=-"),
            ltrim($row->end_date, "=-"),
            ltrim((float)$row->price, "=-"),
            ltrim(match ($row->status) {
                UserPlan::USED => 'Expired',
                UserPlan::CURRENT => 'Active',
                UserPlan::NOT_USED => 'Waiting',
            }, "=-"),
        ];
    }

    public function headings(): array
    {
        return [
            __('ID'),
            __('Plan Name'),
            __('User'),
            __('From'),
            __('To'),
            __('Price'),
            __('Status'),
        ];
    }


}
