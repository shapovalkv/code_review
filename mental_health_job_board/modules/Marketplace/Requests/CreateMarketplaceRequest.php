<?php

namespace Modules\Marketplace\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Marketplace\Models\Marketplace;

class CreateMarketplaceRequest extends FormRequest
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
            'status' => Rule::in(Marketplace::STATUS_PUBLISH, Marketplace::STATUS_DRAFT),
            'title'  => 'required',
            'cat_id' => 'required',
            'slug'   => 'unique:' . Marketplace::class . ',slug',
        ];
    }

    public function messages(): array
    {
        if (is_default_lang()) {
            return [
                'title.required'  => __('Marketplace title is required'),
                'cat_id.required' => __("Category is required"),
            ];
        } else {
            return parent::messages();
        }
    }
}
