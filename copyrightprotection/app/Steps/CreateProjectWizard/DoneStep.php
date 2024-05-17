<?php

namespace App\Steps\CreateProjectWizard;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Vildanbina\LivewireWizard\Components\Step;

class DoneStep extends Step
{
    // Step view located at resources/views/steps/general.blade.php
    protected string $view = 'user.create-newproject-wizard.done-step';

    /*
    * Step icon
    */
    public function icon(): string
    {
        return 'fas fa-thumbs-up';
    }

    /*
     * When Wizard Form has submitted
     */
    public function saveDone($state)
    {
        $userProjects = $this->model;

        $userProjects->save();

        return redirect(route('dashboard'))->with('success', 'success');
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
        return __('Done');
    }
}
