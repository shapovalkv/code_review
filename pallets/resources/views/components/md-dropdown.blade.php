<div
    x-data="{
    el: null,
    init() {
        this.$nextTick(() => {
            this.el = new MDCSelect($refs.select);
            this.el.listen('MDCSelect:change', () => {
                @this.set('{{ $attributes->get('wire:model') }}', this.el.value)
            });
            this.el.value = @entangle($attributes->wire('model')).initialValue+'';
        });
        Livewire.hook('morph.updated', (el, component) => {
            if (el.el === $refs.list) {
                setTimeout(() => {
                    this.el.layoutOptions();
                    const newVal = @entangle($attributes->wire('model')).initialValue+'';
                    if (this.el.value !== newVal) {
                        const index = this.el.foundation.adapter.getMenuItemValues().indexOf(newVal)
                        this.el.foundation.setSelectedIndex(index, false, true)
                    }
                }, 0)
            }
        })
    }
}"

    {{ $attributes->only(['class']) }}
>
    <div class="mdc-select mdc-select--outlined mdc-select--required" x-ref="select">
        <div class="mdc-select__anchor" aria-labelledby="outlined-select-label" wire:ignore>
            <span class="mdc-notched-outline">
                <span class="mdc-notched-outline__leading"></span>
                <span class="mdc-notched-outline__notch">
                    <span id="outlined-select-label" class="mdc-floating-label">{{ $attributes->get('placeholder') }}</span>
                </span>
                <span class="mdc-notched-outline__trailing"></span>
            </span>
            <span class="mdc-select__selected-text-container">
                        <span id="demo-selected-text"
                              class="mdc-select__selected-text"></span>
                    </span>
            <span class="mdc-select__dropdown-icon">
                        <svg class="mdc-select__dropdown-icon-graphic" viewBox="7 10 10 5"
                             focusable="false">
                            <polygon class="mdc-select__dropdown-icon-inactive"
                                     stroke="none" fill-rule="evenodd"
                                     points="7 10 12 15 17 10"></polygon>
                            <polygon class="mdc-select__dropdown-icon-active" stroke="none"
                                     fill-rule="evenodd"
                                     points="7 15 12 10 17 15"></polygon>
                        </svg>
                    </span>
        </div>
        <div
            class="mdc-select__menu mdc-menu mdc-menu-surface mdc-menu-surface--fullwidth">
            <ul class="mdc-list" role="listbox" aria-label="Food picker listbox" x-ref="list">
                @foreach($options as $key => $item)
                    <li class="mdc-list-item"
                        {{ $item->id == $attributes->wire('model')->value() ? "aria-selected='true'" : '' }}
                        tabindex="{{ $key }}"
                        data-value="{{ $item->id }}" role="option">
                        <span class="mdc-list-item__ripple"></span>
                        <span class="mdc-list-item__text">{{ \Illuminate\Support\Str::ucfirst($item->name ?? $item->concatenated_description) }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
        <div>
            @error($attributes->get('wire:model')) <span class="error">{{ $message }}</span> @enderror
        </div>
    </div>
</div>
