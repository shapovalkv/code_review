<?php

namespace Modules\Equipment\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Job\Models\Job;

class UpdateEquipmentRequest extends FormRequest
{
    public const PUBLISH = 'publish';
    public const DRAFT = 'draft';

    public const STATUSES = [self::PUBLISH, self::DRAFT];

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
            'action' => 'required|string',
            'status' => Rule::in($this::STATUSES),
        ];
    }
}
