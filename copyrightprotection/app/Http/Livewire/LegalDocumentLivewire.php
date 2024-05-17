<?php

namespace App\Http\Livewire;

use App\Models\File;
use App\Models\Plan;
use App\Models\UserProject;
use App\Services\FilesService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class LegalDocumentLivewire extends Component
{
    use WithFileUploads;

    public $component;
    public $userProject;

    public $files = [];
    public $projectFiles;

    protected $rules = [
        'projectFiles' => 'required|array',
    ];

    protected $listeners = [
        'refreshComponent' => '$refresh'
    ];

    public function mount(UserProject $project)
    {
        $this->userProject = $project;
        $this->projectFiles = $this->userProject->legalDocuments ?? collect();

        return view('livewire.legal-document-livewire');
    }

    public function finishUpload($name, $tmpPath, $isMultiple, FilesService $filesService)
    {
        $this->cleanupOldUploads();

        $files = collect($tmpPath)->map(function ($i) {
            return TemporaryUploadedFile::createFromLivewire($i);
        });

        foreach ($files as $file) {
            $this->projectFiles->push($filesService->store($file, $this->userProject->id, Auth::id()));
        }

        $this->emitSelf('upload:finished', $name, collect($files)->map->getFilename()->toArray());
    }

    public function deleteFile(File $file, FilesService $filesService)
    {
        $filesService->delete($file);
        $this->projectFiles = $this->userProject->legalDocuments ?? collect();
        $this->emitSelf('refreshComponent');
    }

    public function downloadFile(File $file, FilesService $filesService)
    {
        return $filesService->download($file);
    }
}
