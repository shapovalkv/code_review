<?php
namespace Modules\Candidate\Exports;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Modules\Candidate\Models\Candidate;

class CandidateExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
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

    /** @param Candidate $row */
    public function map($row): array
    {
        return [
            ltrim($row->title,"=-"),
            ltrim($row->user->first_name,"=-"),
            ltrim($row->user->last_name,"=-"),
            ltrim($row->user->email,"=-"),
            ltrim($row->user->phone,"=-"),
            ltrim($row->user->status,"=-"),
            ltrim($row->allow_search,"=-"),
        ];
    }

    public function headings(): array
    {
        return [
            __('Position'),
            __('First name'),
            __('Last name'),
            __('Email'),
            __('Phone'),
            __('Status'),
            __('Allow search'),
        ];
    }
}
