<?php

namespace Modules\MarketplaceUser\Exports;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Modules\MarketplaceUser\Models\MarketplaceUser;

class MarketplaceUserExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
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

    /** @param MarketplaceUser $row */
    public function map($row): array
    {
        return [
            ltrim($row->user->first_name,"=-"),
            ltrim($row->user->last_name,"=-"),
            ltrim($row->user->email,"=-"),
            ltrim($row->user->phone,"=-"),
            ltrim($row->user->status,"=-"),
        ];
    }

    public function headings(): array
    {
        return [
            __('First name'),
            __('Last name'),
            __('Email'),
            __('Phone'),
            __('Status'),
        ];
    }
}
