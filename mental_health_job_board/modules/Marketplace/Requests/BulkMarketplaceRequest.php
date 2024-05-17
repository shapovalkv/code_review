<?php

namespace Modules\Marketplace\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BulkMarketplaceRequest extends FormRequest
{
    public const DELETE = 'delete';
    public const DRAFT = 'draft';
    public const PUBLISH = 'publish';
    public const UPDATE = 'update';

    public const APPROVED = 'approved';

    public const ACTIONS = [self::PUBLISH, self::DRAFT, self::DELETE, self::UPDATE];

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
            'ids' => 'required',
            'ids.*' => 'required|int',
            'action' => Rule::in($this::ACTIONS),
        ];
    }
}
