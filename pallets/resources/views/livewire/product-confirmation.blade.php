<div class="step-content" style="overflow-y: hidden;">
    <div class="row">
        <div class="input-field col m6 s12" style="padding-top: 8px">
            <label for="product_name">Product Name: <span class="red-text">*</span></label>
            <input type="text" class="validate" id="product_name" name="product_name" required="">
        </div>
        <div class="select-dropdown col m6 s12" >
            <label for="product_type_id">Product Type: <span class="red-text">*</span></label>
            <select id="product_type_id" name="product_type_id">
                <option value="" disabled selected>Product Type</option>
                @foreach($product_types as $type)
                    <option value="{{ $type->id }}">{{ \Illuminate\Support\Str::ucfirst($type->name) }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <div class="input-field col m6 s12">
            <label for="product_length">Product Length: (min/max: 100-600mm)</label>
            <input type="text" id="product_length" name="product_length" class="validate" required="">
        </div>
        <div class="input-field col m6 s12">
            <label for="product_width">Product Width: (min/max: 100-400mm)</label>
            <input type="text" id="product_width" name="product_width" class="validate" required="">
        </div>
    </div>
    <div class="row">
        <div class="input-field col m6 s12">
            <label for="product_height">Product Height: (min/max: 0.1-400mm)</label>
            <input type="text" id="product_height" name="product_height" class="validate" required="">
        </div>
        <div class="input-field col m6 s12">
            <label for="product_weight">Product Weight: (min/max: 1-100kg)</label>
            @if ($errors->has('product_weight'))
                <span class="error">{{ $errors->first('product_weight') }}</span>
            @endif
            <input {{--wire:model="product_weight" wire:change="weightValidate" --}}type="text" id="product_weight" name="product_weight" class="validate" required="">
        </div>
    </div>
    <div class="row">
        <div class="range-field col m6 s12">
            <label for="product_infeed_rate">Product Infeed Rate: (min/max: 1-15)</label>
            @if ($errors->has('product_weight'))
                <span class="error">{{ $errors->first('product_weight') }}</span>
            @endif
            <p class="range-field" style="margin-top: 20px">
                <input wire:model="product_infeed_rate" type="range" name="product_infeed_rate" id="product_infeed_rate" min="1" max="15" />
            </p>
        </div>
        <div class="input-field col m6 s12">
            <label for="pallet_length">Pallet Length: (min/max: 600-1220mm)</label>
            <input type="text" id="pallet_length" name="pallet_length" class="validate" required="">
        </div>
    </div>
    <div class="row">
        <div class="input-field col m6 s12">
            <label for="pallet_width">Pallet Width: (min/max: 600-1220mm)</label>
            <input type="text" id="pallet_width" name="pallet_width" class="validate" required="">
        </div>
        <div class="input-field col m6 s12">
            <label for="pallet_height">Pallet Height: (min/max: 0.1-200mm)</label>
            <input type="text" id="pallet_height" name="pallet_height" class="validate" required="">
        </div>
    </div>
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
                <button class="waves-effect waves dark btn btn-primary next-step"  data-feedback="checkProductConfigurationStep">
                    Next
                    <i class="material-icons right">arrow_forward</i>
                </button>
            </div>
        </div>
    </div>
</div>
