<div>
    <div class="mdc-tab-bar" role="tablist">
        <div class="mdc-tab-scroller">
            <div class="mdc-tab-scroller__scroll-area mdc-tab-scroller__scroll-area--scroll mb0">
                <div class="mdc-tab-scroller__scroll-content">
                    <button {{ \App\Livewire\Wizard::PRODUCT_CONFIGURATION_STEP > $this->stepCheker() ? 'disabled' : '' }}
                            wire:click="back(1)"
                            class="mdc-tab {{ $currentStep == \App\Livewire\Wizard::PRODUCT_CONFIGURATION_STEP ? 'mdc-tab--active' : '' }}"
                            role="tab" tabindex="0">
                            <span class="mdc-tab__content">
                                <span class="mdc-tab__text-label">Product</span>
                            </span>
                        <span {{ \App\Livewire\Wizard::PRODUCT_CONFIGURATION_STEP > $this->stepCheker() ? 'disabled' : '' }}
                            class="mdc-tab-indicator {{ $currentStep == \App\Livewire\Wizard::PRODUCT_CONFIGURATION_STEP ? 'mdc-tab-indicator--active' : '' }}">
                                <span class="mdc-tab-indicator__content mdc-tab-indicator__content--underline"></span>
                            </span>
                        <span class="mdc-tab__ripple"></span>
                    </button>
                    <button {{ \App\Livewire\Wizard::CELL_CONFIGURATION_STEP > $this->stepCheker() ? 'disabled' : '' }}
                            wire:click="back(2)"
                            class="mdc-tab {{ $currentStep == \App\Livewire\Wizard::CELL_CONFIGURATION_STEP ? 'mdc-tab--active' : '' }}"
                            role="tab" tabindex="0">
                            <span class="mdc-tab__content">
                                <span class="mdc-tab__text-label">Cell</span>
                            </span>
                        <span
                            class="mdc-tab-indicator {{ $currentStep == \App\Livewire\Wizard::CELL_CONFIGURATION_STEP ? 'mdc-tab-indicator--active' : '' }}">
                                <span class="mdc-tab-indicator__content mdc-tab-indicator__content--underline"></span>
                            </span>
                        <span class="mdc-tab__ripple"></span>
                    </button>
                    <button {{ \App\Livewire\Wizard::PALLET_HEIGHT_CONFIGURATION_STEP > $this->stepCheker() ? 'disabled' : '' }}
                            wire:click="back(3)"
                            class="mdc-tab {{ $currentStep == \App\Livewire\Wizard::PALLET_HEIGHT_CONFIGURATION_STEP ? 'mdc-tab--active' : '' }}"
                            role="tab" tabindex="0">
                            <span class="mdc-tab__content">
                                <span class="mdc-tab__text-label">Facility</span>
                            </span>
                        <span
                            class="mdc-tab-indicator {{ $currentStep == \App\Livewire\Wizard::PALLET_HEIGHT_CONFIGURATION_STEP ? 'mdc-tab-indicator--active' : '' }}">
                                <span class="mdc-tab-indicator__content mdc-tab-indicator__content--underline"></span>
                            </span>
                        <span class="mdc-tab__ripple"></span>
                    </button>
                    <button {{ \App\Livewire\Wizard::ROBOT_SELECTION_STEP > $this->stepCheker() ? 'disabled' : '' }}
                            wire:click="back(4)"
                            class="mdc-tab {{ $currentStep == \App\Livewire\Wizard::ROBOT_SELECTION_STEP ? 'mdc-tab--active' : '' }}"
                            role="tab" tabindex="0">
                            <span class="mdc-tab__content">
                                <span class="mdc-tab__text-label">Robot</span>
                            </span>
                        <span
                            class="mdc-tab-indicator {{ $currentStep == \App\Livewire\Wizard::ROBOT_SELECTION_STEP ? 'mdc-tab-indicator--active' : '' }}">
                                <span class="mdc-tab-indicator__content mdc-tab-indicator__content--underline"></span>
                            </span>
                        <span class="mdc-tab__ripple"></span>
                    </button>
                    <button {{ \App\Livewire\Wizard::SYSTEM_SUMMARY_STEP > $this->stepCheker() ? 'disabled' : '' }}
                            wire:click="back(5)"
                            class="mdc-tab {{ $currentStep == \App\Livewire\Wizard::SYSTEM_SUMMARY_STEP ? 'mdc-tab--active' : '' }}"
                            role="tab" tabindex="0">
                            <span class="mdc-tab__content">
                                <span class="mdc-tab__text-label">Summary</span>
                            </span>
                        <span
                            class="mdc-tab-indicator {{ $currentStep == \App\Livewire\Wizard::SYSTEM_SUMMARY_STEP ? 'mdc-tab-indicator--active' : '' }}">
                                <span class="mdc-tab-indicator__content mdc-tab-indicator__content--underline"></span>
                            </span>
                        <span class="mdc-tab__ripple"></span>
                    </button>
                    <button type="button" class="measurement" onclick="openChangeMeasurementSystemDialog()">
                        <span class="mdi mdi-cog-outline"></span>
                    </button>
                </div>
            </div>
        </div>

        <form wire:submit="save" novalidate name="test">
            {{--     Product Confirmation       --}}
            <div class="mdc-card mdc-card--outlined productConfiguration" style="{{ $currentStep == \App\Livewire\Wizard::PRODUCT_CONFIGURATION_STEP ? 'display:block' : 'display:none' }}">
                <div class="wizardForm">
                    <div class="mdc-layout-grid__inner reverseMobile">
                        <div class="mdc-layout-grid__cell--span-10">
                            <div class="mdc-layout-grid">
                                <div class="mdc-layout-grid__inner">
                                    <div class="mdc-layout-grid__cell--span-6">
                                        <div class="mdc-layout-grid">
                                            <label class="mdc-text-field mdc-text-field--outlined mdc-text-field--label-floating w100 @error('product_name') mdc-text-field--invalid @enderror">
                                                    <span class="mdc-notched-outline {{ !empty($product_name) ? 'mdc-notched-outline--upgraded mdc-notched-outline--notched' : '' }}">
                                                        <span class="mdc-notched-outline__leading"></span>
                                                        <span class="mdc-notched-outline__notch">
                                                            <span class="mdc-floating-label {{ !empty($product_name) ? 'mdc-floating-label--float-above' : '' }}" id="product_name">Product Name:*</span>
                                                        </span>
                                                        <span class="mdc-notched-outline__trailing"></span>
                                                    </span>
                                                <input type="text" wire:model.blur="product_name"
                                                       class="mdc-text-field__input"
                                                       aria-labelledby="product_name">
                                            </label>
                                            <div>
                                                @error('product_name') <span class="error">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="mdc-layout-grid selectContainer">
                                            <x-md-dropdown wire:model="product_type_id" :options="$product_types" placeholder="Select Product Type" />
                                        </div>
                                        <div class="mdc-layout-grid">
                                            <label class="mdc-text-field mdc-text-field--outlined mdc-text-field--label-floating w100 @error('product_length') mdc-text-field--invalid @enderror">
                                                <span class="mdc-notched-outline {{ !empty($product_length) ? 'mdc-notched-outline--upgraded mdc-notched-outline--notched' : '' }}">
                                                    <span class="mdc-notched-outline__leading"></span>
                                                    <span class="mdc-notched-outline__notch">
                                                        <span class="mdc-floating-label {{ !empty($product_length) ? 'mdc-floating-label--float-above' : '' }}" id="product_length">Product Length:*<span class="helper"> ({{ $measurementSystemConfig['PRODUCT_MIN_LENGTH']['value'] }}-{{ $measurementSystemConfig['PRODUCT_MAX_LENGTH']['value'] }} {{ $measurementSystemConfig['PRODUCT_MIN_LENGTH']['code']}})</span></span>
                                                    </span>
                                                    <span class="mdc-notched-outline__trailing"></span>
                                                </span>
                                                <input type="text" wire:model.blur="product_length" class="mdc-text-field__input"
                                                       aria-labelledby="product_length" onkeydown="@if($measurementSystem === "imperial") numberswithdotonly(event) @else numbersonly(event)@endif"
                                                       >
                                            </label>
                                            <div>
                                                @error('product_length') <span class="error">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="mdc-layout-grid">
                                            <label class="mdc-text-field mdc-text-field--outlined mdc-text-field--label-floating w100 @error('product_height') mdc-text-field--invalid @enderror">
                                            <span class="mdc-notched-outline {{ !empty($product_height) ? 'mdc-notched-outline--upgraded mdc-notched-outline--notched' : '' }}">
                                                <span class="mdc-notched-outline__leading"></span>
                                                <span class="mdc-notched-outline__notch">
                                                    <span class="mdc-floating-label {{ !empty($product_height) ? 'mdc-floating-label--float-above' : '' }}" id="product_height">Product Height:*<span class="helper">({{ $measurementSystemConfig['PRODUCT_MIN_HEIGHT']['value'] }}-{{ $measurementSystemConfig['PRODUCT_MAX_HEIGHT']['value'] }} {{ $measurementSystemConfig['PRODUCT_MIN_HEIGHT']['code']}})</span></span>
                                                </span>
                                                <span class="mdc-notched-outline__trailing"></span>
                                            </span>
                                                <input type="text" wire:model.blur="product_height" class="mdc-text-field__input" aria-labelledby="product_height" onkeydown="numberswithdotonly(event)">
                                            </label>
                                            <div>
                                                @error('product_height') <span class="error">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="mdc-layout-grid">
                                            <div wire:ignore class="w100">
                                                <label class="active" for="product_infeed_rate">Product Infeed Rate:* (min/max: 1-15)</label>
                                                <div class="mdc-slider mdc-slider--discrete" id="product_infeed_rate">
                                                    <input class="mdc-slider__input" type="range" name="product_infeed_rate"
                                                           min="1" max="15" step="0.1" value="{{ $product_infeed_rate }}"/>
                                                    <div class="mdc-slider__track">
                                                        <div class="mdc-slider__track--inactive"></div>
                                                        <div class="mdc-slider__track--active">
                                                            <div class="mdc-slider__track--active_fill"></div>
                                                        </div>
                                                    </div>
                                                    <div class="mdc-slider__thumb">
                                                        <div class="mdc-slider__value-indicator-container" aria-hidden="true">
                                                            <div class="mdc-slider__value-indicator">
                                                                <span class="mdc-slider__value-indicator-text">50</span>
                                                            </div>
                                                        </div>
                                                        <div class="mdc-slider__thumb-knob"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="notifyInfeed">{{ $product_infeed_rate_notify }}</p>
                                            @if ($errors->has('product_weight'))
                                                <span class="error">{{ $errors->first('product_infeed_rate') }}</span>
                                            @endif
                                            <div>
                                                @error('product_infeed_rate') <span class="error">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="mdc-layout-grid">
                                            <label class="mdc-text-field mdc-text-field--outlined mdc-text-field--label-floating w100 @error('pallet_height') mdc-text-field--invalid @enderror">
                                            <span class="mdc-notched-outline {{ !empty($pallet_height) ? 'mdc-notched-outline--upgraded mdc-notched-outline--notched' : '' }}">
                                                <span class="mdc-notched-outline__leading"></span>
                                                <span class="mdc-notched-outline__notch">
                                                    <span class="mdc-floating-label {{ !empty($pallet_height) ? 'mdc-floating-label--float-above' : '' }}" id="pallet_height">Pallet Height:*<span class="helper"> ({{ $measurementSystemConfig['PALLET_MIN_HEIGHT']['value'] }}-{{ $measurementSystemConfig['PALLET_MAX_HEIGHT']['value']}} {{ $measurementSystemConfig['PALLET_MIN_HEIGHT']['code']}})</span></span>
                                                </span>
                                                <span class="mdc-notched-outline__trailing"></span>
                                            </span>
                                                <input type="text" wire:model.blur="pallet_height" class="mdc-text-field__input" aria-labelledby="pallet_height" onkeydown="numberswithdotonly(event)">
                                            </label>
                                            <div>
                                                @error('pallet_height') <span class="error">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="mdc-layout-grid">
                                            <label class="mdc-text-field mdc-text-field--outlined mdc-text-field--label-floating w100 @error('pallet_width') mdc-text-field--invalid @enderror">
                                            <span class="mdc-notched-outline {{ !empty($pallet_width) ? 'mdc-notched-outline--upgraded mdc-notched-outline--notched' : '' }}">
                                                <span class="mdc-notched-outline__leading"></span>
                                                <span class="mdc-notched-outline__notch">
                                                    <span class="mdc-floating-label {{ !empty($pallet_width) ? 'mdc-floating-label--float-above' : '' }}" id="pallet_width">Pallet Width:*<span class="helper"> ({{ $measurementSystemConfig['PALLET_MIN_WIDTH']['value'] }}-{{ $measurementSystemConfig['PALLET_MAX_WIDTH']['value'] }} {{ $measurementSystemConfig['PALLET_MIN_WIDTH']['code']}})</span></span>
                                                </span>
                                                <span class="mdc-notched-outline__trailing"></span>
                                            </span>
                                                <input type="text" wire:model.blur="pallet_width" class="mdc-text-field__input"
                                                       aria-labelledby="pallet_width" onkeydown="@if($measurementSystem === "imperial") numberswithdotonly(event) @else numbersonly(event)@endif">
                                            </label>
                                            <div>
                                                @error('pallet_width') <span class="error">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mdc-layout-grid__cell--span-6">
                                        <div class="mdc-layout-grid visualShowContainer">
                                            <img id="product_img" src="{{ $productTypeImage }}" class="mwWebkit" >
                                        </div>
                                        <div class="mdc-layout-grid afterImage">
                                            <label class="mdc-text-field mdc-text-field--outlined mdc-text-field--label-floating w100 @error('product_width') mdc-text-field--invalid @enderror">
                                                <span class="mdc-notched-outline {{ !empty($product_width) ? 'mdc-notched-outline--upgraded mdc-notched-outline--notched' : '' }}">
                                                    <span class="mdc-notched-outline__leading"></span>
                                                    <span class="mdc-notched-outline__notch">
                                                        <span class="mdc-floating-label {{ !empty($product_width) ? 'mdc-floating-label--float-above' : '' }}" id="product_width">Product Width:*<span class="helper"> ({{ $measurementSystemConfig['PRODUCT_MIN_WIDTH']['value'] }}-{{ $measurementSystemConfig['PRODUCT_MAX_WIDTH']['value'] }} {{ $measurementSystemConfig['PRODUCT_MIN_WIDTH']['code']}})</span></span>
                                                    </span>
                                                    <span class="mdc-notched-outline__trailing"></span>
                                                </span>
                                                <input type="text" wire:model.blur="product_width" class="mdc-text-field__input"
                                                       aria-labelledby="product_width" onkeydown="@if($measurementSystem === "imperial") numberswithdotonly(event) @else numbersonly(event)@endif"
                                                       >
                                            </label>
                                            <div>
                                                @error('product_width') <span class="error">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="mdc-layout-grid">
                                            <label class="mdc-text-field mdc-text-field--outlined mdc-text-field--label-floating w100 @error('product_weight') mdc-text-field--invalid @enderror">
                                            <span class="mdc-notched-outline {{ !empty($product_weight) ? 'mdc-notched-outline--upgraded mdc-notched-outline--notched' : '' }}">
                                                <span class="mdc-notched-outline__leading"></span>
                                                <span class="mdc-notched-outline__notch">
                                                    <span class="mdc-floating-label {{ !empty($product_weight) ? 'mdc-floating-label--float-above' : '' }}" id="product_weight">Product Weight:*<span class="helper"> ({{ $measurementSystemConfig['PRODUCT_MIN_WEIGHT']['value'] }}-{{ $measurementSystemConfig['PRODUCT_MAX_WEIGHT']['value'] }} {{ $measurementSystemConfig['PRODUCT_MIN_WEIGHT']['code']}})</span></span>
                                                </span>
                                                <span class="mdc-notched-outline__trailing"></span>
                                            </span>
                                                <input type="text" wire:model.blur="product_weight"
                                                       class="mdc-text-field__input"
                                                       aria-labelledby="product_weight" onkeydown="@if($measurementSystem === "imperial") numberswithdotonly(event) @else numbersonly(event)@endif">
                                            </label>
                                            <div>
                                                @error('product_weight') <span class="error">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="mdc-layout-grid emptyGrid">&nbsp;</div>
                                        <div class="mdc-layout-grid">
                                            <label class="mdc-text-field mdc-text-field--outlined mdc-text-field--label-floating w100 @error('pallet_length') mdc-text-field--invalid @enderror">
                                            <span class="mdc-notched-outline {{ !empty($pallet_length) ? 'mdc-notched-outline--upgraded mdc-notched-outline--notched' : '' }}">
                                                <span class="mdc-notched-outline__leading"></span>
                                                <span class="mdc-notched-outline__notch">
                                                    <span class="mdc-floating-label {{ !empty($pallet_length) ? 'mdc-floating-label--float-above' : '' }}" id="pallet_length">Pallet Length:*<span class="helper"> ({{ $measurementSystemConfig['PALLET_MIN_LENGTH']['value'] }}-{{ $measurementSystemConfig['PALLET_MAX_LENGTH']['value']}} {{ $measurementSystemConfig['PALLET_MIN_LENGTH']['code']}})</span></span>
                                                </span>
                                                <span class="mdc-notched-outline__trailing"></span>
                                            </span>
                                                <input type="text" wire:model.blur="pallet_length" class="mdc-text-field__input"
                                                       aria-labelledby="pallet_length" onkeydown="@if($measurementSystem === "imperial") numberswithdotonly(event) @else numbersonly(event)@endif">
                                            </label>
                                            <div>
                                                @error('pallet_length') <span class="error">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mdc-card__actions my20">
                        <div class="mdc-layout-grid__cell--span-2">
                            <div class="mdc-layout-grid--align-left">
                                <button
                                    wire:click="createOrUpdateLeadProductConfiguration"
                                    class="mdc-button mdc-card__action mdc-card__action--button mdc-button--raised mdc-ripple-upgraded validate">
                                    <span class="mdc-button__label">Next</span>
                                    <div class="mdc-button__ripple"></div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{--     Cell Configuration       <--}}
            <div class="mdc-card mdc-card--outlined cellConfiguration" style="{{ $currentStep == \App\Livewire\Wizard::CELL_CONFIGURATION_STEP ? 'display:block' : 'display:none' }}">
                <div class="mdc-layout-grid">
                    <div class="mdc-layout-grid__inner">
                        @if($currentStep == \App\Livewire\Wizard::CELL_CONFIGURATION_STEP)
                            <div  class="mdc-layout-grid__cell--span-4" id="cell-configuration-tab">
                                <div class="inline-text-field-container mt20 ml20">
                                    <div class="w100 selectContainer">
                                        <x-md-dropdown wire:model="product_infeed_id" :options="$product_infeeds" placeholder="Select Infeed Position:" />
                                    </div>
                                </div>
                                <div class="inline-text-field-container mt20 ml20">
                                    <div class="w100 selectContainer">
                                        <x-md-dropdown wire:model="left_pallet_position_id" :options="$left_pallet_positions" placeholder="Select Left Pallet Position:" />
                                    </div>
                                </div>
                                <div class="inline-text-field-container mt20 ml20">
                                    <div class="w100 selectContainer">
                                        <x-md-dropdown wire:model="right_pallet_position_id" :options="$right_pallet_positions" placeholder="Select Right Pallet Position:" />
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="mdc-layout-grid__cell--span-8 last-element">
                            <div wire:ignore id="canvas-step-2" class="cell-scene-zone">
                                <canvas id="renderCanvas"></canvas>
                                <div id="cameraIcon" style="position: absolute; bottom: 10px; right: 10px; font-size: 24px;">
                                    <span class="mdi mdi-rotate-3d"></span>
                                </div>
                                <div id="loader" class="loader"></div>
                                <div id="scene-overlay"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mdc-card__actions my20 justify-right">
                    <div class="mdc-layout-grid__cell--span-2">
                        <div class="mdc-layout-grid--align-left">
                            <button
                                wire:click="back(1)"
                                class="mdc-button mdc-card__action mdc-card__action--button mdc-button-black--raised mdc-ripple-upgraded validate">
                                <span class="mdc-button__label-white">Back</span>
                                <div class="mdc-button__ripple"></div>
                            </button>
                            <button
                                wire:click="saveCell"
                                class="mdc-button mdc-card__action mdc-card__action--button mdc-button--raised mdc-ripple-upgraded validate">
                                <span class="mdc-button__label">Next</span>
                                <div class="mdc-button__ripple"></div>
                            </button>
                        </div>
                    </div>
                    <div class="mdc-layout-grid--align-right">
                        <button type="button"
                                onclick="openDialog()"
                                class="mdc-button mdc-card__action mdc-card__action--button mdc-button--raised mdc-ripple-upgraded validate">
                            Request Customization
                        </button>
                    </div>
                </div>
            </div>
            {{--     Pallet Height Configuration       --}}
            <div class="mdc-card mdc-card--outlined palletHeightConfiguration" style="{{ $currentStep == \App\Livewire\Wizard::PALLET_HEIGHT_CONFIGURATION_STEP ? 'display:block' : 'display:none' }}">
                <div class="inline-text-field-container mt20 ml20">
                    <div  class="mdc-layout-grid">
                        <div class="mdc-layout-grid__inner reverseMobile">
                            @if($currentStep == \App\Livewire\Wizard::PALLET_HEIGHT_CONFIGURATION_STEP)
                                <div class="mdc-layout-grid__cell--span-6">
                                    <label class="mdc-text-field mdc-text-field--outlined mdc-text-field--label-floating w100 @error('system_pallet_height') mdc-text-field--invalid @enderror">
                                                <span class="mdc-notched-outline {{ !empty($system_pallet_height) ? 'mdc-notched-outline--upgraded mdc-notched-outline--notched' : '' }}">
                                                    <span class="mdc-notched-outline__leading"></span>
                                                    <span class="mdc-notched-outline__notch">
                                                        <span class="mdc-floating-label {{ !empty($system_pallet_height) ? 'mdc-floating-label--float-above' : '' }}" id="system_pallet_height">Max Allowable total pallet height (including pallet):*<span class="helper">(min/max:1-{{ $measurementSystemConfig['SYSTEM_PALLET_MAX_HIGHT']['value']}} {{ $measurementSystemConfig['SYSTEM_PALLET_MIN_HIGHT']['code']}})</span></span>
                                                    </span>
                                                    <span class="mdc-notched-outline__trailing"></span>
                                                </span>
                                        <input type="text" wire:model.blur="system_pallet_height"
                                               class="mdc-text-field__input"
                                               aria-labelledby="system_pallet_height" onkeydown="numberswithdotonly(event)">
                                    </label>
                                    <div>
                                        @error('system_pallet_height') <span class="error">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            @endif
                            <div class="mdc-layout-grid__cell--span-6">
                                <img id="product_with_pallet_img" src="{{ $productTypeWithPalletImage }}" class="mwWebkit w100">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mdc-card__actions my20 justify-right">
                    <div class="mdc-layout-grid__cell--span-2">
                        <div class="mdc-layout-grid--align-left">
                            <button
                                wire:click="back(2)"
                                class="mdc-button mdc-card__action mdc-card__action--button mdc-button-black--raised mdc-ripple-upgraded validate">
                                <span class="mdc-button__label-white">Back</span>
                                <div class="mdc-button__ripple"></div>
                            </button>
                            <button
                                wire:click="savePalletHeight"
                                class="mdc-button mdc-card__action mdc-card__action--button mdc-button--raised mdc-ripple-upgraded validate">
                                <span class="mdc-button__label">Next</span>
                                <div class="mdc-button__ripple"></div>
                            </button>
                        </div>
                    </div>
                    <div class="mdc-layout-grid--align-right">
                        <button type="button"
                                onclick="openDialog()"
                                class="mdc-button mdc-card__action mdc-card__action--button mdc-button--raised mdc-ripple-upgraded validate">
                            Request Customization
                        </button>
                    </div>
                </div>
            </div>
            {{--  Robot Selection  --}}
            <div class="mdc-card mdc-card--outlined robotConfiguration" style="{{ $currentStep == \App\Livewire\Wizard::ROBOT_SELECTION_STEP ? 'display:block' : 'display:none' }}">
                    <div class="mdc-layout-grid">
                        <div class="mdc-layout-grid__inner">
                            @if($currentStep == \App\Livewire\Wizard::ROBOT_SELECTION_STEP)
                                <div class="mdc-layout-grid__cell--span-4 selectContainer">
                                    <x-md-dropdown wire:model="robot_id" :options="$robots" placeholder="Select Robot"/>
                                    <div class="">
                                        <p class="notifyInfeed" style="margin-left: 24px">{!! $robot_notify !!}</p>
                                    </div>
                                </div>
                            @endif
                            <div class="mdc-layout-grid__cell--span-8 last-element">
                                <div wire:ignore id="canvas-step-4" class="cell-scene-zone">
                                </div>
                            </div>
                        </div>
                    </div>
                <div class="mdc-card__actions my20 justify-right">
                    <div class="mdc-layout-grid__cell--span-2">
                        <div class="mdc-layout-grid--align-left">
                            <button
                                wire:click="back(3)"
                                class="mdc-button mdc-card__action mdc-card__action--button mdc-button-black--raised mdc-ripple-upgraded validate">
                                <span class="mdc-button__label-white">Back</span>
                                <div class="mdc-button__ripple"></div>
                            </button>
                            <button
                                wire:click="saveSelectedRobot"
                                class="mdc-button mdc-card__action mdc-card__action--button mdc-button--raised mdc-ripple-upgraded validate">
                                <span class="mdc-button__label">Next</span>
                                <div class="mdc-button__ripple"></div>
                            </button>
                        </div>
                    </div>
                    <div class="mdc-layout-grid--align-right">
                        <button type="button"
                                onclick="openDialog()"
                                class="mdc-button mdc-card__action mdc-card__action--button mdc-button--raised mdc-ripple-upgraded validate">
                            Request Customization
                        </button>
                    </div>
                </div>
            </div>
            {{--  System Summary Range  --}}
            <div class="mdc-card mdc-card--outlined mpb100 summary" style="{{ $currentStep == \App\Livewire\Wizard::SYSTEM_SUMMARY_STEP ? 'display:block' : 'display:none' }}">
                <div class="mdc-layout-grid">
                    <div class="mdc-layout-grid__inner">
                        <div class="mdc-layout-grid__cell--span-4 summary">
                            @if($currentStep == \App\Livewire\Wizard::SYSTEM_SUMMARY_STEP)
                                <div class="inline-text-field-container mt20 ml20">
                                    <label class="mdc-text-field mdc-text-field--outlined mdc-text-field--textarea mdc-text-field--label-floating w100">
                                            <span class="mdc-notched-outline mdc-notched-outline--upgraded mdc-notched-outline--notched">
                                                <span class="mdc-notched-outline__leading"></span>
                                                <span class="mdc-notched-outline__notch">
                                                    <span class="mdc-floating-label mdc-floating-label--float-above" id="summary_label">Summary:</span>
                                                </span>
                                                <span class="mdc-notched-outline__trailing"></span>
                                            </span>
                                                @if (!empty($infeedPosition))
                                                    @php $summaryText = "Selected Product Infeed:" . PHP_EOL . $infeedPosition->name . PHP_EOL; @endphp
                                                @endif
                                                @if(!empty($leftPosition))
                                                    @php
                                                        if (!empty($summaryText)) {
                                                            $summaryText .= "Selected Left Pallet Position:" . PHP_EOL . $leftPosition->name . PHP_EOL;
                                                        }  else {
                                                            $summaryText = "Selected Left Pallet Position:" . PHP_EOL . $leftPosition->name . PHP_EOL;
                                                        }
                                                    @endphp
                                                @endif
                                                @if(!empty($rightPosition))
                                                    @php
                                                        if (!empty($summaryText)) {
                                                            $summaryText .= "Selected Right Pallet Position:" . PHP_EOL . $rightPosition->name . PHP_EOL;
                                                        } else {
                                                            $summaryText = "Selected Right Pallet Position:" . PHP_EOL . $rightPosition->name . PHP_EOL;
                                                        }
                                                    @endphp
                                                @endif
                                                @if(!empty($robotDetail))
                                                    @php
                                                        if (!empty($summaryText)) {
                                                            $summaryText .= "Selected Robot:" . PHP_EOL . $robotDetail->concatenated_description;
                                                        } else {
                                                            $summaryText = "Selected Robot:" . PHP_EOL . $robotDetail->concatenated_description;
                                                        }
                                                    @endphp
                                                @endif
                                        <textarea id="summary_content" class="mdc-text-field__input" aria-labelledby="summary_label" rows="8" cols="40" maxlength="240" disabled>{{$summaryText}}</textarea>
                                    </label>
                                </div>
                            @endif
                        </div>
                        <div class="mdc-layout-grid__cell--span-8">
                            <div wire:ignore id="canvas-step-5" class="cell-scene-zone"></div>
                            <div class="text-center mt20">
                                <div class="currentConfiguration">Current Configuration:<br/>${{ number_format($totalAmount) }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mdc-card__actions my20 justify-right">
                    <div class="mdc-layout-grid__cell--span-2">
                        <div class="mdc-layout-grid--align-left">
                            <button
                                wire:click="back(4)"
                                class="mdc-button mdc-card__action mdc-card__action--button mdc-button-black--raised mdc-ripple-upgraded validate">
                                <span class="mdc-button__label-white">Back</span>
                                <div class="mdc-button__ripple"></div>
                            </button>
                            @if(!empty($lead_configuration->status) && $lead_configuration->status == \App\Models\LeadProductConfiguration::STATUS_OVERVIEW)
                                <button wire:click="completeWizard" type="button" class="mdc-button mdc-dialog__button mdc-button--raised" data-mdc-dialog-action="discard">
                                    <div class="mdc-button__ripple"></div>
                                    <span class="mdc-button__label">Create a new configuration</span>
                                </button>
                            @else
                                <button type="button"
                                        onclick="openContactInformationDialog()"
                                        class="mdc-button mdc-card__action mdc-card__action--button mdc-button--raised mdc-ripple-upgraded validate">
                                    <span class="mdc-button__label">Request Formal Proposal</span>
                                    <div class="mdc-button__ripple"></div>
                                </button>
                            @endif
                        </div>
                    </div>
                    <div class="mdc-layout-grid--align-right">
                        <button type="button"
                                onclick="openDialog()"
                                class="mdc-button mdc-card__action mdc-card__action--button mdc-button--raised mdc-ripple-upgraded validate">
                            <span class="mdc-button__label">Request Customization</span>
                            <div class="mdc-button__ripple"></div>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="mdc-dialog" id="requestCustomizationModal">
        <div class="mdc-dialog__container">
            <div class="mdc-dialog__surface"
                 role="alertdialog"
                 aria-modal="true"
                 aria-labelledby="my-dialog-title"
                 aria-describedby="my-dialog-content">
                <h2 class="mdc-dialog__title" id="my-dialog-title"><!---->Describe the Customization request<!----></h2>
                <div class="mdc-dialog__content" id="my-dialog-content">
                    <label class="mdc-text-field mdc-text-field--outlined mdc-text-field--textarea">
                        <span class="mdc-notched-outline">
                            <span class="mdc-notched-outline__leading"></span>
                            <span class="mdc-notched-outline__notch">
                                <span class="mdc-floating-label" id="request_customization_label">Request</span>
                            </span>
                            <span class="mdc-notched-outline__trailing"></span>
                        </span>
                        <span class="mdc-text-field__resizer">
                            <textarea wire:model="request_customization" id="request_customization" class="mdc-text-field__input" aria-labelledby="request_customization_label" rows="8"
                                      cols="40" maxlength="240"></textarea>
                        </span>
                    </label>
                    <div class="mdc-text-field-helper-line">
                        <div class="mdc-text-field-character-counter">0 / 240</div>
                    </div>
                </div>
                <div class="mdc-dialog__actions">
                    <button type="button" class="mdc-button mdc-dialog__button mdc-button-black--raised" data-mdc-dialog-action="cancel">
                        <div class="mdc-button__ripple"></div>
                        <span class="mdc-button__label-white">Cancel</span>
                    </button>
                    <button wire:click="saveRequestCustomization" type="button" class="mdc-button mdc-dialog__button mdc-button--raised" data-mdc-dialog-action="discard">
                        <div class="mdc-button__ripple"></div>
                        <span class="mdc-button__label">Save changes</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="mdc-dialog__scrim"></div>
    </div>
    <div class="mdc-dialog" id="contactInformation">
        <div class="mdc-dialog__container">
            <div class="mdc-dialog__surface"
                 role="alertdialog"
                 aria-modal="true"
                 aria-labelledby="my-dialog-title"
                 aria-describedby="my-dialog-content">
                <h2 class="mdc-dialog__title" id="my-dialog-title">Contact</h2>
                <div class="mdc-dialog__content" id="my-dialog-content">
                    <div class="mdc-layout-grid">
                        <div class="mdc-layout-grid__inner">
                            <div class="mdc-layout-grid__cell--span-12">
                                <div class="mdc-layout-grid">
                                    <div class="mdc-layout-grid__inner">
                                        <div class="mdc-layout-grid__cell--span-6">
                                            <label class="mdc-text-field mdc-text-field--outlined w100">
                                            <span
                                                class="mdc-notched-outline {{ !empty($first_name) ? 'mdc-notched-outline--upgraded mdc-notched-outline--notched' : '' }} ">
                                                <span class="mdc-notched-outline__leading"></span>
                                                <span class="mdc-notched-outline__notch">
                                                    <span
                                                        class="mdc-floating-label {{ !empty($first_name) ? 'mdc-floating-label--float-above' : '' }}"
                                                        id="first_name">First Name:</span>
                                            </span>
                                            <span class="mdc-notched-outline__trailing"></span>
                                            </span>
                                                <input type="text" wire:model="first_name" class="mdc-text-field__input"
                                                       aria-labelledby="first_name" required>
                                            </label>
                                            <div>
                                                @error('first_name') <span class="error">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="mdc-layout-grid__cell--span-6">
                                            <label class="mdc-text-field mdc-text-field--outlined mdc-text-field--label-floating w100">
                                            <span
                                                class="mdc-notched-outline {{ !empty($last_name) ? 'mdc-notched-outline--upgraded mdc-notched-outline--notched' : '' }}">
                                                <span class="mdc-notched-outline__leading"></span>
                                                <span class="mdc-notched-outline__notch">
                                                    <span
                                                        class="mdc-floating-label {{ !empty($last_name) ? 'mdc-floating-label--float-above' : '' }}"
                                                        id="first_name">Last Name:</span>
                                            </span>
                                            <span class="mdc-notched-outline__trailing"></span>
                                            </span>
                                                <input type="text" wire:model="last_name" class="mdc-text-field__input"
                                                       aria-labelledby="last_name" required>
                                            </label>
                                            <div>
                                                @error('last_name') <span class="error">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mdc-layout-grid">
                                    <div class="mdc-layout-grid__inner">
                                        <div class="mdc-layout-grid__cell--span-6">
                                            <label class="mdc-text-field mdc-text-field--outlined mdc-text-field--label-floating w100">
                                            <span
                                                class="mdc-notched-outline {{ !empty($email) ? 'mdc-notched-outline--upgraded mdc-notched-outline--notched' : '' }}">
                                                <span class="mdc-notched-outline__leading"></span>
                                                <span class="mdc-notched-outline__notch">
                                                    <span
                                                        class="mdc-floating-label {{ !empty($email) ? 'mdc-floating-label--float-above' : '' }}"
                                                        id="first_name">Email:</span>
                                            </span>
                                            <span class="mdc-notched-outline__trailing"></span>
                                            </span>
                                                <input type="text" wire:model="email" class="mdc-text-field__input" aria-labelledby="email"
                                                       required>
                                            </label>
                                            <div>
                                                @error('email') <span class="error">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="mdc-layout-grid__cell--span-6">
                                            <label class="mdc-text-field mdc-text-field--outlined mdc-text-field--label-floating w100">
                                            <span
                                                class="mdc-notched-outline {{ !empty($phone) ? 'mdc-notched-outline--upgraded mdc-notched-outline--notched' : '' }}">
                                                <span class="mdc-notched-outline__leading"></span>
                                                <span class="mdc-notched-outline__notch">
                                                    <span
                                                        class="mdc-floating-label {{ !empty($phone) ? 'mdc-floating-label--float-above' : '' }}"
                                                        id="first_name">Contact Number:</span>
                                            </span>
                                            <span class="mdc-notched-outline__trailing"></span>
                                            </span>
                                                <input type="text" wire:model="phone" class="mdc-text-field__input" aria-labelledby="phone"
                                                       required>
                                            </label>
                                            <div>
                                                @error('phone') <span class="error">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mdc-layout-grid">
                                    <div class="mdc-layout-grid__inner">
                                        <div class="mdc-layout-grid__cell--span-6">
                                            <label class="mdc-text-field mdc-text-field--outlined mdc-text-field--label-floating w100">
                                            <span
                                                class="mdc-notched-outline {{ !empty($job_title) ? 'mdc-notched-outline--upgraded mdc-notched-outline--notched' : '' }}">
                                                <span class="mdc-notched-outline__leading"></span>
                                                <span class="mdc-notched-outline__notch">
                                                    <span
                                                        class="mdc-floating-label {{ !empty($job_title) ? 'mdc-floating-label--float-above' : '' }}"
                                                        id="first_name">Job title:</span>
                                            </span>
                                            <span class="mdc-notched-outline__trailing"></span>
                                            </span>
                                                <input type="text" wire:model="job_title" class="mdc-text-field__input"
                                                       aria-labelledby="job_title" required>
                                            </label>
                                            <div>
                                                @error('job_title') <span class="error">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="mdc-layout-grid__cell--span-6">
                                            <label class="mdc-text-field mdc-text-field--outlined mdc-text-field--label-floating w100">
                                            <span
                                                class="mdc-notched-outline {{ !empty($company) ? 'mdc-notched-outline--upgraded mdc-notched-outline--notched' : '' }}">
                                                <span class="mdc-notched-outline__leading"></span>
                                                <span class="mdc-notched-outline__notch">
                                                    <span
                                                        class="mdc-floating-label {{ !empty($company) ? 'mdc-floating-label--float-above' : '' }}"
                                                        id="first_name">Company:</span>
                                            </span>
                                            <span class="mdc-notched-outline__trailing"></span>
                                            </span>
                                                <input type="text" wire:model="company" class="mdc-text-field__input"
                                                       aria-labelledby="company"
                                                       required>
                                            </label>
                                            <div>
                                                @error('company') <span class="error">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mdc-layout-grid">
                                    <div class="mdc-layout-grid__inner">
                                        <div class="mdc-layout-grid__cell--span-6">
                                            <label class="mdc-text-field mdc-text-field--outlined mdc-text-field--label-floating w100">
                                            <span
                                                class="mdc-notched-outline  {{ !empty($local_distributor) ? 'mdc-notched-outline--upgraded mdc-notched-outline--notched' : '' }}">
                                                <span class="mdc-notched-outline__leading"></span>
                                                <span class="mdc-notched-outline__notch">
                                                    <span
                                                        class="mdc-floating-label  {{ !empty($local_distributor) ? 'mdc-floating-label--float-above' : '' }}"
                                                        id="first_name">Local distributor:</span>
                                            </span>
                                            <span class="mdc-notched-outline__trailing"></span>
                                            </span>
                                                <input type="text" wire:model="local_distributor" class="mdc-text-field__input"
                                                       aria-labelledby="local_distributor"
                                                       required>
                                            </label>
                                            <div>
                                                @error('local_distributor') <span class="error">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="mdc-layout-grid__cell--span-6"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mdc-dialog__actions">
                    <button type="button" class="mdc-button mdc-dialog__button mdc-button-black--raised" data-mdc-dialog-action="cancel">
                        <div class="mdc-button__ripple"></div>
                        <span class="mdc-button__label-white">Cancel</span>
                    </button>
                    <button wire:click="createLead" type="button" class="mdc-button mdc-dialog__button mdc-button--raised" data-mdc-dialog-action="discard">
                        <div class="mdc-button__ripple"></div>
                        <span class="mdc-button__label">Submit</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="mdc-dialog__scrim"></div>
    </div>
    <div class="mdc-dialog" id="requestFormalProposalModal">
        <div class="mdc-dialog__container">
            <div class="mdc-dialog__surface"
                 role="alertdialog"
                 aria-modal="true"
                 aria-labelledby="my-dialog-title"
                 aria-describedby="my-dialog2-content">
                <div class="mdc-dialog__content" id="my-dialog2-content">
                    Thank you. Our technical experts will be in touch with you soon.
                </div>
                <div class="mdc-dialog__actions">
                    <button type="button" class="mdc-button mdc-dialog__button mdc-button-black--raised" data-mdc-dialog-action="cancel">
                        <div class="mdc-button__ripple"></div>
                        <span class="mdc-button__label-white">Close</span>
                    </button>
                    <button wire:click="completeWizard" type="button" class="mdc-button mdc-dialog__button mdc-button--raised" data-mdc-dialog-action="discard">
                        <div class="mdc-button__ripple"></div>
                        <span class="mdc-button__label">Create a new configuration</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="mdc-dialog__scrim"></div>
    </div>
    <div id="cameraIconContainer"></div>
    <div class="mdc-dialog" id="resettingAlertModal">
        <div class="mdc-dialog__container">
            <div class="mdc-dialog__surface"
                 role="alertdialog"
                 aria-modal="true"
                 aria-labelledby="my-dialog-title"
                 aria-describedby="my-dialog3-content">
                <div class="mdc-dialog__content" id="my-dialog3-content">
                    Applying changes to this step will result in resetting the progress of the following steps.
                </div>
                <div class="mdc-dialog__actions">
                    <button type="button" class="mdc-button mdc-dialog__button mdc-button-black--raised" data-mdc-dialog-action="cancel">
                        <div class="mdc-button__ripple"></div>
                        <span class="mdc-button__label-white">Close</span>
                    </button>
                    <button wire:click="acceptResettingAlertModal" type="button" class="mdc-button mdc-dialog__button mdc-button--raised" data-mdc-dialog-action="discard">
                        <div class="mdc-button__ripple"></div>
                        <span class="mdc-button__label">Next</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="mdc-dialog__scrim"></div>
    </div>
    <div class="mdc-dialog" id="changeMeasurementSystemModal">
        <div class="mdc-dialog__container">
            <div class="mdc-dialog__surface"
                 role="alertdialog"
                 aria-modal="true"
                 aria-labelledby="my-dialog-title"
                 aria-describedby="my-dialog4-content">
                <div class="mdc-dialog__content" id="my-dialog4-content">
                    <div>Select your measurement system:</div>
                    <div wire:ignore class="text-center">
                        <label for="measurable-switch">Metric</label>
                        <button wire:click="changeMeasurementSystem" id="measurable-switch" class="mdc-switch @if($measurementSystem === "imperial") mdc-switch--selected @else mdc-switch--unselected @endif" type="button" role="switch" aria-checked="false">
                            <div class="mdc-switch__track"></div>
                            <div class="mdc-switch__handle-track">
                                <div class="mdc-switch__handle">
                                    <div class="mdc-switch__ripple"></div>
                                </div>
                            </div>
                        </button>
                        <label for="measurable-switch">Imperial</label>
                    </div>
                </div>
                <div class="mdc-dialog__actions">
                    <button type="button" class="mdc-button mdc-dialog__button mdc-button-black--raised" data-mdc-dialog-action="cancel">
                        <div class="mdc-button__ripple"></div>
                        <span class="mdc-button__label-white">Close</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="mdc-dialog__scrim"></div>
    </div>
</div>
@include('components.babylon-scripts')
@script
<script>
    document.addEventListener("livewire:initialized", function (event) {

        var measurableSwitch = window.initSwitch(document.getElementById('measurable-switch'));
        window.initSlider(document.getElementById('product_infeed_rate'), function (event) {
            Livewire.first().set('product_infeed_rate', event.detail.value);
        })

        Livewire.on('change-step', function (data) {
            setTimeout(function () {
                moveScene("canvas-step-" + data.step)
                window.initSlider(document.getElementById('product_infeed_rate'), function (event) {
                    Livewire.first().set('product_infeed_rate', event.detail.value);
                })
                var textFields = document.querySelectorAll('.mdc-text-field');
                textFields.forEach(function(element){
                    var textField = new window.MDCTextField(element);
                });
                document.querySelector('.mdc-tab--active').scrollIntoView({
                    behavior: "smooth",
                    block: 'center',
                    inline: 'center'
                });
            }, 100)
        });

        Livewire.on('refresh3DScene', function (data) {
            const module = JSON.parse(data.model);
            const meshKey = data.meshKey;
            disposeMesh(meshKey);
            displayLoader()
            var path = module.path

            ImportMesh(path, 1)
                .then(mesh => {
                    if (loadedMeshes[meshKey]) {
                        disposeMesh(meshKey);
                    }
                    loadedMeshes[meshKey] = mesh;
                    closeLoader()
                })
                .catch(error => {
                    console.error('Error loading scene:', error);
                    closeLoader()
                });
        });

        Livewire.on('loadDefault', function () {
            disposeMesh('gripper');
            disposeMesh('product_infeed_id');
            disposeMesh('left_pallet_position_id');
            disposeMesh('right_pallet_position_id');
            disposeMesh('robot_id');
            importDefaultModels();
        });

        Livewire.on('refreshRobotScene', function (data) {
            disposeMesh('robot_id');
            const robotModel = JSON.parse(data.robotModel);
            const gripperModel = JSON.parse(data.gripperModel);
            importRobot(robotModel, gripperModel);
        });

        Livewire.on('wizardUpdated', function(data){
            setTimeout(function(){
                document.body.classList = [];
                var measurableSwitch = window.initSwitch(document.getElementById('measurable-switch'));
                var textFields = document.querySelectorAll('.mdc-text-field');
                textFields.forEach(function(element){
                    var textField = new window.MDCTextField(element);
                });
            },50);
        });

        moveScene("canvas-step-" + @json($currentStep))

        function moveScene(targetDivId) {
            const targetDiv = document.getElementById(targetDivId);
            const canvas = document.getElementById('renderCanvas');
            const loaderElement = document.getElementById('loader');
            const overlayElement = document.getElementById('scene-overlay');
            const cameraIcon = document.getElementById('cameraIcon');
            if (targetDiv) {
                scene.paused = false;
                targetDiv.appendChild(canvas);
                targetDiv.appendChild(loaderElement);
                targetDiv.appendChild(overlayElement);
                targetDiv.appendChild(cameraIcon);
                setTimeout(function () {
                    engine.resize();
                }, 100);
            } else {
                scene.paused = true;
            }
        }

        window.openDialog = function() {
            let dialog1 = window.initDialog(document.getElementById('requestCustomizationModal'));
            dialog1.open();
            var textFields = document.querySelectorAll('.mdc-text-field--textarea');
            textFields.forEach(function(element){
                var textField = new window.MDCTextField(element);
            });
        }

        window.openFormalProposalDialog = function() {
            Livewire.dispatch('systemSummarySubmit')
        }

        window.openContactInformationDialog = function() {
            window.initDialog(document.getElementById('contactInformation')).open();
        }

        Livewire.on('systemSummarySubmitComplete', function () {
            window.initDialog(document.getElementById('requestFormalProposalModal')).open();
        })

        Livewire.on('resettingProgressAlert', function () {
            window.initDialog(document.getElementById('resettingAlertModal')).open();
        });

        window.numbersonly = function(e) {
            if /*Delete,backspace, Esc, Tab, Arrows, whatsoever*/ (e.keyCode >= 46) {
                if(isNaN(e.key)) e.preventDefault();
            }
        }

        window.numberswithdotonly = function(e) {
            if /*Delete,backspace, Esc, Tab, Arrows, whatsoever*/ (e.keyCode >= 46) {
                if(isNaN(e.key) && e.key !== '.') e.preventDefault();
            }
        }

        window.openChangeMeasurementSystemDialog = function() {
            let dialog4 = window.initDialog(document.getElementById('changeMeasurementSystemModal'));
            dialog4.open();
        }

    });
</script>
@endscript
