<?php

namespace Modules\User\Exports;

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
use Modules\User\Services\PlanReportService;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PlanReportExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, ShouldAutoSize
{
    use Exportable;

    private ?string $separate;
    private array $planTypes;
    private array $planIds;
    private ?Carbon $from;
    private ?Carbon $to;

    public function __construct(array $planTypes, array $planIds, ?string $separate = null, ?Carbon $from = null, ?Carbon $to = null)
    {
        $this->separate = $separate;
        $this->planTypes = $planTypes;
        $this->planIds = $planIds;
        $this->from = $from;
        $this->to = $to;
    }

    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'H' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'G' => NumberFormat::FORMAT_NUMBER,
        ];
    }

    public function collection(): Collection
    {
        return app()
            ->make(PlanReportService::class)
            ->getGroupedReportBuilder($this->planTypes, $this->planIds, $this->separate, $this->from, $this->to)
            ->get();
    }

    public function map($row): array
    {
        /** @var OrderItem $item */
        if (request()->query('separate') === PlanReportService::SEPARATE_WEEK) {

            $parse = explode('-', $item->period);
            $date = Carbon::now();
            $date->setISODate($parse[0], $parse[1]);

            $item->period = $date->startOfWeek()->format('m/d/Y') . '-' . $date->endOfWeek()->format('m/d/Y');
        }
        return [
            ltrim($row->period, "=-"),
            ltrim($row->plan->id, "=-"),
            ltrim($row->plan->title, "=-"),
            ltrim($row->plan->role->name, "=-"),
            ltrim($row->plan->plan_type, "=-"),
            ltrim($row->plan->price, "=-"),
            ltrim($row->count, "=-"),
            ltrim($row->total, "=-"),
            ltrim($row->plan->status, "=-"),
        ];
    }

    public function headings(): array
    {
        return [
            __($this->separate ?? 'Year'),
            __('Plan ID'),
            __('Plan Name'),
            __('Plan Role'),
            __('Plan Type'),
            __('Plan Price'),
            __('Payments'),
            __('Total price'),
            __('Status'),
        ];
    }


}
