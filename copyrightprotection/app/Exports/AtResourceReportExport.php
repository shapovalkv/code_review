<?php

namespace App\Exports;

use App\Models\WhitelistedAccount;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AtResourceReportExport implements FromQuery
{
    use Exportable;
    protected $report;

    public function __construct($report)
    {
        $this->report = $report;
    }

    public function query()
    {
        return $this->report->atSourceReports()->select('content');
    }
}
