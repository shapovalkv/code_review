<?php
namespace Modules\Job\Exports;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Modules\Job\Models\Job;

class JobExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;


    private Builder $builder;

    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
    }

    public function collection(): Collection
    {
        return $this->builder->get();
    }

    /** @param Job $row */
    public function map($row): array
    {
        return [
            ltrim($row->title,"=-"),
            ltrim($row->position->name,"=-"),
            ltrim($row->location->name,"=-"),
            ltrim($row->category->name,"=-"),
            ltrim($row->company->name,"=-"),
            ltrim($row->status,"=-"),
            ltrim($row->updated_at,"=-"),
        ];
    }

    public function headings(): array
    {
        return [
            __('Title'),
            __('Employment Type'),
            __('Location'),
            __('Category'),
            __('Company'),
            __('Status'),
            __('Date'),
        ];
    }
}
