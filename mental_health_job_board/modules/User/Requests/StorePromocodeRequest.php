<?php

namespace Modules\User\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\User\Models\Plan;

class StorePromocodeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'title'           => 'required|string|max:255',
            'code'            => [
                'required',
                'string',
                'max:255',
                Rule::unique('promocodes', 'id')->ignore($this->input('id'))
            ],
            'plan_id'         => 'nullable|numeric|exists:' . Plan::class . ',id',
            'value'           => 'required|numeric',
            'is_percent'      => 'nullable|boolean',
            'is_annual'       => 'nullable|boolean',
            'expiration_date' => 'nullable|date',
            'plan_ids'        => 'array',
            'plan_ids.*'      => 'nullable|numeric|exists:' . Plan::class . ',id',
        ];
    }

    public function getPlanIds(): array
    {
        return array_filter((array)$this->input('plan_ids'));
    }

}
