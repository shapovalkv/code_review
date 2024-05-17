<!-- Filter Block -->
@if($list_employment_type && (count($list_employment_type) > 1 && $list_employment_type->first()->slug != 'practicum-site'))
    <div class="switchbox-outer">
        <h4>{{ $val['title'] }}</h4>
        <ul class="switchbox">
            @foreach($list_employment_type as $type)
                @php
                    $translation = $type->translateOrOrigin(app()->getLocale());
                @endphp
                <li>
                    <label class="switch">
                        <input type="checkbox" name="employment_type[]" value="{{ $type->id  }}" @if(!empty(request()->get('employment_type')) && in_array($type->id, request()->get('employment_type'))) checked @endif>
                        <span class="slider round"></span>
                        <span class="title">{{ $translation->name }}</span>
                    </label>
                </li>
            @endforeach
        </ul>
    </div>
@endif
