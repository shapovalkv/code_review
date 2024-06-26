<?php

namespace Modules\Company\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\User\Models\User;

class StoreSubAccountRequest extends FormRequest
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
            'first_name' => 'required|string',
            'last_name'  => 'required|string',
            'email'      => [
                'required', 'email',
                Rule::unique('users', 'email')->ignore($this->input('id')),
            ],
            'phone'      => 'required',
            'permissions' => 'array',
        ];
    }
}
