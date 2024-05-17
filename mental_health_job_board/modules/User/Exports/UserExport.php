<?php
namespace Modules\User\Exports;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\User;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Modules\Job\Models\Job;

class UserExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
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

    /** @param User $row */
    public function map($row): array
    {
        return [
            ltrim($row->name,"=-"),
            ltrim($row->first_name,"=-"),
            ltrim($row->last_name,"=-"),
            ltrim($row->email,"=-"),
            ltrim($row->phone,"=-"),
            ltrim($row->address,"=-"),
            ltrim($row->address2,"=-"),
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
            __('Display Name'),
            __('First name'),
            __('Last name'),
            __('Email'),
            __('Phone'),
            __('Address'),
            __('Address 2'),
            __('City'),
            __('State'),
            __('Country'),
            __('Zip Code'),
            __('Status'),
        ];
    }
}
