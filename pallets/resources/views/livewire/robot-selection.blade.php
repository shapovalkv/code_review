<div class="step-content" style="overflow-y: hidden;">
    <div class="row">
        <label for="left_pallet_position_id">Select Robot: <span class="red-text">*</span></label>
        <select class="browser-default" wire:model="robots" id="robot_id" name="robot_id" >
            <option value="" disabled selected>Select Left Pallet Position</option>
            @foreach($robots as $robot)
                <option value="{{ $robot->id }}">{{ $robot->concatenated_description }}</option>
            @endforeach
        </select>
    </div>
{{--    <div class="row">--}}
{{--        <div class="select-dropdown col m6 s12" style="padding-top: 15px">--}}
{{--            <label for="robot_product_to_represent">Product Type: <span class="red-text">*</span></label>--}}
{{--            <select id="robot_product_to_represent" name="robot_product_to_represent">--}}
{{--                <option value="" disabled selected>Product To Represent</option>--}}
{{--                --}}{{--                                            @foreach(\App\Models\LeadProductConfiguration::PRODUCT_TYPE as $type)--}}
{{--                --}}{{--                                                <option value="{{ $type }}">{{ \Illuminate\Support\Str::ucfirst($type) }}</option>--}}
{{--                --}}{{--                                            @endforeach--}}
{{--            </select>--}}
{{--        </div>--}}
{{--    </div>--}}
    <div class="step-actions">
        <div class="row">
            <div class="col m4 s12 mb-3">
                <button class="red btn btn-reset" type="reset">
                    <i class="material-icons left">clear</i>
                    Reset
                </button>
            </div>
            <div class="col m4 s12 mb-3">
                <button class="btn btn-light previous-step">
                    <i class="material-icons left">arrow_back</i>
                    Prev
                </button>
            </div>
            <div class="col m4 s12 mb-3">
                <button class="waves-effect waves dark btn btn-primary next-step" data-feedback="checkPalletHeightConfigurationStep">
                    Next
                    <i class="material-icons right">arrow_forward</i>
                </button>
            </div>
        </div>
    </div>
</div>
