<?php

namespace App\Services;

use App\Helper;
use App\Models\Basic;
use App\Models\Bom;
use App\Models\CellExtendsChange;
use App\Models\Lead;
use App\Models\LeadProductConfiguration;
use App\Models\PalletizerModule;
use App\Models\ProductType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Livewire\Livewire;

class CellConfigurationService extends Basic
{
    public static function getInfeedsModules(Lead $lead)
    {
       return self::universalSearch($lead, 'infeed_id');
    }

    public static function getLeftSidesModules(Lead $lead, $infeed_id)
    {
        return self::universalSearch($lead, 'left_side_id', $infeed_id);
    }

    public static function getRightSidesModules(Lead $lead, $infeed_id, $left_side_id)
    {
        return self::universalSearch($lead, 'right_side_id', $infeed_id, $left_side_id);
    }

    public static function universalSearch(Lead $lead, $searchString, $infeed_id = null, $left_side_id = null)
    {
        $modulesIds = PalletizerModule::query()
            ->without('cadModel')
            ->join('cell_gripper_requirements', 'palletizer_modules.id', '=', 'cell_gripper_requirements.gripper_id')
            ->where('cell_gripper_requirements.gripper_id', '=', $lead->lead_product_configuration()->whereIn('status', [LeadProductConfiguration::STATUS_DRAFT, LeadProductConfiguration::STATUS_OVERVIEW])->latest()->first()->tool->id)
            ->where(function ($query) use ($infeed_id){
                if (!empty($infeed_id)){
                    $query->where('cell_gripper_requirements.infeed_id', $infeed_id);
                }
            })
            ->where(function ($query) use ($left_side_id){
                if (!empty($left_side_id)){
                    $query->where('cell_gripper_requirements.left_side_id', $left_side_id);
                }
            })
            ->select('cell_gripper_requirements.'.$searchString)
            ->groupBy('cell_gripper_requirements.'.$searchString)
            ->get();

       return PalletizerModule::whereIn('id', $modulesIds)->get();
    }

    public function extendedConfigurationReplacement($configuration)
    {
        $configuration->replaced_product_infeed_id = intval($this->replacmentInfeedModel($configuration->infeedPosition->id, $configuration->leftPosition->id, $configuration->rightPosition->id)?->cell_editable_to_module_id);
        $configuration->replaced_left_pallet_position_id = intval($this->replacmentLeftModel($configuration->leftPosition->id, $configuration->infeedPosition->id)?->cell_editable_to_module_id);
        $configuration->replaced_right_pallet_position_id = intval($this->replacmentRightModel($configuration->rightPosition->id, $configuration->infeedPosition->id)?->cell_editable_to_module_id);

        $configuration->save();

        return $configuration;
    }

    public function replacmentInfeedModel($productInfeedId, $leftPalletPositionId, $rightPalletPositionId)
    {
        if (!empty($replacmentInfeedModel = CellExtendsChange::query()
            ->where('cell_editable_field', '=', 'product_infeed_id')
            ->where('infeed_id', $productInfeedId)
            ->where('cell_editable_from_module_id', $productInfeedId)
            ->where(function ($query) use ($rightPalletPositionId, $leftPalletPositionId) {
                $query->where('cell_substitute_module_id', $leftPalletPositionId)->orWhere('cell_substitute_module_id', $rightPalletPositionId);
            })
            ->first())) {
            return $replacmentInfeedModel;
        }
        return null;
    }

    public function replacmentLeftModel($leftPalletPositionId, $productInfeedId)
    {
        if (!empty($replacmentLeftModel = CellExtendsChange::query()
            ->where('cell_substitute_field', '=', 'left_pallet_position_id')
            ->where('cell_substitute_module_id', $leftPalletPositionId)
            ->where('cell_editable_from_module_id', $leftPalletPositionId)
            ->where('cell_editable_field', '=', 'left_pallet_position_id')
            ->where('infeed_id', $productInfeedId)
            ->first())) {
            return $replacmentLeftModel;
        }
        return null;
    }

    public function replacmentRightModel($rightPalletPositionId, $productInfeedId)
    {
        if (!empty($replacmentRightModel = CellExtendsChange::query()
            ->where('cell_substitute_field', '=', 'right_pallet_position_id')
            ->where('cell_substitute_module_id', $rightPalletPositionId)
            ->where('cell_editable_from_module_id', $rightPalletPositionId)
            ->where('cell_editable_field', '=', 'right_pallet_position_id')
            ->where('infeed_id', $productInfeedId)
            ->first())) {
            return $replacmentRightModel;
        }
        return null;
    }
}
