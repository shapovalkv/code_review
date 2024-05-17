<?php

namespace App\Steps\CreateProjectWizard;

use App\Models\Plan;
use Illuminate\Validation\Rule;
use Vildanbina\LivewireWizard\Components\Step;

class BillingStep extends Step
{
    // Step view located at resources/views/steps/general.blade.php
    protected string $view = 'user.create-newproject-wizard.billing-step';

    public $OrderPlans = [];
    /*
     * Initialize step fields
     */
    public function mount()
    {
        $this->OrderPlans = Plan::all();
        $this->mergeState([
            'name' => $this->model->name,
        ]);
    }

    /*
    * Step icon
    */
    public function icon(): string
    {
        return 'fas fa-dollar-sign';
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
        return __('Billing');
    }
}
