<?php

namespace Modules\User\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetUserListRequest extends FormRequest
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
            'orderDirection' => 'string|in:desc,asc',
            'orderBy'        => 'string|in:name,email,phone,created_at,role',
        ];
    }
}
