<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WizardLeadCellConfigurationRequest extends FormRequest
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
            'product_infeed_id' => 'required',
            'left_pallet_position_id' => 'required',
            'right_pallet_position_id' => 'required',
        ];
    }
}
