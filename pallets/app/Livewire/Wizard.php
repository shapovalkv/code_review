<?php

namespace App\Livewire;

use App\Events\HubSpotProcessedEvent;
use App\Helper;
use App\Models\CellExtendsChange;
use App\Models\EAOTDetail;
use App\Models\LeadProductConfiguration;
use App\Models\PalletizerModule;
use App\Models\ProductType;
use App\Models\RobotDetail;
use App\Services\CellConfigurationService;
use App\Services\LeadWizardService;
use App\Services\MeasurementSystemConfigService;
use App\Services\PalletizerConfigurator;
use App\Services\RobotConfigurationService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Wizard extends Component
{
    public $currentStep = 1;

    const PRODUCT_CONFIGURATION_STEP = 1;
    const CELL_CONFIGURATION_STEP = 2;
    const PALLET_HEIGHT_CONFIGURATION_STEP = 3;
    const ROBOT_SELECTION_STEP = 4;
    const SYSTEM_SUMMARY_STEP = 5;

    const DEFAULT_CONVEYOR_SLUG = 'center-left-infeed';
    const DEFAULT_LEFT_SIDE_SLUG = 'left-side-floor-pallet-left-infeed-extended';
    const DEFAULT_RIGHT_SIDE_SLUG = 'right-side-floor-pallet-right-infeed-extended';
    const MAIN_CELL_SLUG = 'main-palletizer';

    const NOTIFY_INFEED = 'The Product Infeed Rate cannot be greater than 7.5 when the Product weight is more than 25kg.';
    const NOTIFY_ROBOT = 'No compatible robots are available for the selected configuration. Adjust the following parameters for more options:
    <br><br> - Box Size
    <br> - Weight
    <br> - Pallet Size
    <br> - Stack Height
    ';

    public $measurementSystem = MeasurementSystemConfigService::MEASURE_SYSTEM_METRIC;
    public $measurementSystemConfig = [];
    public $lead;
    public string $productTypeImage = '';
    public string $productTypeWithPalletImage = '';
    public $lead_configuration;
    public $infeedPosition;
    public $leftPosition;
    public $rightPosition;
    public $robotDetail;
    public $product_types = [];
    public $product_infeeds = [];
    public $left_pallet_positions = [];
    public $right_pallet_positions = [];
    public $left_right_disable = 'disabled';
    public $robots = [];
    public $totalAmount = 0;
    public $gripper;
    public $product_type;
    public $robotModel;
    public $defaultModelsPath;
    public $defaultProductTypeImg;
    public $resettingAlertModalAccepted = false;
    public $stepFunctionsMapping = [
        1 => 'createOrUpdateLeadProductConfiguration',
        2 => 'saveCell',
        3 => 'savePalletHeight'
    ];

    ## Customer information step
    #[Validate('required|string|max:255')]
    public string $first_name = '';

    #[Validate('required|string|max:255')]
    public string $last_name = '';

    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required|string|max:20')]
    public string $phone = '';

    #[Validate('required|string|max:255')]
    public string $job_title = '';

    #[Validate('required|string|max:255')]
    public string $company = '';

    #[Validate('required|string|max:255')]
    public string $local_distributor = '';

    ## Product configuration step
    #[Validate('required', message: 'The Product Name is required.')]
    public string $product_name = '';

    #[Validate('required', message: 'The Product Type is required.')]
    public string $product_type_id = '';

    #[Validate]
    public string $product_length = '';

    #[Validate]
    public string $product_width = '';

    #[Validate]
    public string $product_height = '';

    #[Validate]
    public string $product_weight = '';

    #[Validate]
    public float $product_infeed_rate = 1.0;
    public string $product_infeed_rate_notify = '';

    #[Validate]
    public string $pallet_length = '';

    #[Validate]
    public string $pallet_width = '';

    #[Validate]
    public string $pallet_height = '';

    ## Cell configuration step
    public $product_infeed_id = '';
    public $left_pallet_position_id = '';
    public $right_pallet_position_id = '';

    ## Pallet height step
    #[Validate]
    public $system_pallet_height = '';

    ## Robot selector step
    public $robot_id = '';
    public string $robot_notify = '';

    ## Request customization Modal
    #[Validate('required')]
    public string $request_customization = '';
    /**
     * @var int|mixed
     */

    public function render()
    {
        $this->dispatch('wizardUpdated');
        return view('livewire.wizard');
    }

    public function rules()
    {
        return [
            'product_length' => 'required|numeric|min:' . $this->measurementSystemConfig['PRODUCT_MIN_LENGTH']['value'] . '|max:' . $this->measurementSystemConfig['PRODUCT_MAX_LENGTH']['value'],
            'product_width' => 'required|numeric|min:' . $this->measurementSystemConfig['PRODUCT_MIN_WIDTH']['value']. '|max:' . $this->measurementSystemConfig['PRODUCT_MAX_WIDTH']['value'],
            'product_height' => 'required|numeric|min:' . $this->measurementSystemConfig['PRODUCT_MIN_HEIGHT']['value'] . '|max:' . $this->measurementSystemConfig['PRODUCT_MAX_HEIGHT']['value'],
            'product_weight' => 'required|numeric|min:' . $this->measurementSystemConfig['PRODUCT_MIN_WEIGHT']['value'] . '|max:' . $this->measurementSystemConfig['PRODUCT_MAX_WEIGHT']['value'],
            'pallet_length' => 'required|numeric|min:' . $this->measurementSystemConfig['PALLET_MIN_LENGTH']['value'] . '|max:' . $this->measurementSystemConfig['PALLET_MAX_LENGTH']['value'],
            'pallet_width' => 'required|numeric|min:' . $this->measurementSystemConfig['PALLET_MIN_WIDTH']['value'] . '|max:' . $this->measurementSystemConfig['PALLET_MAX_WIDTH']['value'],
            'pallet_height' => 'required|numeric|min:' . $this->measurementSystemConfig['PALLET_MIN_HEIGHT']['value'] . '|max:' . $this->measurementSystemConfig['PALLET_MAX_HEIGHT']['value'],
            'system_pallet_height' => 'required|numeric|min:' . $this->measurementSystemConfig['SYSTEM_PALLET_MIN_HIGHT']['value'] . '|max:' .  $this->measurementSystemConfig['SYSTEM_PALLET_MAX_HIGHT']['value'],
            'product_infeed_rate' => [
                'required',
                'numeric',
                'min:' . $this->measurementSystemConfig['PRODUCT_MIN_INFEED_RATE']['value'],
                function ($attribute, $value, $fail) {
                    if ($this->product_weight > 25 && $value >= 7.5) {
                        $fail('The Product Infeed Rate cannot be greater than 7.5 when the Product weight is more than '.$this->measurementSystemConfig['PRODUCT_REQUIRE_INFEED_RATE_WEIGHT']['value'].' '.$this->measurementSystemConfig['PRODUCT_REQUIRE_INFEED_RATE_WEIGHT']['code'].'.');
                    } elseif ($this->product_weight < $this->measurementSystemConfig['PRODUCT_REQUIRE_INFEED_RATE_WEIGHT']['value'] && $value > $this->measurementSystemConfig['PRODUCT_MAX_INFEED_RATE']['value']) {
                        $fail('The product infeed rate cannot exceed the maximum allowed value.');
                    }
                }
            ],
        ];
    }

    public function messages()
    {
        return [
            'product_length.required' => 'The Product Length is required.',
            'product_length.min' => 'The Product Length must be at least :min.',
            'product_length.max' => 'The Product Length must not be greater than :max.',

            'product_width.required' => 'The Product Width is required.',
            'product_width.min' => 'The Product Width must be at least :min.',
            'product_width.max' => 'The Product Width must not be greater than :max.',

            'product_height.required' => 'The Product Height is required.',
            'product_height.min' => 'The Product Height must be at least :min.',
            'product_height.max' => 'The Product Height must not be greater than :max.',

            'product_weight.required' => 'The Product Weight is required.',
            'product_weight.min' => 'The Product Weight must be at least :min.',
            'product_weight.max' => 'The Product Weight must not be greater than :max.',

            'pallet_length.required' => 'The Pallet Length is required.',
            'pallet_length.min' => 'The Pallet Length must be at least :min.',
            'pallet_length.max' => 'The Pallet Length must not be greater than :max.',

            'pallet_width.required' => 'The Pallet Width is required.',
            'pallet_width.min' => 'The Pallet Width must be at least :min.',
            'pallet_width.max' => 'The Pallet Width must not be greater than :max.',

            'pallet_height.required' => 'The  Pallet Height is required.',
            'pallet_height.min' => 'The  Pallet Height must be at least :min.',
            'pallet_height.max' => 'The  Pallet Height must not be greater than :max.',


            'system_pallet_height.required' => 'The Max Allowable total pallet height is required.',
            'system_pallet_height.min' => 'The Max Allowable total pallet height must be at least :min.',
            'system_pallet_height.max' => 'The Max Allowable total pallet height must not be greater than :max.',
        ];
    }

    public function mount()
    {
        $this->resettingAlertModalAccepted = false;
        $robotConfigurationService = new  RobotConfigurationService();
        $measurementSystemConfigService = new MeasurementSystemConfigService();
        $palletizerConfigurator = new  PalletizerConfigurator();
        $this->product_types = ProductType::all();
        $this->lead = Helper::get_lead_from_session();
        $this->productTypeImage = ProductType::where('slug', '=', 'box')->first()->img_path;
        $this->productTypeWithPalletImage = ProductType::where('slug', '=', 'box')->first()->img_with_pallet_path;
        $this->defaultProductTypeImg = $this->product_types->firstWhere('slug', 'box')->img_path;
        $this->measurementSystem = !empty(Session::get('metricalSystem')) ? Session::get('metricalSystem') : MeasurementSystemConfigService::MEASURE_SYSTEM_METRIC;
        $this->measurementSystemConfig = $measurementSystemConfigService->getConfig($this->measurementSystem);

        if ($this->lead) {
            $this->first_name = $this->lead->first_name ?? '';
            $this->last_name = $this->lead->last_name ?? '';
            $this->email = $this->lead->email ?? '';
            $this->phone = $this->lead->phone ?? '';
            $this->job_title = $this->lead->job_title ?? '';
            $this->company = $this->lead->company ?? '';
            $this->local_distributor = $this->lead->local_distributor ?? '';
            $this->lead_configuration = $this->lead->lead_product_configuration()->whereIn('status', [LeadProductConfiguration::STATUS_DRAFT, LeadProductConfiguration::STATUS_OVERVIEW])->latest()->first();
        }

        if ($this->lead && $this->lead_configuration) {
            $this->product_name = $this->lead_configuration->product_name;
            $this->product_type_id = $this->lead_configuration->product_type_id;
            $this->product_type = $this->product_types->where('id', $this->product_type_id)->first();
            $this->productTypeImage = $this->product_type->img_path;
            $this->productTypeWithPalletImage = $this->product_type->img_with_pallet_path;
            $this->measurementSystemRecalculation();
            $this->gripper = EAOTDetail::with('cadModel')
                ->firstWhere('palletizer_module_id', $this->lead_configuration->gripper_id);
            if ($this->lead_configuration->product_infeed_id &&
                $this->lead_configuration->left_pallet_position_id &&
                $this->lead_configuration->right_pallet_position_id){
                $this->product_infeed_id = $this->lead_configuration->product_infeed_id;
                $this->left_pallet_position_id = $this->lead_configuration->left_pallet_position_id;
                $this->right_pallet_position_id = $this->lead_configuration->right_pallet_position_id;
                $this->infeedPosition = $this->lead_configuration->infeedPosition;
                $this->rightPosition = $this->lead_configuration->rightPosition;
                $this->leftPosition = $this->lead_configuration->leftPosition;
            }

            if ($this->lead_configuration->robot_id) {
                $this->robot_id = $this->lead_configuration->robot->robotDetail->id;
                $this->robotDetail = $this->lead_configuration->robot->robotDetail;
                $this->robotModel = PalletizerModule::with('cadModel')->firstWhere('slug', $this->lead_configuration->robot->slug);
            }

            if ($this->lead_configuration->request_customization) {
                $this->request_customization = $this->lead_configuration->request_customization;
            }

            if ($this->lead_configuration && $this->lead_configuration->total_price){
                $this->totalAmount = $this->lead_configuration->total_price ?? $palletizerConfigurator->getTotalAmount($this->lead_configuration);
            }
        }

        $this->product_infeeds = !empty($this->lead_configuration) ? CellConfigurationService::getInfeedsModules($this->lead) : [];
        if (!empty($this->product_infeed_id)) {
            $this->left_pallet_positions = !empty($this->product_infeed_id) ? CellConfigurationService::getLeftSidesModules($this->lead, $this->product_infeed_id) : [];
            $this->right_pallet_positions = !empty($this->product_infeed_id) ? CellConfigurationService::getRightSidesModules($this->lead, $this->product_infeed_id, $this->left_pallet_position_id) : [];
            $this->left_right_disable = 'enabled';
        } else {
            $this->left_pallet_positions = [];
            $this->right_pallet_positions = [];
        }

        $this->defaultModelsPath = $this->getDefaultModelsPath();

        $this->robots = $this->lead && $this->lead_configuration && $this->lead_configuration->product_infeed_id ? $robotConfigurationService->processingRobotCalculation($this->lead_configuration, $this->lead_configuration->tool->eoatDetail) : new Collection();
        if ($this->robots->isEmpty()){
            $this->robot_notify = self::NOTIFY_ROBOT;
            $this->dispatch('RobotEmpty');
        } else {
            $this->robot_notify = '';
        }

        $this->currentStep = $this->stepCheker();
    }

    public function createLead(LeadWizardService $leadWizardService)
    {
        $validated = Validator::make([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'job_title' => $this->job_title,
            'company' => $this->company,
            'local_distributor' => $this->local_distributor
        ], [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'job_title' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'local_distributor' => 'required|string|max:255'
        ])->validate();

        $this->lead = $leadWizardService->createLead($validated);
        $this->systemSummarySubmit();
    }

    public function createOrUpdateLeadProductConfiguration(LeadWizardService $leadWizardService)
    {
        $measurementSystemConfigService = new  MeasurementSystemConfigService();

        $validated = Validator::make([
            'product_name' => $this->product_name,
            'product_type_id' => $this->product_type_id,
            'product_length' => $this->product_length,
            'product_width' => $this->product_width,
            'product_height' => $this->product_height,
            'product_weight' => $this->product_weight,
            'product_infeed_rate' => $this->product_infeed_rate,
            'pallet_length' => $this->pallet_length,
            'pallet_width' => $this->pallet_width,
            'pallet_height' => $this->pallet_height,
        ], [
            'product_name' => 'required|string|max:255',
            'product_type_id' => 'required',
            'product_length' => 'required|numeric|min:' . $this->measurementSystemConfig['PRODUCT_MIN_LENGTH']['value'] . '|max:' . $this->measurementSystemConfig['PRODUCT_MAX_LENGTH']['value'],
            'product_width' => 'required|numeric|min:' . $this->measurementSystemConfig['PRODUCT_MIN_WIDTH']['value']. '|max:' . $this->measurementSystemConfig['PRODUCT_MAX_WIDTH']['value'],
            'product_height' => 'required|numeric|min:' . $this->measurementSystemConfig['PRODUCT_MIN_HEIGHT']['value'] . '|max:' . $this->measurementSystemConfig['PRODUCT_MAX_HEIGHT']['value'],
            'product_weight' => 'required|numeric|min:' . $this->measurementSystemConfig['PRODUCT_MIN_WEIGHT']['value'] . '|max:' . $this->measurementSystemConfig['PRODUCT_MAX_WEIGHT']['value'],
            'product_infeed_rate' => [
                'required',
                'numeric',
                'min:' . $this->measurementSystemConfig['PRODUCT_MIN_INFEED_RATE']['value'],
                function ($attribute, $value, $fail) {
                    if ($this->product_weight > 25 && $value >= 7.5) {
                        $fail('The Product Infeed Rate cannot be greater than 7.5 when the Product weight is more than '.$this->measurementSystemConfig['PRODUCT_REQUIRE_INFEED_RATE_WEIGHT']['value'].' '.$this->measurementSystemConfig['PRODUCT_REQUIRE_INFEED_RATE_WEIGHT']['code'].'.');
                    } elseif ($this->product_weight < $this->measurementSystemConfig['PRODUCT_REQUIRE_INFEED_RATE_WEIGHT']['value'] && $value > $this->measurementSystemConfig['PRODUCT_MAX_INFEED_RATE']['value']) {
                        $fail('The product infeed rate cannot exceed the maximum allowed value.');
                    }
                }
            ],
            'pallet_length' => 'required|numeric|min:' . $this->measurementSystemConfig['PALLET_MIN_LENGTH']['value'] . '|max:' . $this->measurementSystemConfig['PALLET_MAX_LENGTH']['value'],
            'pallet_width' => 'required|numeric|min:' . $this->measurementSystemConfig['PALLET_MIN_WIDTH']['value'] . '|max:' . $this->measurementSystemConfig['PALLET_MAX_WIDTH']['value'],
            'pallet_height' => 'required|numeric|min:' . $this->measurementSystemConfig['PALLET_MIN_HEIGHT']['value'] . '|max:' . $this->measurementSystemConfig['PALLET_MAX_HEIGHT']['value']
        ], [
            'product_name.required' => 'The Product Name is required.',

            'product_type_id.required' => 'The Product Type is required.',

            'product_length.required' => 'The Product Length is required.',
            'product_length.min' => 'The Product Length must be at least :min.',
            'product_length.max' => 'The Product Length must not be greater than :max.',

            'product_width.required' => 'The Product Width is required.',
            'product_width.min' => 'The Product Width must be at least :min.',
            'product_width.max' => 'The Product Width must not be greater than :max.',

            'product_height.required' => 'The Product Height is required.',
            'product_height.min' => 'The Product Height must be at least :min.',
            'product_height.max' => 'The Product Height must not be greater than :max.',

            'product_weight.required' => 'The Product Weight is required.',
            'product_weight.min' => 'The Product Weight must be at least :min.',
            'product_weight.max' => 'The Product Weight must not be greater than :max.',

            'product_infeed_rate.required' => 'The Product Infeed Rate is required.',
            'product_infeed_rate.min' => 'The Product Infeed Rate must be at least :min.',
            'product_infeed_rate.max' => 'The Product Infeed Rate must not be greater than :max.',

            'pallet_length.required' => 'The Pallet Length is required.',
            'pallet_length.min' => 'The Pallet Length must be at least :min.',
            'pallet_length.max' => 'The Pallet Length must not be greater than :max.',

            'pallet_width.required' => 'The Pallet Width is required.',
            'pallet_width.min' => 'The Pallet Width must be at least :min.',
            'pallet_width.max' => 'The Pallet Width must not be greater than :max.',

            'pallet_height.required' => 'The Pallet Height is required.',
            'pallet_height.min' => 'The Pallet Height must be at least :min.',
            'pallet_height.max' => 'The Pallet Height must not be greater than :max.',
        ])->validate();

        if (empty($this->lead)){
            $this->lead = $leadWizardService->createLead([]);
        }
        $validated['lead_id'] = $this->lead->id;
        $validated['product_infeed_rate'] = $this->product_infeed_rate;
        $validated['gripper_id'] = PalletizerModule::where('slug', '=', $leadWizardService->matchGripperSelection($validated)['toolRequired'])->first()->id;

        if ($this->lead_configuration){
            $result = $leadWizardService->wizardDataStepChangeChecker($this->lead_configuration, $validated, self::PRODUCT_CONFIGURATION_STEP);

            if ($this->resettingAlertModalAccepted) {
                $this->livewireStepCleaner(self::PRODUCT_CONFIGURATION_STEP);
            } else if ($result) {
                $this->dispatch('resettingProgressAlert');
                return;
            }
        }

        $recalculatedData = $measurementSystemConfigService->recalculateSavingValues($validated, $this->measurementSystem);
        $leadWizardService->createOrUpdateLeadProductConfiguration($recalculatedData, $this->lead_configuration ? $this->lead_configuration->id : null);

        $this->mount();

        $this->next(self::CELL_CONFIGURATION_STEP);
    }

    public function updatedProductTypeId(ProductType $productType)
    {
        $this->productTypeImage = $productType->img_path;
        $this->productTypeWithPalletImage = $productType->img_with_pallet_path;
    }

    public function updatedRobotId(RobotDetail $robotDetail)
    {
        $this->dispatch('refreshRobotScene',
            robotModel: json_encode($robotDetail->palletizer_module->cadModel),
            gripperModel: json_encode($this->gripper->cadModel)
        );
    }

    public function saveCell(LeadWizardService $leadWizardService)
    {
        $cellConfigurationService = new  CellConfigurationService();

        $this->resetValidation();
        $validated = Validator::make([
            'product_infeed_id' => $this->product_infeed_id,
            'left_pallet_position_id' => $this->left_pallet_position_id,
            'right_pallet_position_id' => $this->right_pallet_position_id,
        ], [
            'product_infeed_id' => 'required',
            'left_pallet_position_id' => 'required',
            'right_pallet_position_id' => 'required',
        ], [
            'product_infeed_id.required' => 'The Product Infeed is required.',
            'left_pallet_position_id.required' => 'The Left Pallet Position is required.',
            'right_pallet_position_id.required' => 'The Right Pallet Position is required.',
        ])->validate();

        $result = $leadWizardService->wizardDataStepChangeChecker($this->lead_configuration, $validated, self::CELL_CONFIGURATION_STEP);

        if ($this->resettingAlertModalAccepted) {
            $this->livewireStepCleaner(self::PRODUCT_CONFIGURATION_STEP);
        }

        if ($result) {
            $this->dispatch('resettingProgressAlert');
            return;
        }

        $this->lead_configuration = $leadWizardService->createOrUpdateLeadProductConfiguration([
            'replaced_product_infeed_id' => $cellConfigurationService->replacmentInfeedModel($this->product_infeed_id, $this->left_pallet_position_id, $this->right_pallet_position_id)?->module->id,
            'replaced_left_pallet_position_id' => $cellConfigurationService->replacmentLeftModel($this->left_pallet_position_id, $this->product_infeed_id)?->module->id,
            'replaced_right_pallet_position_id' => $cellConfigurationService->replacmentRightModel($this->right_pallet_position_id, $this->product_infeed_id)?->module->id,
        ], $this->lead_configuration ? $this->lead_configuration->id : null);

        $leadWizardService->createOrUpdateLeadProductConfiguration($validated, $this->lead_configuration?->id);
        $this->mount();
        $this->next(self::PALLET_HEIGHT_CONFIGURATION_STEP);
    }

    #[On('cellChange')]
    public function cellChange()
    {
        if (!empty($this->product_infeed_id) && !empty($this->left_pallet_position_id)){
            $this->right_pallet_positions = CellConfigurationService::getRightSidesModules($this->lead, $this->product_infeed_id, $this->left_pallet_position_id);
        }
        elseif(!empty($this->product_infeed_id) && !empty($this->right_pallet_position_id)){
            $this->left_pallet_positions =  CellConfigurationService::getLeftSidesModules($this->lead, $this->product_infeed_id);
        } elseif (!empty($this->product_infeed_id)) {
            $this->left_pallet_position_id = null;
            $this->right_pallet_position_id = null;
            $this->left_pallet_positions = [];
            $this->right_pallet_positions = [];
            $this->left_pallet_positions = !empty($this->product_infeed_id) ? CellConfigurationService::getLeftSidesModules($this->lead, $this->product_infeed_id) : [];
            $this->right_pallet_positions = !empty($this->product_infeed_id) ? CellConfigurationService::getRightSidesModules($this->lead, $this->product_infeed_id, $this->left_pallet_position_id) : [];
            $this->left_right_disable = 'enabled';
        } else {
            $this->left_pallet_positions = [];
            $this->right_pallet_positions = [];
        }
        $this->dispatch('model-load');
    }

    public function updatedProductInfeedId(PalletizerModule $infeedModule)
    {
        $this->resetValidation();
        if ($this->lead_configuration->product_infeed_id != $this->product_infeed_id){
            $this->left_pallet_positions = [];
            $this->right_pallet_positions = [];
            $this->left_pallet_position_id = '';
            $this->right_pallet_position_id = '';

            $this->dispatch('refresh3DScene',
                model: json_encode(PalletizerModule::where('slug', self::DEFAULT_LEFT_SIDE_SLUG)->first()?->cadModel),
                meshKey: 'left_pallet_position_id'
            );

            $this->dispatch('refresh3DScene',
                model: json_encode(PalletizerModule::where('slug', self::DEFAULT_RIGHT_SIDE_SLUG)->first()?->cadModel),
                meshKey: 'right_pallet_position_id'
            );
        }
        $this->left_pallet_positions = $infeedModule->id ? CellConfigurationService::getLeftSidesModules($this->lead, $infeedModule->id) : [];

        $this->dispatch('refresh3DScene',
            model: json_encode($infeedModule->cadModel),
            meshKey: 'product_infeed_id'
        );
    }

    public function updatedLeftPalletPositionId(PalletizerModule $leftPalletModule)
    {
        $this->resetValidation();
        $cellConfigurationService = new  CellConfigurationService();
        $infeedModel = PalletizerModule::find($this->product_infeed_id);

        if ($this->lead_configuration->left_pallet_position_id != $this->left_pallet_position_id){
            $this->right_pallet_positions = [];
            if (!empty($this->right_pallet_position_id)){
                $this->right_pallet_position_id = '';
                $this->dispatch('refresh3DScene',
                    model: json_encode(PalletizerModule::where('slug', self::DEFAULT_RIGHT_SIDE_SLUG)->first()->cadModel),
                    meshKey: 'right_pallet_position_id'
                );
            }
        }
        $this->right_pallet_positions = $this->left_pallet_position_id ? CellConfigurationService::getRightSidesModules($this->lead, $this->product_infeed_id, $this->left_pallet_position_id) : [];
        $cadModel = $cellConfigurationService->replacmentLeftModel($leftPalletModule->id, $infeedModel->id)?->module->cadModel ?? $leftPalletModule->cadModel;
        $infeedCadModel = $cellConfigurationService->replacmentInfeedModel($infeedModel->id, $this->left_pallet_position_id, $this->right_pallet_position_id)?->module->cadModel ?? $infeedModel->cadModel;

        $this->dispatch('refresh3DScene',
            model: json_encode($cadModel),
            meshKey: 'left_pallet_position_id'
        );

        $this->dispatch('refresh3DScene',
            model: json_encode($infeedCadModel),
            meshKey: 'product_infeed_id'
        );
    }

    public function updatedRightPalletPositionId(PalletizerModule $rightPalletModule)
    {
        $this->resetValidation();
        $cellConfigurationService = new  CellConfigurationService();
        $infeedModel = PalletizerModule::find($this->product_infeed_id);

        $cadModel = $cellConfigurationService->replacmentRightModel($rightPalletModule->id, $infeedModel->id)?->module->cadModel ?? $rightPalletModule->cadModel;
        $infeedCadModel = $cellConfigurationService->replacmentInfeedModel($infeedModel->id, $this->left_pallet_position_id, $this->right_pallet_position_id)?->module->cadModel ?? $infeedModel->cadModel;

        $this->dispatch('refresh3DScene',
            model: json_encode($cadModel),
            meshKey: 'right_pallet_position_id'
        );

        $this->dispatch('refresh3DScene',
            model: json_encode($infeedCadModel),
            meshKey: 'product_infeed_id'
        );
    }

    public function savePalletHeight(LeadWizardService $leadWizardService)
    {
        $measurementSystemConfigService = new  MeasurementSystemConfigService();

        $validated = Validator::make([
            'system_pallet_height' => $this->system_pallet_height,
        ], [
            'system_pallet_height' => 'required|numeric|min:' . LeadProductConfiguration::SYSTEM_PALLET_MIN_HIGHT . '|max:' . LeadProductConfiguration::SYSTEM_PALLET_MAX_HIGHT,
        ], [
            'system_pallet_height.required' => 'The Max Allowable total pallet height is required.',
            'system_pallet_height.min' => 'The Max Allowable total pallet height must be at least :min.',
            'system_pallet_height.max' => 'The Max Allowable total pallet height must not be greater than :max.',
        ])->validate();

        $result = $leadWizardService->wizardDataStepChangeChecker($this->lead_configuration, $validated, self::PALLET_HEIGHT_CONFIGURATION_STEP);

        if ($this->resettingAlertModalAccepted) {
            $this->livewireStepCleaner(self::PRODUCT_CONFIGURATION_STEP);
        }

        if ($result) {
            $this->dispatch('resettingProgressAlert');
            return;
        }

        $recalculatedData = $measurementSystemConfigService->recalculateSavingValues($validated, $this->measurementSystem);
        $leadWizardService->createOrUpdateLeadProductConfiguration($recalculatedData, $this->lead_configuration ? $this->lead_configuration->id : null);

        $this->mount();
        $this->next(self::ROBOT_SELECTION_STEP);
    }

    public function saveSelectedRobot(LeadWizardService $leadWizardService, PalletizerConfigurator $palletizerConfigurator)
    {
        $validated = Validator::make([
            'robot_id' => $this->robot_id,
        ], [
            'robot_id' => 'required',
        ],[
            'robot_id.required' => 'The Robot is required.',
        ])->validate();

        $data['robot_id'] = RobotDetail::find($validated['robot_id'])->palletizer_module_id;

        $leadWizardService->createOrUpdateLeadProductConfiguration($data, $this->lead_configuration ? $this->lead_configuration->id : null);
        $this->mount();
        $this->totalAmount = $palletizerConfigurator->getTotalAmount($this->lead_configuration);
        $this->lead_configuration = $leadWizardService->createOrUpdateLeadProductConfiguration(['total_price' => $this->totalAmount], $this->lead_configuration ? $this->lead_configuration->id : null);
        $this->next(self::SYSTEM_SUMMARY_STEP);
    }

    #[On('saveRequestCustomization')]
    public function saveRequestCustomization(LeadWizardService $leadWizardService): void
    {
        $validated = Validator::make([
            'request_customization' => $this->request_customization,
        ], [
            'request_customization' => 'required',
        ])->validate();

        $leadWizardService->createOrUpdateLeadProductConfiguration($validated, $this->lead_configuration ? $this->lead_configuration->id : null);
    }

    #[On('systemSummarySubmit')]
    public function systemSummarySubmit()
    {
        $cellConfigurationService = new  CellConfigurationService();
        $leadWizardService = new  LeadWizardService();

        $cellConfigurationService->extendedConfigurationReplacement($this->lead_configuration);

        if ($this->lead_configuration->status == LeadProductConfiguration::STATUS_DRAFT) {
            event(new HubSpotProcessedEvent($this->lead, $this->lead_configuration));
        }

        $this->lead_configuration = $leadWizardService->createOrUpdateLeadProductConfiguration(['status' => LeadProductConfiguration::STATUS_OVERVIEW], $this->lead_configuration ? $this->lead_configuration->id : null);

        $this->dispatch('systemSummarySubmitComplete');
    }

    public function completeWizard(LeadWizardService $leadWizardService)
    {
        $this->lead_configuration = $leadWizardService->createOrUpdateLeadProductConfiguration(['status' => LeadProductConfiguration::STATUS_COMPLETED], $this->lead_configuration ? $this->lead_configuration->id : null);

        return redirect(route('wizard'));
    }

    public function save(LeadWizardService $leadWizardService, PalletizerConfigurator $palletizerConfigurator)
    {
//        $this->totalAmount = $palletizerConfigurator->getTotalAmount($this->lead_configuration);
//        $this->lead_configuration = $leadWizardService->createOrUpdateLeadProductConfiguration(['total_price' => $this->totalAmount], $this->lead_configuration);
    }

    public function stepCheker()
    {
        return match (true) {
            empty($this->lead_configuration) => self::PRODUCT_CONFIGURATION_STEP,
            empty($this->lead_configuration->product_infeed_id) && empty($this->lead_configuration->left_pallet_positions) && empty($this->lead_configuration->right_pallet_positions) => self::CELL_CONFIGURATION_STEP,
            empty($this->lead_configuration->system_pallet_height) => self::PALLET_HEIGHT_CONFIGURATION_STEP,
            empty($this->lead_configuration->robot_id) => self::ROBOT_SELECTION_STEP,
            !empty($this->lead_configuration->robot_id) => self::SYSTEM_SUMMARY_STEP,
            default => 1
        };
    }

    public function next($step)
    {
        $this->currentStep = $step;
        $this->dispatch('change-step', step: $step);
    }

    public function back($step)
    {
        $this->resetValidation();
        $this->currentStep = $step;
        $this->dispatch('change-step', step: $step);
    }

    public function notifyInfeed()
    {
        if (intval($this->product_infeed_rate) > 7.5){
            $this->product_infeed_rate_notify = self::NOTIFY_INFEED;
        } else {
            $this->product_infeed_rate_notify = '';
        }
    }

    public function getDefaultModelsPath()
    {
        $cellConfigurationService = new  CellConfigurationService();

        $productInfeedPosition = $this->lead?->lead_product_configuration?->infeedPosition;
        $leftPalletPosition = $this->lead?->lead_product_configuration?->leftPosition;
        $rightPalletPosition = $this->lead?->lead_product_configuration?->rightPosition;

        $defaultInfeed = PalletizerModule::where('slug', self::DEFAULT_CONVEYOR_SLUG)->first();
        $defaultLeftPalletPosition = PalletizerModule::where('slug', self::DEFAULT_LEFT_SIDE_SLUG)->first();
        $defaultRightPalletPosition = PalletizerModule::where('slug', self::DEFAULT_RIGHT_SIDE_SLUG)->first();
        $defaultMainCell = PalletizerModule::where('slug', self::MAIN_CELL_SLUG)->first();

        return [
            "product_infeed_id" => PalletizerModule::find(match (true) {
                !empty($productInfeedPosition && $replacementInfeedModel = intval($cellConfigurationService->replacmentInfeedModel($productInfeedPosition->id, $leftPalletPosition->id, $rightPalletPosition->id)?->cell_editable_to_module_id)) => $replacementInfeedModel,
                !empty($productInfeedPosition && $selectedInfeedModel = $productInfeedPosition->id) => $selectedInfeedModel,
                !empty($defaultInfeedModel = $defaultInfeed->id) => $defaultInfeedModel,
            })?->cadModel?->path,
            "left_pallet_position_id" => PalletizerModule::find(match (true) {
                !empty($productInfeedPosition && $leftPalletPosition && $replacementLeftPalletModel = intval($cellConfigurationService->replacmentLeftModel($productInfeedPosition->id, $leftPalletPosition->id)?->cell_editable_to_module_id)) => $replacementLeftPalletModel,
                !empty($leftPalletPosition && $selectedLeftPalletModel = $leftPalletPosition->id) => $selectedLeftPalletModel,
                !empty($defaultLeftPalletModel = $defaultLeftPalletPosition->id) => $defaultLeftPalletModel,
            })?->cadModel?->path,
            "right_pallet_position_id" => PalletizerModule::find(match (true) {
                !empty($productInfeedPosition && $rightPalletPosition && $replacementRightPalletModel = intval($cellConfigurationService->replacmentRightModel($productInfeedPosition->id, $rightPalletPosition->id)?->cell_editable_to_module_id)) => $replacementRightPalletModel,
                !empty($rightPalletPosition && $selectedRightPalletModel = $rightPalletPosition->id) => $selectedRightPalletModel,
                !empty($defaultRightPalletModel = $defaultRightPalletPosition->id) => $defaultRightPalletModel,
            })?->cadModel?->path,
            "main_cell" => $defaultMainCell?->cadModel?->path
        ];
    }

    public function livewireStepCleaner($step)
    {
        if ($step === Wizard::PRODUCT_CONFIGURATION_STEP) {
            $this->product_infeed_id = '';
            $this->left_pallet_position_id = '';
            $this->right_pallet_position_id = '';
            $this->system_pallet_height = '';
            $this->robot_id = '';
            $this->dispatch('loadDefault');
        }
        if ($step === Wizard::CELL_CONFIGURATION_STEP) {
            $this->system_pallet_height = '';
            $this->robot_id = '';
            $this->dispatch('change-step');
        }
        if ($step === Wizard::PALLET_HEIGHT_CONFIGURATION_STEP) {
            $this->robot_id = '';
            $this->dispatch('refresh3DScene');
        }
    }

    public function acceptResettingAlertModal()
    {
        $this->resettingAlertModalAccepted = true;
        $stepFunctionName = $this->stepFunctionsMapping[$this->currentStep];
        $this->$stepFunctionName(new LeadWizardService());
    }

    public function changeMeasurementSystem()
    {
        $this->resetValidation();

        $this->measurementSystem = ($this->measurementSystem === MeasurementSystemConfigService::MEASURE_SYSTEM_METRIC) ? MeasurementSystemConfigService::MEASURE_SYSTEM_IMPERIAL : MeasurementSystemConfigService::MEASURE_SYSTEM_METRIC;
        Session::put('metricalSystem', $this->measurementSystem);

        $this->measurementSystemRecalculation();
    }

    public function measurementSystemRecalculation()
    {
        $measurementSystemConfigService = new MeasurementSystemConfigService();

        $this->measurementSystem = !empty(Session::get('metricalSystem')) ? Session::get('metricalSystem') : MeasurementSystemConfigService::MEASURE_SYSTEM_METRIC;
        $this->measurementSystemConfig = $measurementSystemConfigService->getConfig($this->measurementSystem);

        if ($this->lead_configuration){
            $this->product_length = $measurementSystemConfigService->getDimension($this->lead_configuration->product_length, $this->measurementSystem);
            $this->product_width = $measurementSystemConfigService->getDimension($this->lead_configuration->product_width, $this->measurementSystem);
            $this->product_height = $measurementSystemConfigService->getDimension($this->lead_configuration->product_height, $this->measurementSystem);
            $this->product_weight = $measurementSystemConfigService->getWeight($this->lead_configuration->product_weight, $this->measurementSystem);
            $this->product_infeed_rate = $this->lead_configuration->product_infeed_rate;
            $this->pallet_length = $measurementSystemConfigService->getDimension($this->lead_configuration->pallet_length, $this->measurementSystem);
            $this->pallet_width = $measurementSystemConfigService->getDimension($this->lead_configuration->pallet_width, $this->measurementSystem);
            $this->pallet_height = $measurementSystemConfigService->getDimension($this->lead_configuration->pallet_height, $this->measurementSystem);

            if ($this->lead_configuration->system_pallet_height) {
                $this->system_pallet_height = $measurementSystemConfigService->getDimension($this->lead_configuration->system_pallet_height, $this->measurementSystem);
            }
        }
    }
}
