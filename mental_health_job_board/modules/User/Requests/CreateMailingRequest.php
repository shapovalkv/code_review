<?php

namespace Modules\User\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Modules\User\Enums\UserStatusEnum;
use Modules\User\Models\Role;

class CreateMailingRequest extends FormRequest
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
            'subject'       => 'required|string|max:255',
            'body'          => 'required|string',
            'role_ids'      => 'required|array',
            'role_ids.*'    => 'required|numeric|exists:' . Role::class . ',id',
            'status'        => ['nullable', 'string', Rule::in([UserStatusEnum::DRAFT, UserStatusEnum::PUBLISH])],
            'register_from' => 'nullable|date',
        ];
    }

    public function getRegisterFrom(): ?Carbon
    {
        return !empty($this->input('register_from')) ? Carbon::parse($this->input('register_from')) : null;
    }

    public function getStatus(): ?string
    {
        return !empty($this->input('status')) ? $this->input('status') : null;
    }

    public function getRoleIds(): array
    {
        return array_map(static function ($roleId) {
            return (int)$roleId;
        }, $this->input('role_ids', []));
    }
}
