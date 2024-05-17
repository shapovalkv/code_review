<?php

namespace App\Http\Controllers;

use App\Http\Requests\WhitelistedAccountsRequest;
use App\Http\Requests\WhitelistedCreateAccountsRequest;
use App\Http\Requests\WhitelistedImportAccountsRequest;
use App\Http\Resources\WhitelistedAccountsResource;
use App\Imports\WhitelistedAccountsImport;
use App\Models\WhitelistedAccount;
use App\Services\WhitelistedAccountsService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class WhitelistedAccountsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        return view('user.whitelisted-accounts', [
            'whitelistedAccounts' => WhitelistedAccountsResource::collection($request->user()->getSelectedProject(Auth::user()->selected_project_id)->whitelistedAccounts()->paginate(10, ['*'], 'whitelistedAccounts'))
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(WhitelistedCreateAccountsRequest $request, WhitelistedAccountsService $whitelistedAccountsService): RedirectResponse
    {
        if ($whitelistedAccountsService->create($request->validated(), Auth::user()->selected_project_id)){
            return redirect(route('user.accounts'))->with('success', __('messages.whitelisted_accounts_created'));
        } return redirect(route('user.accounts'))->with('error', __('messages.whitelisted_accounts_created_fail'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function import(WhitelistedImportAccountsRequest $request, WhitelistedAccountsService $whitelistedAccountsService): RedirectResponse
    {
        if ($whitelistedAccountsService->import($request->file('import_accounts'), Auth::user()->selected_project_id)){
            return redirect()->back()->with('success', __('messages.whitelisted_accounts_imported'));
        }return redirect()->back()->with('error', __('messages.whitelisted_accounts_imported_fail'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WhitelistedAccount $whitelistedAccount, WhitelistedAccountsService $whitelistedAccountsService): RedirectResponse
    {
        $action = $whitelistedAccountsService->delete($whitelistedAccount);
        if ($action){
            return redirect()->back()->with('success', __('messages.whitelisted_accounts_deleted'));
        } else {
            return redirect()->back()->with('error', __('messages.whitelisted_accounts_deleted_fail'));
        }
    }
}
