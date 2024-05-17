<div>
    <div class="row">
        <label for="product_infeed_id">Select Product Infeed: <span class="red-text">*</span></label>
        <select class="browser-default" wire:model="product_infeed" id="product_infeed_id" name="product_infeed_id" wire:change="change">
            <option value="" disabled selected>Select Product Infeed</option>
            @foreach($product_infeeds as $infeed)
                <option value="{{ $infeed->id }}">{{ $infeed->name }}</option>
            @endforeach
        </select>
    </div>
{{--    <div class="row">--}}
{{--        <div class="select-dropdown col m6 s12" style="padding-top: 15px">--}}
{{--            <label for="product_infeed_id">Select Product Infeed: <span class="red-text">*</span></label>--}}
{{--            <select wire:model="product_infeeds" id="product_infeed_id" name="product_infeed_id">--}}
{{--                <option value="" disabled selected>Select Product Infeed</option>--}}
{{--                @foreach($product_infeeds as $infeed)--}}
{{--                    <option value="{{ $infeed->id }}">{{ $infeed->name }}</option>--}}
{{--                @endforeach--}}
{{--            </select>--}}
{{--        </div>--}}
{{--    </div>--}}
    <div class="row">
        <label for="left_pallet_position_id">Select Left Pallet Position: <span class="red-text">*</span></label>
        <select class="browser-default" wire:model="left_pallet_positions" id="left_pallet_position_id" name="left_pallet_position_id" {{ $left_right_disable }}>
            <option value="" disabled selected>Select Left Pallet Position</option>
            @foreach($left_pallet_positions as $left_pallet_position)
                <option value="{{ $left_pallet_position->id }}">{{ $left_pallet_position->name }}</option>
            @endforeach
        </select>
    </div>
{{--    </div>--}}
{{--    <div class="row">--}}
{{--        <div class="select-dropdown col m6 s12" style="padding-top: 15px">--}}
{{--            <label for="left_pallet_position_id">Select Left Pallet Position: <span class="red-text">*</span></label>--}}
{{--            <select  wire:model="left_pallet_positions" id="left_pallet_position_id" name="left_pallet_position_id">--}}
{{--                <option value="" disabled selected>Select Left Pallet Position</option>--}}
{{--                @foreach($left_pallet_positions as $left_pallet_position)--}}
{{--                    <option value="{{ $left_pallet_position->id }}">{{ $left_pallet_position->name }}</option>--}}
{{--                @endforeach--}}
{{--            </select>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <div class="row">--}}
{{--        <div class="select-dropdown col m6 s12" style="padding-top: 15px">--}}
{{--            <label for="right_pallet_position_id">Select Right Pallet Position: <span class="red-text">*</span></label>--}}
{{--            <select wire:model="right_pallet_positions" id="right_pallet_position_id" name="right_pallet_position_id" >--}}
{{--                <option value="" disabled selected>Select Right Pallet Position</option>--}}
{{--                @foreach($right_pallet_positions as $right_pallet_position)--}}
{{--                    <option value="{{ $right_pallet_position->id }}">{{ $right_pallet_position->name }}</option>--}}
{{--                @endforeach--}}
{{--            </select>--}}
{{--        </div>--}}
{{--    </div>--}}
    <div class="row">
        <label for="right_pallet_position_id">Select Right Pallet Position: <span class="red-text">*</span></label>
        <select class="browser-default" wire:model="right_pallet_positions" id="right_pallet_position_id" name="right_pallet_position_id" {{ $left_right_disable }}>
            <option value="" disabled selected>Select Right Pallet Position</option>
            @foreach($right_pallet_positions as $right_pallet_position)
                <option value="{{ $right_pallet_position->id }}">{{ $right_pallet_position->name }}</option>
            @endforeach
        </select>
    </div>
</div>
