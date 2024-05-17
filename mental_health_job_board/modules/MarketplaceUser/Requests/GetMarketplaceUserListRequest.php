<?php

namespace Modules\MarketplaceUser\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetMarketplaceUserListRequest extends FormRequest
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
            'orderBy'        => 'string|in:users.name,users.email,users.phone,bc_marketplace_users.created_at,users.status',
        ];
    }
}
