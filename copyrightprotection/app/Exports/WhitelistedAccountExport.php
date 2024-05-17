<?php

namespace App\Exports;

use App\Models\WhitelistedAccount;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class WhitelistedAccountExport implements FromCollection
{
    use Exportable;
    protected int $userProjectId;

    public function __construct($userProjectId)
    {
        $this->userProjectId = $userProjectId;
    }

    public function collection()
    {
        return WhitelistedAccount::query()->select('content')->where('user_project_id', $this->userProjectId)->get();
    }
}
