<?php

namespace App\Services;

use App\Helper;
use App\Livewire\Wizard;
use App\Models\Basic;
use App\Models\Lead;
use App\Models\LeadProductConfiguration;
use App\Models\PalletizerModule;
use App\Models\ProductType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Livewire\Livewire;

class LeadWizardService extends Basic
{
    public function createLead($data)
    {
        $lead = parent::save(Lead::class, $data, Helper::get_lead_from_session()->id ?? null);
        Session::put('lead_id', $lead->id);
        return $lead;
    }

    public function updateLead($id, $data)
    {
        return parent::save(array_merge(['id' => $id]), $data);
    }

    public function createOrUpdateLeadProductConfiguration($data, $configurationId = null)
    {
        if (Helper::check_lead_session() === false){
            return redirect(route('wizard'));
        }
        if (empty($configurationId)){
            $data['status'] = 'draft';
        }
        return parent::save(LeadProductConfiguration::class, $data, $configurationId ?? null);
    }

    public function matchGripperSelection($data)
    {
        $product_type = ProductType::find($data['product_type_id']);
        $result = match (true) {
            $product_type->name == LeadProductConfiguration::PRODUCT_BOX && $data['product_weight'] <= 25 && $data['product_infeed_rate'] <= 7.5,
                $product_type->name == LeadProductConfiguration::PRODUCT_BAG && $data['product_weight'] <= 25 && $data['product_infeed_rate'] <= 7.5,
                $product_type->name == LeadProductConfiguration::PRODUCT_PAIL && $data['product_weight'] <= 25 && $data['product_infeed_rate'] <= 7.5 => [
                'toolRequired' => 'vacuum-gripper-single',
                'infeedExclusions' => LeadProductConfiguration::NO_INFЕЕD_EXCLUSIONS
            ],
            $product_type->name == LeadProductConfiguration::PRODUCT_BOX && $data['product_weight'] <= 25 && $data['product_infeed_rate'] >= 7.5,
                $product_type->name == LeadProductConfiguration::PRODUCT_BOX && $data['product_weight'] >= 25 && $data['product_infeed_rate'] <= 7.5,
                $product_type->name == LeadProductConfiguration::PRODUCT_BAG && $data['product_weight'] <= 25 && $data['product_infeed_rate'] >= 7.5,
                $product_type->name == LeadProductConfiguration::PRODUCT_BAG && $data['product_weight'] >= 25 && $data['product_infeed_rate'] <= 7.5,
                $product_type->name == LeadProductConfiguration::PRODUCT_PAIL && $data['product_weight'] <= 25 && $data['product_infeed_rate'] >= 7.5,
                $product_type->name == LeadProductConfiguration::PRODUCT_PAIL && $data['product_weight'] >= 25 && $data['product_infeed_rate'] <= 7.5 => [
                'toolRequired' => 'vacuum-gripper-double',
                'infeedExclusions' => LeadProductConfiguration::NO_INFЕЕD_EXCLUSIONS
            ],
            ($product_type->name == LeadProductConfiguration::PRODUCT_TOTE || $product_type->name == LeadProductConfiguration::PRODUCT_TRAY) && $data['product_weight'] <= 25 && $data['product_infeed_rate'] <= 7.5,
                ($product_type->name == LeadProductConfiguration::PRODUCT_TOTE || $product_type->name == LeadProductConfiguration::PRODUCT_TRAY) && $data['product_weight'] <= 25 && $data['product_infeed_rate'] >= 7.5,
                ($product_type->name == LeadProductConfiguration::PRODUCT_TOTE || $product_type->name == LeadProductConfiguration::PRODUCT_TRAY) && $data['product_weight'] >= 25 && $data['product_infeed_rate'] <= 7.5 => [
                'toolRequired' => 'fork-gripper',
                'infeedExclusions' => LeadProductConfiguration::LEFT_RIGHT_INFЕЕD_NOT_COMPATIBLE
            ],
            default => null
        };


        if ($result) {
            return [
                'toolRequired' => $result['toolRequired'],
                'infeedExclusions' => $result['infeedExclusions']
            ];
        }
    }

    public function wizardDataStepChangeChecker(LeadProductConfiguration $configuration, $newData, $step) : bool
    {
        if ($step === Wizard::PRODUCT_CONFIGURATION_STEP){
            if ($newData['product_type_id'] != $configuration->product_type_id
                || $newData['product_length'] != $configuration->product_length
                || $newData['product_width'] != $configuration->product_width
                || $newData['product_height'] != $configuration->product_height
                || $newData['product_weight'] != $configuration->product_weight
                || $newData['product_infeed_rate'] != $configuration->product_infeed_rate
                || $newData['pallet_length'] != $configuration->pallet_length
                || $newData['pallet_width'] != $configuration->pallet_width
                || $newData['pallet_height'] != $configuration->pallet_height
            ){
                return $configuration->update([
                    'product_infeed_id' => null,
                    'left_pallet_position_id' => null,
                    'right_pallet_position_id' => null,
                    'system_pallet_height' => null,
                    'robot_id' => null,
                ]);
            }
        }
        if ($step === Wizard::CELL_CONFIGURATION_STEP){
            if (!empty($configuration->robot_id) && ($newData['product_infeed_id'] != $configuration->product_infeed_id
                || $newData['left_pallet_position_id'] != $configuration->left_pallet_position_id
                || $newData['right_pallet_position_id'] != $configuration->right_pallet_position_id
            )){
                return $configuration->update([
                    'system_pallet_height' => null,
                    'robot_id' => null,
                ]);
            }
        }
        if ($step === Wizard::PALLET_HEIGHT_CONFIGURATION_STEP){
            if (!empty($configuration->robot_id) && $newData['system_pallet_height'] != $configuration->system_pallet_height){
                return $configuration->update([
                    'robot_id' => null,
                ]);
            }
        }

        return false;
    }
}
