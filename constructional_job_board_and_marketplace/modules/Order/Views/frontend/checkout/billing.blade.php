<div class="post-form__left-col" data-select2-id="6">
    <div class="ls-widget">
        <div class="tabs-box">
            <div class="widget-title"><h4>{{ __("Billing Details") }}</h4></div>

            <div class="default-form" data-select2-id="5">
                <div class="row">
                    <!--Form Group-->
                    <div class="form-group col-lg-6 col-md-12 col-sm-12">
                        <label class="form-label">{{__('First name')}} <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            name="first_name"
                            value="{{old('first_name',$user->billing_first_name ? $user->billing_first_name : $user->first_name)}}"
                            placeholder="First name"
                            class="form-control"
                        >
                    </div>

                    <!--Form Group-->
                    <div class="form-group col-lg-6 col-md-12 col-sm-12">
                        <label class="form-label">{{__('Last name')}} <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            name="last_name"
                            value="{{old('last_name',$user->billing_last_name ? $user->billing_last_name : $user->last_name)}}"
                            placeholder="Last name"
                            class="form-control"
                        >
                    </div>

                    <div class="col-sm-6 mb-4 form-group">
                        <label class="form-label">{{ __("Phone") }} <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">+1</span>
                            <input
                                id="phone"
                                type="text"
                                value="{{$user->phone ?? ''}}"
                                name="phone"
                                placeholder="{{__("Your Phone")}}"
                                class="form-control"
                                required
                            >
                        </div>
                    </div>

                    <div class="col-sm-6 mb-4 form-group">
                        <label class="form-label">
                            {{ __("Country") }}  <span class="text-danger">*</span>
                        </label>
                        <select class="form-control select" name="country" >
                            <option value="">{{__('-- Select --')}}</option>
                            @foreach(get_country_lists() as $id=>$name)
                                <option @if(($user->country ?? '') == $id) selected @endif value="{{$id}}">{{$name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-sm-6 mb-4 form-group">
                        <label class="form-label">
                            {{ __("State/Province/Region") }}
                        </label>
                        <input
                            type="text"
                            class="form-control"
                            value="{{$user->state ?? ''}}"
                            name="state"
                            placeholder="{{__("State/Province/Region")}}"
                        >
                    </div>
                    <div class="col-sm-6 mb-4 form-group">
                        <label class="form-label">
                            {{ __("City") }}
                        </label>
                        <input
                            type="text"
                            value="{{$user->city ?? ''}}"
                            name="city"
                            placeholder="{{__("Your City")}}"
                            class="form-control"
                        >
                    </div>

                    <div class="col-sm-6 mb-4 form-group">
                        <label class="form-label">
                            {{ __("ZIP code/Postal code") }}  <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"  value="{{$user->zip_code ?? ''}}"
                            name="zip_code"
                            placeholder="{{__("ZIP code/Postal code")}}"
                            class="form-control"
                        >
                    </div>

                    <div class="form-group col-lg-12 col-md-12 col-sm-12">
                        <label class="form-label">{{ __("Street address") }} <span class="text-danger">*</span></label>
                        <input
                            class="mb-4 form-control"
                            type="text"
                            value="{{$user->address ?? ''}}"
                            name="address"
                            placeholder="{{__('House number and street name')}}"
                        >
                        <input
                            type="text"
                            value="{{$user->address2 ?? ''}}"
                            name="address_line_2"
                            placeholder="{{__('Apartment,suite,unit etc. (optional)')}}"
                            class="form-control"
                        >
                    </div>
                    <div class="w-100"></div>
                </div>
            </div>
        </div>
    </div>
</div>
