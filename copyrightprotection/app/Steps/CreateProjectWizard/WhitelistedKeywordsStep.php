<?php

namespace App\Steps\CreateProjectWizard;

use Illuminate\Validation\Rule;
use Vildanbina\LivewireWizard\Components\Step;

class WhitelistedKeywordsStep extends Step
{
    public array $inputs= [];
    public int $i = 1;

    public function addKeywordsInput($i): void
    {
        $i = $i + 1;
        $this->i = $i;
        array_push($this->inputs ,$i);
    }

    public function remove($i): void
    {
        unset($this->inputs[$i]);
    }

    // Step view located at resources/views/steps/general.blade.php
    protected string $view = 'user.create-newproject-wizard.whitelisted-keywords-step';

    /*
     * Initialize step fields
     */
    public function mount(): void
    {
        $this->mergeState([
            'name' => $this->model->name,
        ]);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view($this->view, [
            'i'=>$this->i,
            'inputs' => $this->inputs
        ]);
    }

    /*
    * Step icon
    */
    public function icon(): string
    {
        return 'far fa-address-card';
    }

    /*
     * Step Validation
     */
    public function validate(): array
    {
        return [
//            [
//                'state.name' => ['required', Rule::unique('users', 'name')->ignoreModel($this->model)],
//                'state.email' => ['required', Rule::unique('users', 'email')->ignoreModel($this->model)],
//            ],
//            [],
//            [
//                'state.name' => __('Name'),
//                'state.email' => __('Email'),
//            ],
        ];
    }

    /*
     * Step Title
     */
    public function title(): string
    {
        return __('Whitelisted-keywords');
    }
}
