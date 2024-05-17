<div>
    <style>
        .theme-wizard .nav-link.active{
            color: #2c7be5 !important;
        }
    </style>
    <div class="card theme-wizard h-100 mb-5">
        <div class="card-header bg-light pt-3 pb-2">
            <ul class="nav justify-content-between nav-wizard" role="tablist">
                <li class="nav-item">
                    <a class="nav-link fw-semi-bold {{ $currentStep == 1 ? 'active' : '' }}{{ $currentStep > 1 ? 'done' : '' }}" disabled
                       data-bs-toggle="tab" data-wizard-step="data-wizard-step"><span
                            class="nav-item-circle-parent"><span class="nav-item-circle"><span
                                    class="fas fa-lock"></span></span></span><span class="d-none d-md-block mt-1 fs--1">Project Name</span>
                    </a>
                </li>
                {{--             Whitelisted keywords--}}
                <li class="nav-item">
                    <a class="nav-link fw-semi-bold {{ $currentStep == 2 ? 'active' : '' }}{{ $currentStep > 2 ? 'done' : '' }}" disabled
                       data-bs-toggle="tab" data-wizard-step="data-wizard-step"><span
                            class="nav-item-circle-parent"><span class="nav-item-circle"><span
                                    class="fas fa-user"></span></span></span><span class="d-none d-md-block mt-1 fs--1">Whitelisted keywords</span>
                    </a>
                </li>
                {{--             Whitelisted accounts--}}
                <li class="nav-item">
                    <a class="nav-link fw-semi-bold {{ $currentStep == 3 ? 'active' : '' }}{{ $currentStep > 3 ? 'done' : '' }}" disabled
                       data-bs-toggle="tab" data-wizard-step="data-wizard-step"><span
                            class="nav-item-circle-parent"><span class="nav-item-circle"><span
                                    class="fas fa-user"></span></span></span><span class="d-none d-md-block mt-1 fs--1">Whitelisted accounts</span>
                    </a>
                </li>
                {{--             Legal documents--}}
                <li class="nav-item">
                    <a class="nav-link fw-semi-bold {{ $currentStep == 4 ? 'active' : '' }}{{ $currentStep > 4 ? 'done' : '' }}" disabled
                       data-bs-toggle="tab" data-wizard-step="data-wizard-step"><span
                            class="nav-item-circle-parent"><span class="nav-item-circle"><span
                                    class="fas fa-user"></span></span></span><span class="d-none d-md-block mt-1 fs--1">Legal documents</span>
                    </a>
                </li>
                {{--             Select plan--}}
                <li class="nav-item">
                    <a class="nav-link fw-semi-bold {{ $currentStep == 5 ? 'active' : '' }}{{ $currentStep > 5 ? 'done' : '' }}" disabled
                       data-bs-toggle="tab" data-wizard-step="data-wizard-step"><span
                            class="nav-item-circle-parent"><span class="nav-item-circle"><span
                                    class="fas fa-dollar-sign"></span></span></span><span
                            class="d-none d-md-block mt-1 fs--1">Plan details</span>
                    </a>
                </li>
                {{--             Checkout--}}
                <li class="nav-item">
                    <a class="nav-link fw-semi-bold {{ $currentStep == 6 ? 'active' : '' }}{{ $currentStep > 6 ? 'done' : '' }}" disabled
                       data-bs-toggle="tab" data-wizard-step="data-wizard-step"><span
                            class="nav-item-circle-parent"><span class="nav-item-circle"><span
                                    class="fas fa-dollar-sign"></span></span></span><span
                            class="d-none d-md-block mt-1 fs--1">Billing</span>
                    </a>
                </li>
                {{--             Done--}}
                <li class="nav-item">
                    <a class="nav-link fw-semi-bold {{ $currentStep == 7 ? 'active' : '' }}{{ $currentStep > 7 ? 'done' : '' }}" disabled
                       data-bs-toggle="tab" data-wizard-step="data-wizard-step"><span
                            class="nav-item-circle-parent"><span class="nav-item-circle"><span
                                    class="fas fa-thumbs-up"></span></span></span><span
                            class="d-none d-md-block mt-1 fs--1">Done</span>
                    </a>
                </li>
            </ul>
            {{--         Create Project step--}}
            <div class="card-body py-4" style="{{ $currentStep != 1 ? 'display: none' : '' }}" id="wizard-controller">
                <div class="tab-content">
                    <div class="tab-pane active px-sm-3 px-md-5 {{ $currentStep != 1 ? 'active show' : '' }}"
                         role="tabpanel" aria-labelledby="bootstrap-wizard-validation-tab1"
                         id="bootstrap-wizard-validation-tab1">
                        <div class="needs-validation" novalidate="novalidate">
                            <div class="mb-3">
                                <label class="form-label" for="bootstrap-wizard-validation-wizard-name">Project Name</label>
                                <div class="col-lg-12 d-flex pb-2">
                                    <small>
                                        Please indicate the name of the copyright you would like us to monitor. For example, this could be your full name, any usernames or aliases, or the titles of any of your protected works.
                                    </small>
                                </div>
                                <input class="form-control" type="text" wire:model="name" value="{{ old('name', $name) }}"
                                       placeholder="Project Name"
                                       id="bootstrap-wizard-validation-wizard-name"/>
                                @error('name') <span class="error" style="color: red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="px-sm-3 px-md-5">
                        <ul class="pager wizard list-inline mb-0">
                            <li class="next">
                                <button wire:click="createProject" type="button" class="btn btn-dark px-5 px-sm-6">
                                    Next<span
                                        class="fas fa-chevron-right ms-2" data-fa-transform="shrink-3"> </span></button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            {{--         Insert whitelisted keywords--}}
            <div class="card-body py-4" style="{{ $currentStep != 2 ? 'display: none' : '' }}" id="wizard-controller">
                <div class="tab-content">
                    <div class="tab-pane px-sm-3 px-md-5 active show" role="tabpanel"
                         aria-labelledby="bootstrap-wizard-validation-tab2">
                        <div class="card-body">
                            <label class="form-label" for="keywords-name">Insert whitelisted keywords here</label>
                            <div class="col-lg-5 d-flex pb-2">
                                <small>
                                    Please list any keywords that you would like us to whitelist (aka NOT track for takedown reporting).
                                </small>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 d-flex justify-content-end">
                                    <div class="input-group">
                                        <input class="form-control" wire:model="whitelistedKeyword" id="keywords-name"
                                               type="text"/>
                                        <button wire:click="createSingleWhitelistedKeyword" class="btn btn-dark ms-2"
                                                type="button">Add
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <label class="form-label" for="first-name">Or import your file here</label>
                            <div class="col-12 d-flex justify-content-start">
                                <input type="file" wire:model="whitelistedKeywordsFile" class="form-control">
                                <button wire:click="importWhitelistedKeywords" class="btn btn-dark ms-2" type="button">
                                    Import
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @error('whitelistedKeywords') <span class="error" style="color: red">{{ $message }}</span> @enderror
                        </div>
                        <div class="table-responsive scrollbar">
                            <table class="table">
                                <thead>
                                <tr>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!empty($userProject->id))
                                    @foreach($whitelistedKeywords as $whitelistedKeyword)
                                        <tr>
                                            <td>{{ $whitelistedKeyword->content }}</td>
                                            <td class="text-end">
                                                <div>
                                                    <a href="#"
                                                       wire:click="deleteSingleWhitelistedKeywords({{ $whitelistedKeyword->id }})"
                                                       wire:loading.attr="disabled"
                                                       class="btn btn-link p-0 ms-2" type="button"
                                                       data-bs-toggle="tooltip" data-bs-placement="top"
                                                       title="Delete">
                                                        <span class="text-500 fas fa-trash-alt"></span>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="px-sm-3 px-md-5">
                        <ul class="pager wizard list-inline mb-0">
                            <li class="previous">
                                <button wire:click="back(1)" class="btn btn-link ps-0" type="button"><span
                                        class="fas fa-chevron-left me-2"
                                        data-fa-transform="shrink-3"></span>Prev
                                </button>
                            </li>
                            <li class="next">
                                <button wire:click="validateKeywordsStep()" class="btn btn-dark px-5 px-sm-6">Next<span
                                        class="fas fa-chevron-right ms-2" data-fa-transform="shrink-3"> </span></button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            {{--         Insert whitelisted accounts--}}
            <div class="card-body py-4" style="{{ $currentStep != 3 ? 'display: none' : '' }}" id="wizard-controller">
                <div class="tab-content">
                    <div class="tab-pane px-sm-3 px-md-5 active show" role="tabpanel"
                         aria-labelledby="bootstrap-wizard-validation-tab3" id="bootstrap-wizard-validation-tab3">
                        <div class="card-body">
                            <label class="form-label" for="keywords-name">Insert whitelisted accounts</label>
                            <div class="col-lg-5 d-flex pb-2">
                                <small>
                                    Please list any accounts that you would like us to whitelist  (aka NOT track for takedown reporting). This can include your social media accounts (ex: instagram.com/username) or your official website (ex: nameofficial.com)
                                </small>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 d-flex justify-content-end">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon3">www.example.com</span>
                                        </div>
                                        <input type="text" wire:model="whitelistedAccount" class="form-control"
                                               id="basic-url"
                                               aria-describedby="basic-addon3">
                                        <button wire:click="createSingleWhitelistedAccount" type="button"
                                                class="btn btn-dark ms-2">
                                            Add
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <label class="form-label" for="first-name">Or import your file here</label>
                            <div class="col-12 d-flex justify-content-start">
                                <input type="file" wire:model="whitelistedAccountsFile" class="form-control">
                                <button wire:click="importWhitelistedAccounts" class="btn btn-dark ms-2" type="button">
                                    Import
                                </button>
                            </div>
                        </div>

                        <div class="card-body">
                            @error('whitelistedAccounts') <span class="error" style="color: red">{{ $message }}</span> @enderror
                        </div>

                        <div class="table-responsive scrollbar">
                            <table class="table">
                                <thead>
                                <tr>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!empty($userProject->id))
                                    @foreach($whitelistedAccounts as $whitelistedAccount)
                                        <tr>
                                            <td>{{ $whitelistedAccount->content }}</td>
                                            <td class="text-end">
                                                <div>
                                                    <a href="#"
                                                       wire:click="deleteSingleWhitelistedAccounts({{ $whitelistedAccount->id }})"
                                                       wire:loading.attr="disabled"
                                                       class="btn btn-link p-0 ms-2" type="button"
                                                       data-bs-toggle="tooltip" data-bs-placement="top"
                                                       title="Delete">
                                                        <span class="text-500 fas fa-trash-alt"></span>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="px-sm-3 px-md-5">
                        <ul class="pager wizard list-inline mb-0">
                            <li class="previous">
                                <button wire:click="back(2)" class="btn btn-link ps-0" type="button"><span
                                        class="fas fa-chevron-left me-2"
                                        data-fa-transform="shrink-3"></span>Prev
                                </button>
                            </li>
                            <li class="next">
                                <button wire:click="validateAccountsStep()" class="btn btn-dark px-5 px-sm-6">Next<span
                                        class="fas fa-chevron-right ms-2" data-fa-transform="shrink-3"></span></button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            {{--         Legal documents--}}
            <div class="card-body py-4" style="{{ $currentStep != 4 ? 'display: none' : '' }}" id="wizard-controller">
                <div class="tab-content">
                    <div class="col-lg-12 d-flex justify-content-center align-items-center pb-2">
                        <small class="content-center">
                            If your copyright is registered, please upload documentation here.
                        </small>
                    </div>
                    <div class="tab-pane px-sm-3 px-md-5 active show" role="tabpanel"
                         aria-labelledby="bootstrap-wizard-validation-tab3" id="bootstrap-wizard-validation-tab4">
                        <div id="wizard-dropzone" class="dropzone dropzone-single p-0" x-data="drop_file_component()"
                             style="border:0; background: transparent">
                            <div
                                class="w-96 rounded border-dashed border-2 flex flex-col justify-center items-center"
                                x-bind:class="dropingFile ? 'bg-gray-400 border-gray-500' : 'border-gray-500 bg-gray-200'"
                                x-on:drop="dropingFile = false"
                                x-on:dragover.prevent="dropingFile = true"
                                x-on:dragleave.prevent="dropingFile = false">
                                <div>
                                    <div class="dz-message" data-dz-message="data-dz-message">
                                        <div class="dz-message-text content-center">
                                            Drop your file here Or
                                            <input class="hidden document-upload" type="file" id="file-upload" multiple/>

                                            <div
                                                x-data="{ isUploading: false, progress: 0 }"
                                                x-on:livewire-upload-start="isUploading = true"
                                                x-on:livewire-upload-finish="isUploading = false"
                                                x-on:livewire-upload-error="isUploading = false"
                                                x-on:livewire-upload-progress="progress = $event.detail.progress"
                                            >
                                                <div x-show="isUploading">
                                                    <progress max="100" x-bind:value="progress"></progress>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-1" wire:loading.flex wire.target="files">
                                        <img class="me-2" src="{{ asset('/assets/img/icons/cloud-upload.svg') }}" width="25" alt=""/>
                                        <div>Processing Files</div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                @error('files')
                                <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                                <span id="fileSizeError" class="error" style="color: red"></span>
                            </div>

                            @if(!empty($projectFiles))
                                <div class="table-responsive scrollbar">
                                    <table class="table">
                                        <tbody>
                                        @foreach($projectFiles as $projectFile)
                                            <tr>
                                                <td>{{ $projectFile->name }}</td>
                                                <td class="text-end">
                                                    <div>
                                                        <button class="btn btn-link p-0 ms-2"
                                                                wire:click="deleteFile({{ $projectFile->id }})"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="Delete"><span
                                                                class="text-500 fas fa-trash-alt"></span></button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="px-sm-3 px-md-5">
                            <ul class="pager wizard list-inline mb-0">
                                <li class="previous">
                                    <button wire:click="back(3)" class="btn btn-link ps-0" type="button"><span
                                            class="fas fa-chevron-left me-2"
                                            data-fa-transform="shrink-3"></span>Prev
                                    </button>
                                </li>
                                <li class="next">
                                    <button wire:click="next(5)" class="btn btn-dark px-5 px-sm-6">Next<span
                                            class="fas fa-chevron-right ms-2" data-fa-transform="shrink-3"></span></button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
                <div class="card-body py-4" style="{{ $currentStep != 5 ? 'display: none' : '' }}" id="wizard-controller">
                    <div class="tab-content">
                        <div class="tab-pane px-sm-3 px-md-5 active" role="tabpanel"
                             aria-labelledby="bootstrap-wizard-validation-tab4" id="bootstrap-wizard-validation-tab5">
                            <div class="row text-center align-items-start">
                                @foreach($plans as $plan)
                                    <div class="col-lg-4 mb-5 mb-lg-0 p-2">
                                        <div class="bg-white p-5 rounded-lg shadow" style="min-height: 600px">
                                            <h1 class="h6 text-uppercase font-weight-bold mb-4">{{ $plan->name }}</h1>
                                            <h2 class="h1 font-weight-bold">${{ $plan->price }}<span
                                                    class="text-small font-weight-normal white-space-nowrap ml-2">/month</span></h2>

                                            <div class="custom-separator my-4 mx-auto bg-primary"></div>

                                            <ul class="list-unstyled my-5 text-small text-left font-weight-normal">
                                                @foreach(json_decode($plan->content) as $content)
                                                    <li class="mb-3">
                                                        <i class="fa fa-check mr-2 text-primary"></i> {{ $content }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                            <a wire:click="selectPlan({{$plan->id}})"
                                               class="btn btn-dark btn-block shadow rounded-pill align-self-end">Select
                                                Plan</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="px-sm-3 px-md-5">
                            <ul class="pager wizard list-inline mb-0">
                                <li class="previous">
                                    <button wire:click="back(4)" class="btn btn-link ps-0" type="button"><span
                                            class="fas fa-chevron-left me-2"
                                            data-fa-transform="shrink-3"></span>Prev
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body py-4" style="{{ $currentStep != 6 ? 'display: none' : '' }}" id="wizard-controller">
                    <form wire:submit.prevent="checkout" id="payment-form">
                        <div class="tab-content">
                            <div class="tab-pane px-sm-3 px-md-5 active" role="tabpanel"
                                 aria-labelledby="bootstrap-wizard-tab3"
                                 id="bootstrap-wizard-tab3">
                                @csrf
                                <div class="card-body ">
                                    <div class="form-check mb-0">
                                        <label class="form-check-label d-flex align-items-center mb-0"
                                               for="credit-card"><span class="fs-1 text-nowrap">Credit Card</span><img
                                                class="d-none d-sm-inline-block ms-2 mt-lg-0"
                                                src="{{ asset('assets/img/icons/icon-payment-methods.png') }}" height="20"
                                                alt=""/>
                                        </label>
                                    </div>
                                    <p class="fs--1 mb-4">Safe money transfer using your bank accounts.</p>
                                    <div>
                                        <div class="row gx-3 mb-3">
                                            <div wire:ignore class="col">
                                                <label
                                                    class="form-label ls text-uppercase text-600 fw-semi-bold mb-0 fs--1"
                                                    for="card-number">Card Number</label>
                                                <div class="form-control" id="card-number"></div>
                                            </div>
                                            <div class="col">
                                                <label
                                                    class="form-label ls text-uppercase text-600 fw-semi-bold mb-0 fs--1"
                                                    for="cardName">Name of Card</label>
                                                <input class="form-control" wire:model="cardName" id="cardName"
                                                       placeholder="John Doe"
                                                     type="text" style="color: #006fff "/>
                                                <input type="hidden" name="token" wire:model="paymentMethod"
                                                       id="paymentTokenInput">
                                            </div>
                                        </div>
                                        <div wire:ignore class="row gx-3">
                                            <div class="col-6 col-sm-3">
                                                <label
                                                    class="form-label ls text-uppercase text-600 fw-semi-bold mb-0 fs--1"
                                                    for="card-expiry">Exp Date</label>
                                                <div class="form-control" id="card-expiry"></div>
                                            </div>
                                            <div class="col-6 col-sm-3">
                                                <label
                                                    class="form-label ls text-uppercase text-600 fw-semi-bold mb-0 fs--1"
                                                    for="card-cvc">CVV<span class="ms-1" data-bs-toggle="tooltip"
                                                                       data-bs-placement="top"
                                                                       title="Card verification value"><span
                                                            class="fa fa-question-circle"></span></span></label>
                                                <div class="form-control" id="card-cvc"></div>
                                            </div>
                                            <span class="error p-3" style="color: red">
                                                <div id="error-message" role="alert"></div>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="px-sm-3 px-md-5">
                                <ul class="pager wizard list-inline mb-0">
                                    <li class="previous">
                                        <button wire:click="back(5)" class="btn btn-link ps-0" type="button"><span
                                                class="fas fa-chevron-left me-2"
                                                data-fa-transform="shrink-3"></span>Prev
                                        </button>
                                    </li>
                                    <li class="next">
                                        <button id="card-button" type="submit" wire:target="checkout"
                                                data-secret="{{ $intent ?? '' }}"
                                                class="btn btn-dark px-5 px-sm-6">Submit<span
                                                class="fas fa-chevron-right ms-2" data-fa-transform="shrink-3"> </span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </form>

                </div>
                <div class="card-body py-4" style="{{ $currentStep != 7 ? 'display: none' : '' }}" id="wizard-controller">
                    <div class="tab-content">
                        <div class="tab-pane text-center px-sm-3 px-md-5 active"
                             role="tabpanel" aria-labelledby="bootstrap-wizard-validation-tab5"
                             id="bootstrap-wizard-validation-tab6">
                            <h4 class="mb-1">Your account is all set!</h4>
                            <p>Now you can access to your account</p>
                            <a class="btn btn-dark px-5 my-3" href="{{ route('user.dashboard') }}">Go to dashboard</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://js.stripe.com/v3/"></script>
        <script type="text/javascript">
            const stripe = Stripe('{{ env('STRIPE_KEY') }}')

            const elements = stripe.elements()

            const cardNumberElement = elements.create('cardNumber');
            cardNumberElement.update({ style: { base: { color: '#006fff' } } });
            cardNumberElement.mount('#card-number');

            const cardExpiryElement = elements.create('cardExpiry');
            cardExpiryElement.update({ style: { base: { color: '#006fff' } } });
            cardExpiryElement.mount('#card-expiry');

            const cardCvcElement = elements.create('cardCvc');
            cardCvcElement.update({ style: { base: { color: '#006fff' } } });
            cardCvcElement.mount('#card-cvc');

            const form = document.getElementById('payment-form')
            const cardBtn = document.getElementById('card-button')
            const clientSecret = cardBtn.dataset.secret;
            const cardHolderName = document.getElementById('cardName')
            const cardNumber = document.getElementById('card-number');
            const cardExpiry = document.getElementById('card-expiry');
            const cardCvc = document.getElementById('card-cvc');
            const paymentTokenInput = document.getElementById('paymentTokenInput');
            const errorMessage = document.getElementById('error-message');

            document.getElementById('file-upload').addEventListener('change', function (event) {
                handleFileSelect(event)
            })

            document.getElementById('wizard-dropzone').addEventListener('drop', function (event) {
                event.preventDefault();
                handleFileDrop(event)
            })

            function handleFileSelect(event) {
                if (event.target.files.length) {
                    uploadMultiple(event.target.files)
                }
            }

            function handleFileDrop(event) {
                if (event.dataTransfer.files.length > 0) {
                    uploadMultiple(event.dataTransfer.files)
                }
            }

            function uploadMultiple(files) {
                const $this = this;
                const maxFileSize = 2 * 1024 * 1024; // 2 МБ в байтах

                this.isUploading = true;

                let hasOversizedFiles = false;

                for (let i = 0; i < files.length; i++) {
                    const file = files[i];

                    if (file.size > maxFileSize) {
                        hasOversizedFiles = true;
                    }
                }

                if (hasOversizedFiles) {
                    const errorMessage = "One or more files are too large and cannot be uploaded.";
                    document.getElementById('fileSizeError').textContent = errorMessage;
                    $this.isUploading = false; // Останавливаем загрузку
                } else {
                @this.uploadMultiple(
                    'files',
                    files,
                    function (success) {
                        $this.isUploading = false;
                        $this.progress = 0;
                    },
                    function (error) {
                        console.log('error', error);
                    },
                    function (event) {
                        $this.progress = event.detail.progress;
                    }
                );
                }
            }

            cardBtn.addEventListener('change', function (event) {
                const displayError = document.getElementById('card-errors');
                if (event.error) {
                    displayError.textContent = event.error.message
                } else {
                    displayError.textContent = '';
                }
            })

            cardBtn.addEventListener('click', async (e) => {

                cardBtn.disabled = true
                const {setupIntent, error} = await stripe.confirmCardSetup(
                    cardBtn.dataset.secret, {
                        payment_method: {
                            card: cardNumberElement,
                            billing_details: {
                                name: cardHolderName.value
                            }
                        }
                    }
                )

                if (error) {
                    errorMessage.textContent = error.message;
                } else {
                    @this.set('paymentMethod', setupIntent.payment_method)
                    @this.call('checkout')
                }
            })

            function drop_file_component() {
                return {
                    dropingFile: false,
                    handleFileDrop(e) {
                        if (event.dataTransfer.files.length > 0) {
                            const files = e.dataTransfer.files;
                        @this.uploadMultiple('files', files,
                            (uploadedFilename) => {
                            }, () => {
                            }, (event) => {
                            }
                        )
                        }
                    }
                };
            }

            Livewire.on('refreshComponent', function () {
                Livewire.emit('refresh');
            });

            document.addEventListener("DOMContentLoaded", function() {
                var lis = document.querySelectorAll(".list-unstyled li");
                var maxHeight = 0;

                lis.forEach(function(li) {
                    var liHeight = li.clientHeight;
                    maxHeight = Math.max(maxHeight, liHeight);
                });

                lis.forEach(function(li) {
                    li.style.height = maxHeight + "px";
                });
            });
        </script>
@endpush
