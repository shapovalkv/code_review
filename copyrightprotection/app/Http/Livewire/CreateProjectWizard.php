<?php

namespace App\Http\Livewire;

use App\Events\NewCopyrightEvent;
use App\Imports\WhitelistedAccountsImport;
use App\Imports\WhitelistedKeywordsImport;
use App\Models\File;
use App\Models\Plan;
use App\Models\UserProject;
use App\Models\WhitelistedAccount;
use App\Models\WhitelistedKeyword;
use App\Rules\CheckWhitelistedAccounts;
use App\Rules\CheckWhitelistedKeywords;
use App\Services\FilesService;
use App\Services\WhitelistedAccountsService;
use App\Services\WhitelistedKeywordsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\Request;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class CreateProjectWizard extends Component
{
    use WithFileUploads;

    const CREATE_PROJECT_STEP = 1;
    const WHITELISTED_ACCOUNT_STEP = 2;
    const WHITELISTED_KEYWORD_STEP = 3;
    const LEGAL_DOCUMENT_STEP = 4;
    const SELECT_PLAN_STEP = 5;
    const BILLING_STEP = 6;
    const DONE_STEP = 7;

    public int $currentStep = 1;

    public $component;
    public $userProject;

    public string $name;
    public $whitelistedKeywords;
    public string $whitelistedKeyword;
    public $whitelistedAccounts;
    public string $whitelistedAccount;
    public string $intent;
    public $whitelistedKeywordsFile;
    public $whitelistedAccountsFile;

    public $files = [];
    public $projectFiles;

    protected $rules = [
        'files' => 'required|array',
    ];

    public $plans = [];
    public int $selectedProjectId;
    public ?int $planId = null;
    public $cardNumber;
    public $cardName;
    public $expDate;
    public $cvv;
    public $stripe_token;

    public $paymentMethod;

    public $count = 1;

    protected $listeners = [
        'paymentMethod' => 'paymentMethod',
        'refreshComponent' => '$refresh',
        'upload:finished' => 'successUpload'
    ];

    public function mount()
    {
        $userProject = UserProject::where('user_id', Auth::id())->where('status', 'draft')->first();
        if ($userProject) {
            $this->userProject = $userProject;

            $this->currentStep = $this->userProject->step;
            $this->name = $this->userProject->name;
            $this->planId = $this->userProject->selected_plan_id;
            $this->projectFiles = $this->userProject->legalDocuments ?? collect();
            $this->plans = Plan::get();
            $this->intent = $this->userProject->createSetupIntent()->client_secret ?? '';
        } else {
            $this->userProject = new UserProject();
            $this->plans = Plan::get();
        }

        $this->whitelistedKeywords = WhitelistedKeyword::query()->where('user_project_id', $this->userProject->id)->get();
        $this->whitelistedAccounts = WhitelistedAccount::query()->where('user_project_id', $this->userProject->id)->get();


        return view('livewire.create-project-wizard');
    }

    public function model(): UserProject
    {
        return new UserProject();
    }

    public function createProject(): void
    {
        $this->validate([
            'name' => [
                'required',
                Rule::unique('user_projects', 'name')->where(function ($query) {
                    return $query
                        ->where('user_id', Auth::id())
                        ->whereNotIn('id', [$this->userProject->id]);
                })->ignore($this->userProject->id),
            ]
        ]);

        $this->currentStep = self::WHITELISTED_ACCOUNT_STEP;
        $this->userProject->update(['step' => self::WHITELISTED_ACCOUNT_STEP]);

        $this->userProject = UserProject::firstOrCreate([
            'user_id' => Auth::id(),
            'status' => UserProject::DRAFT
        ], [
            'user_id' => Auth::id(),
            'step' => $this->currentStep,
        ]);

        $this->userProject->name = $this->name;
        $this->userProject->status = UserProject::DRAFT;
        $this->userProject->save();
        $this->mount();
    }

    public function createSingleWhitelistedKeyword(): void
    {
        $this->validate([
            'whitelistedKeyword' => [
                'required',
                Rule::unique('whitelisted_keywords', 'content')->where(function ($query) {
                    return $query->where('user_project_id', $this->userProject->id)
                        ->whereNotIn('id', [$this->userProject->id]);
                }),
            ]
        ]);

        WhitelistedKeyword::create([
            'content' => $this->whitelistedKeyword,
            'user_project_id' => $this->userProject->id
        ]);

        $this->whitelistedKeyword = '';
        $this->mount();
    }

    public function deleteSingleWhitelistedKeywords(WhitelistedKeyword $keyword, WhitelistedKeywordsService $keywordsService)
    {
        $keywordsService->delete($keyword);
        $this->mount();
    }

    public function importWhitelistedKeywords(): void
    {
        $this->validate([
            'whitelistedKeywordsFile' => 'required|mimes:xls,xlsx'
        ]);

        $import = new WhitelistedKeywordsImport($this->userProject->id);
        Excel::import($import, $this->whitelistedKeywordsFile);
        $this->mount();
    }

    public function validateKeywordsStep()
    {
        //Uncomment to add require to create WhitelistedKeywords for this step
//        $this->validate([
//            'whitelistedKeywords' => [new CheckWhitelistedKeywords($this->userProject->id)],
//        ]);

        $this->currentStep = self::WHITELISTED_KEYWORD_STEP;
        $this->userProject->update(['step' => self::WHITELISTED_KEYWORD_STEP]);
    }

    public function createSingleWhitelistedAccount(): void
    {
        $this->validate([
            'whitelistedAccount' => [
                'required',
                Rule::unique('whitelisted_accounts', 'content')->where(function ($query) {
                    return $query->where('user_project_id', $this->userProject->id)
                        ->whereNotIn('id', [$this->userProject->id]);
                }),
            ]
        ]);

        WhitelistedAccount::create([
            'content' => $this->whitelistedAccount,
            'user_project_id' => $this->userProject->id
        ]);

        $this->whitelistedAccount = '';
        $this->mount();
    }

    public function deleteSingleWhitelistedAccounts(WhitelistedAccount $account, WhitelistedAccountsService $accountsService)
    {
        $accountsService->delete($account);
        $this->mount();
    }

    public function validateAccountsStep()
    {
        //Uncomment to add require to create WhitelistedAccounts for this step
//        $this->validate([
//            'whitelistedAccounts' => [new CheckWhitelistedAccounts($this->userProject->id)],
//        ]);

        $this->currentStep = self::LEGAL_DOCUMENT_STEP;
        $this->userProject->update(['step' => self::LEGAL_DOCUMENT_STEP]);
    }

    public function successUpload($name, $tmpPath, FilesService $filesService)
    {
        $files = collect($tmpPath)->map(function ($i) {
            return TemporaryUploadedFile::createFromLivewire($i);
        });

        if ($name === 'files'){
            foreach ($files as $file) {
                $this->projectFiles->push($filesService->store($file, $this->userProject->id, Auth::id()));
            }
        }
    }

    public function deleteFile(File $file, FilesService $filesService)
    {
        $filesService->delete($file);
        $this->projectFiles = $this->userProject->legalDocuments ?? collect();
        $this->emitSelf('refreshComponent');
    }

    public function importWhitelistedAccounts(): void
    {
        $this->validate([
            'whitelistedAccountsFile' => 'required|mimes:xls,xlsx'
        ]);

        $import = new WhitelistedAccountsImport($this->userProject->id);
        Excel::import($import, $this->whitelistedAccountsFile);
        $this->mount();
    }

    public function selectPlan($planId)
    {
        $plan = Plan::findOrFail($planId);
        $this->planId = $plan->id;
        $this->userProject->selected_plan_id = $plan->id;
        $this->userProject->save();
        $this->intent = $this->userProject->createSetupIntent()->client_secret;
        $this->currentStep = self::BILLING_STEP;
        $this->userProject->update(['step' => $this->currentStep]);

    }

    public function paymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }

    public function checkout()
    {
        $plan = Plan::find($this->planId);

        auth()->user()->newProject()->newSubscription($plan->id, $plan->stripe_plan)
            ->create($this->paymentMethod);

        Auth::user()->update(['selected_project_id' => $this->userProject->id]);
        $this->userProject->status = UserProject::ACTIVE;
        $this->userProject->save();

        event(new NewCopyrightEvent($this->userProject));

        $this->currentStep = self::DONE_STEP;
    }

    public function next($step)
    {
        $this->currentStep = $step;
        $this->userProject->update(['step' => $step]);
    }

    public function back($step)
    {
        $this->currentStep = $step;
        $this->userProject->update(['step' => $step]);
    }
}
