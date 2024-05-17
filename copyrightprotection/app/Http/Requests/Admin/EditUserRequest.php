<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'role' => 'required',
            'phone' => ['required', 'regex:/^[0-9]{5,12}$/', Rule::unique('users')->ignore($this->user)],
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => ['required', 'max:255', Rule::unique('users')->ignore($this->user)],
            'password' => 'nullable|min:8|max:255|confirmed',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
        ];
    }
}
