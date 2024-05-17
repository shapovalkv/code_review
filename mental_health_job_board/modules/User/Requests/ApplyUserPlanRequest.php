<?php

namespace Modules\User\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\User\Models\Plan;

class ApplyUserPlanRequest extends FormRequest
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
            'plan_id' => 'required|exists:' . Plan::class . ',id'
        ];
    }
}
