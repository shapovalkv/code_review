<?php

namespace Modules\User\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Modules\User\Enums\UserStatusEnum;
use Modules\User\Models\Plan;
use Modules\User\Models\Role;
use Modules\User\Services\PlanReportService;

class GetPlanLogRequest extends FormRequest
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
            'from'         => 'nullable|date',
            'to'           => 'nullable|date',
            'plan_ids'     => 'nullable|array',
            'plan_ids.*'   => 'numeric',
            'status_ids'   => 'nullable|array',
            'status_ids.*' => 'numeric',
            'create_user'  => 'nullable|numeric',
        ];
    }

    public function getFrom(): ?Carbon
    {
        return !empty($this->input('from')) ? Carbon::parse($this->input('from'))->startOfDay() : null;
    }

    public function getTo(): ?Carbon
    {
        return !empty($this->input('to')) ? Carbon::parse($this->input('to'))->endOfDay() : null;
    }

    public function getStatusIds(): array
    {
        return array_map(static function ($id) {
            return (int)$id;
        }, $this->input('status_ids', []));
    }

    public function getPlanIds(): array
    {
        return array_map(static function ($roleId) {
            return (int)$roleId;
        }, $this->input('plan_ids', []));
    }
}
