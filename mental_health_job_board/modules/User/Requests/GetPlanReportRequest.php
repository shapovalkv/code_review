<?php

namespace Modules\User\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Modules\User\Enums\UserStatusEnum;
use Modules\User\Models\Plan;
use Modules\User\Models\Role;
use Modules\User\Services\PlanReportService;

class GetPlanReportRequest extends FormRequest
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
            'separate'   => [
                'nullable',
                'string',
                Rule::in([
                    PlanReportService::SEPARATE_YEAR,
                    PlanReportService::SEPARATE_MONTH,
                    PlanReportService::SEPARATE_WEEK
                ])
            ],
            'from'       => 'nullable|date',
            'to'         => 'nullable|date',
            'role_ids'   => 'nullable|array',
            'role_ids.*' => 'numeric|exists:' . \Modules\User\Models\Role::class . ',id',
            'role_types'   => 'nullable|array',
            'role_types.*'   => [
                'string',
                Rule::in([
                    Plan::TYPE_RECURRING,
                    Plan::TYPE_ONE_TIME,
                    Plan::TYPE_FREE,
                ])
            ],
        ];
    }

    public function getFrom(): ?Carbon
    {
        return !empty($this->input('from')) ? Carbon::parse($this->input('from')) : null;
    }

    public function getTo(): ?Carbon
    {
        return !empty($this->input('to')) ? Carbon::parse($this->input('to')) : null;
    }

    public function getStatus(): ?string
    {
        return !empty($this->input('status')) ? $this->input('status') : null;
    }

    public function getPlanIds(): array
    {
        return array_map(static function ($roleId) {
            return (int)$roleId;
        }, $this->input('plan_ids', []));
    }

    public function getPlanTypes(): array
    {
        return (array)$this->input('plan_types', []);
    }
}
