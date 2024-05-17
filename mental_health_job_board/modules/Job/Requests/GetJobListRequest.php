<?php

namespace Modules\Job\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetJobListRequest extends FormRequest
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
            'orderDirection' => 'string|in:desc,asc',
            'orderBy'        => 'string|in:title,bc_job_positions.name,bc_locations.name,bc_job_categories.name,bc_companies.name,status,created_at',
        ];
    }
}
