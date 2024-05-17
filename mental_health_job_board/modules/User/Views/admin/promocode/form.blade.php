<div class="form-row align-items-center form-group">
    <div class="col-md-1">
        <label>{{__('Title')}} <span class="text-danger">*</span></label>
    </div>
    <div class="input-group col-md-11">
    <input type="text" required value="{{old('title',$row->title)}}" placeholder="{{__('Title')}}" name="title"
           class="form-control">
    </div>
</div>
<div class="form-row align-items-center form-group">
    <div class="col-md-1">
        <label>{{__('Code')}} <span class="text-danger">*</span></label>
    </div>
    <div class="input-group col-md-3">
        <div class="input-group-prepend">
            <a class="btn btn-primary generate-code">Generate</a>
        </div>
        <input type="text" required value="{{old('code',$row->code)}}" placeholder="{{__('Code')}}" name="code"
               class="form-control">
    </div>
</div>
<div class="form-row align-items-center form-group">
    <div class="col-md-1">
        <label>{{__('Value')}} <span class="text-danger">*</span></label>
    </div>
    <div class="form-group col-md-8">
        <input type="number" required value="{{old('value',$row->value)}}" placeholder="{{__('Value')}}" name="value"
               class="form-control">
    </div>
    <div class="col-md-3">
        <label class="control-label">
            <input
                class="form-control"
                type="checkbox"
                name="is_percent"
                value="1"
                @if (old('is_percent', $row->is_percent)) checked @endif
            />
            {{__('Is percent')}}
        </label>
    </div>

</div>

<div class="form-row align-items-center form-group">
    <div class="col-md-1">
        <label>{{__("Plans")}}</label>
    </div>
    <div class="form-group col-md-8">
        <select name="plan_ids[]" class="form-control select-plans" multiple>
            <option value="">{{__("-- Please Select --")}}</option>
            @foreach(\Modules\User\Models\Plan::query()->with(\Modules\User\Models\Plan::RELATION_ROLE)->get() as $plan)
                <option @if(in_array($plan->id, old('plan_ids',$row->plans->pluck('id')->toArray()), true)) selected
                        @endif value="{{$plan->id}}">[{{strtoupper($plan->status)}}] {{$plan->title}} ({{$plan->role->name}})</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="control-label">
            <input
                class="form-control"
                type="checkbox"
                name="is_annual"
                value="1"
                @if (old('is_annual', $row->is_annual)) checked @endif
            />
            {{__('Is annual')}}
        </label>
    </div>
</div>

<div class="form-row align-items-center form-group">
    <div class="col-md-1">
        <label>{{__('Expiration Date')}}</label>
    </div>
    <div class="form-group col-md-11">
    <input type="text" value="{{old('expiration_date',$row->expiration_date?->format('m/d/Y'))}}" placeholder="{{__('Expiration Date')}}" name="expiration_date"
           class="form-control has-datepicker">
    </div>
</div>

@section('script.body')
    <script>
        $(function() {
            $('.generate-code').on('click', function() {
                $('input[name="code"]').val(randomString(12));
            });
            $('.select-plans').select2({
                placeholder: 'Choose plans',
                allowClear: true,
                multiple: true
            });
        })

        function randomString(length) {
            let result = '';
            const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            const charactersLength = characters.length;
            let counter = 0;
            while (counter < length) {
                if (counter % 4 === 0 && counter > 0) {
                    result += '-';
                }
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
                counter += 1;
            }
            return result;
        }

    </script>
@endsection
