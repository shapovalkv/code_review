<div class="form-group">
    <label>{{__("Name")}} <span class="text-danger">*</span></label>
    <input type="text" required value="{{old('title',$translation->title)}}" placeholder="{{__("name")}}" name="title" class="form-control">
</div>
<div class="form-group">
    <label>{{__("Description")}} </label>
    <textarea name="content" cols="30" rows="5" class="form-control">{{old('content',$translation->content)}}</textarea>
</div>
<div class="form-group">
    <label>{{__("For Role")}} <span class="text-danger">*</span></label>
    <select name="role_id" class="form-control">
        <option value="">{{__("-- Please Select --")}}</option>
        @foreach(\Modules\User\Models\Role::all() as $role)
            <option @if(old('role_id',$row->role_id) == $role->id) selected @endif value="{{$role->id}}">{{$role->name}}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label class="control-label">{{__("Type")}} <span class="text-danger">*</span></label>
    <select name="plan_type" class="form-control" required>
        <option @if(old('plan_type', $row->plan_type) == \Modules\User\Models\Plan::TYPE_RECURRING) selected @endif value="{{ \Modules\User\Models\Plan::TYPE_RECURRING }}">{{__("Recurring")}}</option>
        <option @if(old('plan_type', $row->plan_type) == \Modules\User\Models\Plan::TYPE_ONE_TIME) selected @endif value="{{ \Modules\User\Models\Plan::TYPE_ONE_TIME }}">{{__("One Time")}}</option>
    </select>
</div>
<div class="form-group">
    <div class="form-check">
        <label class="control-label">
            <input
                class="form-check-input"
                type="checkbox"
                name="is_hidden"
                value="1"
                @if (old('is_hidden', $row->is_hidden)) checked @endif
            />
            {{__("Hidden?")}}
        </label>
    </div>
</div>

<div class="form-group">
    <label class="control-label">{{__("Price")}} </label>
    <input type="number" step="any" placeholder="{{__("Free")}}" value="{{old('price',$row->price)}}" name="price" class="form-control">
</div>
<div class="form-group">
    <label class="control-label">{{__("Annual Price")}}</label>
    <input type="number" step="any" value="{{old('annual_price',$row->annual_price)}}" name="annual_price" class="form-control">
</div>
<div class="form-group">
    <label class="control-label">{{__("Duration")}} <span class="text-danger">*</span></label>
    <input type="number" min="1" value="{{old('duration',max(1,$row->duration))}}" name="duration" class="form-control">
</div>
<div class="form-group">
    <label class="control-label">{{__("Duration Type")}} <span class="text-danger">*</span></label>
    <select name="duration_type" class="form-control" required>
        <option @if(old('duration_type',$row->duration_type) == 'day') selected @endif value="day">{{__("Day")}}</option>
        <option @if(old('duration_type',$row->duration_type) == 'week') selected @endif value="week">{{__("Week")}}</option>
        <option @if(old('duration_type',$row->duration_type) == 'month') selected @endif value="month">{{__("Month")}}</option>
        <option @if(old('duration_type',$row->duration_type) == 'year') selected @endif value="year">{{__("Year")}}</option>
    </select>
</div>
<div class="form-group">
    <label class="control-label">Features</label>

    @foreach (\Modules\User\Models\PlanFeature::FEATURES as $key => $name)
        <div class="row mb-2">
            <div class="col-md-4">
                <div class="form-check">
                    <input
                        name="features[{{ $key }}][is_active]"
                        id="feature_{{ $key }}_is_active"
                        value="{{ $key }}"
                        class="form-check-input"
                        type="checkbox"
                        {{
                            old('features.' . $key . '.is_active', isset($row) && $row->hasFeature($key)) ?
                                'checked'
                                : ''
                        }}
                    />

                    <label class="form-check-label" for="feature_{{ $key }}_is_active">
                        {{ $name }}
                    </label>
                </div>
            </div>
            <div class="col-md-8">
                <input
                    type="number"
                    name="features[{{ $key }}][value]"
                    class="form-control" placeholder="Unlimited"
                    value="{{ old("features.$key.value", isset($row) ? $row->getFeatureBySlug($key)->value : null) }}"
                />
            </div>
        </div>
    @endforeach
</div>

<div class="form-group">
    <label class="control-label">{{__("Status")}}</label>
    <select name="status" class="form-control">
        <option value="publish">{{__("Publish")}}</option>
        <option @if(old('status',$row->status) == 'draft') selected @endif value="draft">{{__("Draft")}}</option>
    </select>
</div>
