<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilesStoreRequest;
use App\Models\File;
use App\Services\FilesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LegalDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('user.legal-document');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FilesStoreRequest $request, FilesService $filesService): RedirectResponse
    {
        $filesService->store($request->file('legal_documents'), auth()->user()->selectedProject->id, Auth::id());
        return redirect()->back()->with('success', 'messages.legal_document_added');
    }

    /**
     * Download file from storage.
     */
    public function download(File $file, FilesService $filesService)
    {
        return $filesService->download($file);
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
    public function destroy(int $fileId)
    {
        ///
    }
}
