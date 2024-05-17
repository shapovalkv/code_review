<?php

namespace Modules\Job\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Job\Models\Job;

class UpdateApplicantsRequest extends FormRequest
{

    public const HIRED = 'hired';
    public const INTERVIEW = 'interview';
    public const INTERESTED = 'interested';
    public const PHONE_INTERVIEW = 'phone_interview';
    public const CONTRACT_OFFERED = 'contract_offered';
    public const APPROVED = 'approved';
    public const DELETE = 'delete';
    public const REJECTED = 'rejected';
    public const PENDING = 'pending';

    public const APPLICANT_STATUSES = [self::HIRED, self::INTERVIEW, self::INTERESTED, self::PHONE_INTERVIEW, self::CONTRACT_OFFERED, self::APPROVED, self::DELETE, self::REJECTED, self::PENDING];


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
            'status' => Rule::in($this::APPLICANT_STATUSES),
        ];
    }
}
