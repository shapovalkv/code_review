<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\CheckProjectReportFile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectReportImportRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'report_date' => 'required',
            'project_report' => ['required', 'mimes:xls,xlsx', new  CheckProjectReportFile()]
        ];
    }
}
