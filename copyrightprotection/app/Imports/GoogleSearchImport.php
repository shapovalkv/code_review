<?php

namespace App\Imports;

use App\Models\ProjectReport;
use App\Models\ReportContent;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;

class GoogleSearchImport implements ToModel
{
    use Importable;

    private int $report_id;

    public function __construct($report_id)
    {
        $this->report_id = $report_id;
    }

    public function model(array $row): ?ReportContent
    {
        $content = trim(current($row));

        if (empty($content)) {
            return null;
        }

        return new ReportContent([
            'report_id' => $this->report_id,
            'content' => $content,
            'type' => ProjectReport::GOOGLE_SEARCH_TYPE,
        ]);
    }
}
