<?php

namespace Modules\User\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\User\Models\Support\Ticket;

class UpdateSupportTicketRequest extends FormRequest
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
            'status' => [
                'required',
                'string',
                Rule::in([Ticket::NEW, Ticket::WAITING, Ticket::ANSWERED, Ticket::COMPLETED]),
            ]
        ];
    }
}
