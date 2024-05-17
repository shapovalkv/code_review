<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\WizardLeadCellConfigurationRequest;
use App\Http\Requests\WizardLeadPalletConfigurationRequest;
use App\Http\Requests\WizardLeadProductConfigurationRequest;
use App\Http\Requests\WizardLeadRequest;
use App\Models\ProductType;
use App\Services\LeadWizardService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Livewire\Attributes\Session;


class LeadWizardController extends Controller
{

    public function index(): View
    {
        return view('wizzard');
    }
}
