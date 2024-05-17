<?php

namespace App\Steps\CreateProjectWizard;

use App\Models\UserProject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Vildanbina\LivewireWizard\Components\Step;

class ProjectNameStep extends Step
{
    // Step view located at resources/views/steps/general.blade.php
    protected string $view = 'user.create-newproject-wizard.name-step';

    /*
     * Initialize step fields
     */
    public function mount()
    {
        $this->mergeState([
            'name' => $this->model->name,
        ]);
    }

//    public function render(): \Illuminate\Contracts\View\View
//    {
//        return view($this->view);
//    }


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
                'state.name' => ['required', Rule::unique('user_projects', 'name')->ignoreModel($this->model)],
            ],
            [
                'state.name' => __('Name'),
            ],
        ];
    }

    /*
     * Step Title
     */
    public function title(): string
    {
        return __('Project Name');
    }
}
