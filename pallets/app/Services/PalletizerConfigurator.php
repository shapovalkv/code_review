<?php

namespace App\Services;

use App\Helper;
use App\Models\Basic;
use App\Models\Bom;
use App\Models\Lead;
use App\Models\LeadProductConfiguration;
use App\Models\PalletizerModule;
use App\Models\ProductType;
use App\Models\RobotDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Livewire\Livewire;

class PalletizerConfigurator extends Basic
{
    const FORGE_OS = 3400;
    const READY_PENDANT = 6750 - self::FORGE_OS;
    const READY_PALLETS_APP = 6000;
    const FORGE_OS_ANNUAL_AGREEMENT = 3600;
    const MODULE_ASSEMBLY = 25000;
    const FINAL_CONFIGURATION_ASSEMBLY = 5000;
    const INSTALLATION_LABOR = 10000;
    const ELECTRICAL_AND_HVAC_DROPS = 5000;
    public $cellConfigurationService;

    public function __construct()
    {
        $this->cellConfigurationService = new CellConfigurationService();
    }

    public function getTotalAmount($configuration)
    {
        return
            ## INFEEDS BOM +++
            intval($this->cellConfigurationService->replacmentInfeedModel($configuration->infeedPosition->id, $configuration->leftPosition->id, $configuration->rightPosition->id)?->module?->cost ?? $configuration->infeedPosition->cost)
//            boomItems->sum(function ($item) {
//                return $item->price_each * $item->qty;
//            })
            ## leftPositions BOM ??????
            +  intval($this->cellConfigurationService->replacmentLeftModel($configuration->leftPosition->id, $configuration->infeedPosition->id)?->module?->cost ?? $configuration->leftPosition->cost)
//                ->boomItems->sum(function ($item) {
//                return $item->price_each * $item->qty;
//            })
            ## rightPosition BOM ++++
            + intval($this->cellConfigurationService->replacmentRightModel($configuration->rightPosition->id, $configuration->infeedPosition->id)?->module?->cost ?? $configuration->rightPosition->cost)
//                ->boomItems->sum(function ($item) {
//                return $item->price_each * $item->qty;
//            })
            ## robot BOM ++++
            + intval($configuration->robot->cost)
//                ->boomItems->sum(function ($item) {
//                return $item->price_each * $item->qty;
//            })
            ## tool/Gripper/EAOTs BOM +++++
            +  intval($configuration->tool->cost)
//                ->boomItems->sum(function ($item) {
//                return $item->price_each * $item->qty;
//            })
            ## Palletizer Cores BOM ????
            + intval(PalletizerModule::find(1)->cost)
//            + Bom::query()->where('palletizer_module_id', '=', 0)->get()->sum(function ($item) {
//                return $item->price_each * $item->qty;
//            })
            + self::FORGE_OS
            + self::READY_PENDANT
            + self::READY_PALLETS_APP
            + self::FORGE_OS_ANNUAL_AGREEMENT
            + self::MODULE_ASSEMBLY
            + self::FINAL_CONFIGURATION_ASSEMBLY
            + self::INSTALLATION_LABOR
            + self::ELECTRICAL_AND_HVAC_DROPS
            ;
    }
}
