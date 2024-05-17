<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ProjectReportImport implements WithMultipleSheets
{
    private int $report_id;

    public function __construct($report_id)
    {
        $this->report_id = $report_id;
    }

    public function sheets():array
    {
        return [
          'Google Search' => new GoogleSearchImport($this->report_id),
          'Google Images' => new GoogleImagesImport($this->report_id),
          'Social Media' => new SocialMediaImport($this->report_id),
          'At Source' => new AtSourceImport($this->report_id),
        ];
    }
}
