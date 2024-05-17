<?php

namespace App\Http\Requests;

use App\Models\LeadProductConfiguration;
use Illuminate\Foundation\Http\FormRequest;

class WizardLeadPalletConfigurationRequest extends FormRequest
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
            'system_pallet_height' => 'required|numeric|min:' . LeadProductConfiguration::SYSTEM_PALLET_MIN_HIGHT . '|max:' . LeadProductConfiguration::SYSTEM_PALLET_MAX_HIGHT,
        ];
    }
}
