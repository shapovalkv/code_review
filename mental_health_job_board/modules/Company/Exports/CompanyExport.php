<?php
namespace Modules\Company\Exports;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Modules\Company\Models\Company;

class CompanyExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
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

    /** @param Company $row */
    public function map($row): array
    {
        return [
            ltrim($row->name,"=-"),
            ltrim($row->email,"=-"),
            ltrim($row->phone,"=-"),
            ltrim($row->author->last_name,"=-"),
            ltrim($row->author->first_name,"=-"),
            ltrim($row->address,"=-"),
            ltrim($row->city,"=-"),
            ltrim($row->state,"=-"),
            ltrim($row->country,"=-"),
            ltrim($row->zip_code,"=-"),
            ltrim($row->status,"=-"),
        ];
    }

    public function headings(): array
    {
        return [
            __('Company Name'),
            __('Email'),
            __('Phone'),
            __('Last name'),
            __('First name'),
            __('Address'),
            __('City'),
            __('State'),
            __('Country'),
            __('Zip Code'),
            __('Status'),
        ];
    }
}
