<?php

namespace Modules\Job\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BulkJobRequest extends FormRequest
{
    public const DELETE = 'delete';
    public const DRAFT = 'draft';
    public const PUBLISH = 'publish';
    public const UPDATE = 'update';

    public const JOB = 'job';
    public const APPLICANTS = 'applicants';

    public const HIRED = 'hired';
    public const INTERVIEW = 'interview';
    public const INTERESTED = 'interested';
    public const PHONE_INTERVIEW = 'phone_interview';
    public const CONTRACT_OFFERED = 'contract_offered';
    public const APPROVED = 'approved';
    public const REJECTED = 'rejected';

    public const ACTIONS = [self::PUBLISH, self::DRAFT, self::DELETE, self::UPDATE];
    public const MODELS = [self::JOB, self::APPLICANTS];
    public const APPLICANT_STATUSES = [self::HIRED, self::INTERVIEW, self::INTERESTED, self::PHONE_INTERVIEW, self::CONTRACT_OFFERED, self::APPROVED, self::DELETE, self::REJECTED];

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
            'model' => Rule::in($this::MODELS),
            'applicant_status' => Rule::in($this::APPLICANT_STATUSES)
        ];
    }
}
