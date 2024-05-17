<?php

namespace App\Exports;

use App\Models\WhitelistedKeyword;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;

class WhitelistedKeywordExport implements FromCollection
{
    protected int $userProjectId;

    public function __construct($userProjectId)
    {
        $this->userProjectId = $userProjectId;
    }

    public function collection()
    {
        return WhitelistedKeyword::query()->select('content')->where('user_project_id', $this->userProjectId)->get();
    }
}
