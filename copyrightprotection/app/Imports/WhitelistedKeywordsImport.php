<?php

namespace App\Imports;

use App\Models\WhitelistedKeyword;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;

class WhitelistedKeywordsImport implements ToModel
{
    use Importable;

    private $user_project_id;

    public function  __construct($user_project_id)
    {
        $this->user_project_id = $user_project_id;
    }

    public function model(array $row): WhitelistedKeyword
    {
        return new WhitelistedKeyword([
            'user_project_id' =>  $this->user_project_id,
            'content' => $row[0]
        ]);
    }
}
