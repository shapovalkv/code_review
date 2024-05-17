<?php

namespace App\Imports;

use App\Models\WhitelistedAccount;
use App\Models\WhitelistedKeyword;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;

class WhitelistedAccountsImport implements ToModel
{
    use Importable;

    private $user_project_id;

    public function  __construct($user_project_id)
    {
        $this->user_project_id = $user_project_id;
    }

    public function model(array $row): WhitelistedAccount
    {
        return new WhitelistedAccount([
            'user_project_id' => $this->user_project_id,
            'content' => $row[0]
        ]);
    }
}
