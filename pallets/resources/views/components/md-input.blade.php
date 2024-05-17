<div class="mdc-layout-grid">
    <label class="mdc-text-field mdc-text-field--outlined mdc-text-field--label-floating w100 @error($attributes->get('wire:model')) mdc-text-field--invalid @enderror">
                                                <span class="mdc-notched-outline {{ !empty($attributes->wire('model')->value()) ? 'mdc-notched-outline--upgraded mdc-notched-outline--notched' : '' }}">
                                                    <span class="mdc-notched-outline__leading"></span>
                                                    <span class="mdc-notched-outline__notch">
                                                        <span class="mdc-floating-label {{ !empty($attributes->wire('model')->value()) ? 'mdc-floating-label--float-above' : '' }}" id="product_length">{{ $attributes->get('placeholder') }}:*<span class="helper"> ({{ $attributes->get('min') }}- {{ $attributes->get('max')  }} {{ $attributes->get('code')}})</span></span>
                                                    </span>
                                                    <span class="mdc-notched-outline__trailing"></span>
                                                </span>
        <input type="text" wire:model.blur="{{ $attributes->get('wire:model') }}" class="mdc-text-field__input"
               aria-labelledby="{{ $attributes->get('wire:model') }}" onkeydown="@if($attributes->get('measurementSystem') === "imperial") numberswithdotonly(event) @else numbersonly(event)@endif"
        >
    </label>
    <div>
        @error($attributes->get('wire:model')) <span class="error">{{ $message }}</span> @enderror
    </div>
</div>
