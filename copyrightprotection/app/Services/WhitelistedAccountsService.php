<?php

namespace App\Services;

use App\Exports\WhitelistedAccountExport;
use App\Exports\WhitelistedKeywordExport;
use App\Imports\WhitelistedAccountsImport;
use App\Models\UserProject;
use App\Models\WhitelistedAccount;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use JetBrains\PhpStorm\NoReturn;
use Maatwebsite\Excel\Facades\Excel;

class WhitelistedAccountsService
{
    public function create($validatedData, $projectId): bool
    {
        $row = new WhitelistedAccount($validatedData);
        $row->user_project_id = $projectId;
        return $row->save();
    }

    public function import($file, $projectId): \Maatwebsite\Excel\Excel
    {
        $import = new WhitelistedAccountsImport($projectId);

        return Excel::import($import, $file);
    }

    public function delete(WhitelistedAccount $whitelistedAccounts): bool
    {
        return $whitelistedAccounts->delete();
    }

    public function export(UserProject $project)
    {
        $date = Carbon::now()->format('Ymd_His');
        return Excel::download(new WhitelistedAccountExport($project->id), 'accounts_'.$project->name.'_'.$date.'.xlsx');
    }
}
