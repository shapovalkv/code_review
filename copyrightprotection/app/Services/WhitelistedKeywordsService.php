<?php

namespace App\Services;

use App\Exports\WhitelistedKeywordExport;
use App\Http\Requests\WhitelistedCreateAccountsRequest;
use App\Http\Requests\WhitelistedCreateKeywordsRequest;
use App\Http\Requests\WhitelistedImportKeywordsRequest;
use App\Imports\WhitelistedAccountsImport;
use App\Imports\WhitelistedKeywordsImport;
use App\Models\UserProject;
use App\Models\WhitelistedAccount;
use App\Models\WhitelistedKeyword;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use JetBrains\PhpStorm\NoReturn;
use Maatwebsite\Excel\Facades\Excel;

class WhitelistedKeywordsService
{
    public function create(WhitelistedCreateKeywordsRequest $request)
    {
        $data = $request->validated();
        $row = new WhitelistedKeyword($data);
        $row->user_project_id = Auth::user()->selected_project_id;
        return $row->save();
    }

    public function import(WhitelistedImportKeywordsRequest $request)
    {
        $file = $request->file('import_keywords');

        $import = new WhitelistedKeywordsImport(Auth::user()->selected_project_id);

        return Excel::import($import, $file);
    }

    public function delete(WhitelistedKeyword $whitelistedKeyword): bool
    {
       return $whitelistedKeyword->delete();
    }

    public function export(UserProject $project)
    {
        $date = Carbon::now()->format('Ymd_His');
        return Excel::download(new WhitelistedKeywordExport($project->id), 'keywords_'.$project->name.'_'.$date.'.xlsx');
    }
}
