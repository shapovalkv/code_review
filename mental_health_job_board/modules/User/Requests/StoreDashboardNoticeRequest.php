<?php

namespace Modules\User\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\User\Models\DashboardNotice;

class StoreDashboardNoticeRequest extends FormRequest
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
            'title'          => 'required|string',
            'content'        => 'required|string',
            'status'         => [
                'required',
                'string',
                Rule::in([
                    DashboardNotice::PUBLISH,
                    DashboardNotice::DRAFT
                ])
            ],
            'sort'           => 'numeric',
            'style'          => [
                'required',
                'string',
                Rule::in([
                    DashboardNotice::SUCCESS,
                    DashboardNotice::DANGER,
                    DashboardNotice::WARNING,
                    DashboardNotice::PRIMARY
                ])
            ],
            'filter'         => 'nullable|array',
            'filter.role_id' => 'nullable|numeric|exists:' . \Modules\User\Models\Role::class . ',id'
        ];
    }
}
