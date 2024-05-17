<?php

namespace App\Http\Controllers;

use App\Http\Requests\WhitelistedCreateAccountsRequest;
use App\Http\Requests\WhitelistedCreateKeywordsRequest;
use App\Http\Requests\WhitelistedImportKeywordsRequest;
use App\Http\Resources\WhitelistedKeywordsResource;
use App\Imports\WhitelistedAccountsImport;
use App\Models\WhitelistedKeyword;
use App\Services\WhitelistedKeywordsService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class WhitelistedKeywordsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        return view('user.whitelisted-keywords', [
            'whitelistedKeywords' => WhitelistedKeywordsResource::collection($request->user()->getSelectedProject(Auth::user()->selected_project_id)->whitelistedKeywords()->paginate(10, ['*'], 'whitelistedAccounts'))
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(WhitelistedCreateKeywordsRequest $request, WhitelistedKeywordsService $keywordsService): RedirectResponse
    {
        if ($keywordsService->create($request)){
            return redirect(route('user.keywords'))->with('success', __('messages.whitelisted_keywords_created'));
        } return redirect(route('user.keywords'))->with('error', __('messages.whitelisted_keywords_created_fail'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function import(WhitelistedImportKeywordsRequest $request, WhitelistedKeywordsService $whitelistedKeywordsService): RedirectResponse
    {
        if ($whitelistedKeywordsService->import($request)){
            return redirect()->back()->with('success', __('messages.whitelisted_keywords_imported'));
        }return redirect()->back()->with('error', __('messages.whitelisted_keywords_imported_fail'));
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
    public function destroy(WhitelistedKeyword $whitelistedKeyword, WhitelistedKeywordsService $keywordsService): RedirectResponse
    {
        $action = $keywordsService->delete($whitelistedKeyword);
        if ($action){
            return redirect()->back()->with('success', __('messages.whitelisted_keywords_deleted'));
        } else {
            return redirect()->back()->with('success', __('messages.whitelisted_keywords_deleted_fail'));
        }
    }
}
