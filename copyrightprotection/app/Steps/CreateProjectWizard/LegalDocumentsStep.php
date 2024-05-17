<?php

namespace App\Steps\CreateProjectWizard;

use Illuminate\Validation\Rule;
use Vildanbina\LivewireWizard\Components\Step;

class LegalDocumentsStep extends Step
{
    // Step view located at resources/views/steps/general.blade.php
    protected string $view = 'user.create-newproject-wizard.legal-documents-step';

    /*
     * Initialize step fields
     */
    public function mount()
    {
        $this->mergeState([
            'name' => $this->model->name,
        ]);
    }

    /*
    * Step icon
    */
    public function icon(): string
    {
        return 'fas fa-user';
    }

    /*
     * Step Validation
     */
    public function validate()
    {
        return [
            [
//                'state.name' => ['required', Rule::unique('users', 'name')->ignoreModel($this->model)],
//                'state.email' => ['required', Rule::unique('users', 'email')->ignoreModel($this->model)],
//            ],
//            [],
//            [
//                'state.name' => __('Name'),
//                'state.email' => __('Email'),
            ],
        ];
    }

    /*
     * Step Title
     */
    public function title(): string
    {
        return __('Legal-documents');
    }
}
