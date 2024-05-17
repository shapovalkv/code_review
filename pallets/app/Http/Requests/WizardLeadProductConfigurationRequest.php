<?php

namespace App\Http\Requests;

use App\Models\LeadProductConfiguration;
use Illuminate\Foundation\Http\FormRequest;

class WizardLeadProductConfigurationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_name' => 'required|string|max:255',
            'product_type_id' => 'required|string|max:255',
            'product_length' => 'required|numeric|min:' . LeadProductConfiguration::PRODUCT_MIN_LENGTH . '|max:' . LeadProductConfiguration::PRODUCT_MAX_LENGTH,
            'product_width' => 'required|numeric|min:' . LeadProductConfiguration::PRODUCT_MIN_WIDTH . '|max:' . LeadProductConfiguration::PRODUCT_MAX_WIDTH,
            'product_height' => 'required|numeric|min:' . LeadProductConfiguration::PRODUCT_MIN_HEIGHT . '|max:' . LeadProductConfiguration::PRODUCT_MAX_HEIGHT,
            'product_weight' => 'required|numeric|min:' . LeadProductConfiguration::PRODUCT_MIN_WEIGHT . '|max:' . LeadProductConfiguration::PRODUCT_MAX_WEIGHT,
            'product_infeed_rate' => 'required|numeric|min:' . LeadProductConfiguration::PRODUCT_MIN_INFEED_RATE . '|max:' . LeadProductConfiguration::PRODUCT_MAX_INFEED_RATE,
            'pallet_length' => 'required|numeric|min:' . LeadProductConfiguration::PALLET_MIN_LENGTH . '|max:' . LeadProductConfiguration::PALLET_MAX_LENGTH,
            'pallet_width' => 'required|numeric|min:' . LeadProductConfiguration::PALLET_MIN_WIDTH . '|max:' . LeadProductConfiguration::PALLET_MAX_WIDTH,
            'pallet_height' => 'required|numeric|min:' . LeadProductConfiguration::PALLET_MIN_HEIGHT . '|max:' . LeadProductConfiguration::PALLET_MAX_HEIGHT,
        ];
    }
}
