<div class="content">
    <div>
        <span class="company-logo">
                    @if($image_tag = get_image_tag($row->thumbnail_id,'full',['alt'=>$translation->title, 'class'=>'img-fluid mb-4 w-100']))
                <a href="{{get_file_url($row->thumbnail_id, 'full')}}" class="lightbox-image">{!! $image_tag !!}</a>
            @else
                <img class='img-fluid mb-4 w-100 lazy' src="{{ asset('images/avatar.png') }}" alt="avatar">
            @endif
                </span>
        <h4>{{ $translation->title }}</h4>
        <ul class="job-info">
            @if($row->MarketplaceCategory)
                @php $cat_translation = $row->MarketplaceCategory->translateOrOrigin(app()->getLocale()) @endphp
                <li><span class="icon flaticon-briefcase"></span> {{ $cat_translation->name }}</li>
            @endif
            @if($row->location)
                @php $location_translation = $row->location->translateOrOrigin(app()->getLocale()) @endphp
                <li><span class="icon flaticon-map-locator"></span> {{ $location_translation->name }}</li>
            @endif
            @if($row->created_at)
                <li><span class="icon flaticon-clock-3"></span> {{ $row->timeAgo() }}</li>
            @endif
            @if($row->salary_min)
                <li><span class="icon flaticon-money"></span> {{ $row->getSalary() }}</li>
            @endif
        </ul>
        <ul class="job-other-info">
            @if($row->jobType)
                @php $jobType_translation = $row->jobType->translateOrOrigin(app()->getLocale()) @endphp
                <li class="time">{{ $jobType_translation->name }}</li>
            @endif
            @if($row->is_featured)
                <li class="privacy">{{ __("Popular") }}</li>
            @endif
            @if($row->is_urgent)
                <li class="required">{{ __("Urgent") }}</li>
            @endif
            @if($employment_locations = json_decode($row->employment_location, true))
                @foreach($employment_locations as $employment_location_name => $employment_location_value)
                    <li class="{{ $employment_location_name }}">{{ __(Str::title(str_replace('_', ' ', $employment_location_name))) }}</li>
                @endforeach
            @endif
        </ul>
    </div>
</div>
