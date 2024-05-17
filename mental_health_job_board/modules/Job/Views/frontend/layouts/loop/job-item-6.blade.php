@php
    $translation = $row->translateOrOrigin(app()->getLocale());
@endphp

<div class="inner-box link-item" href="{{ $row->getDetailUrl() }}">
    <div class="content">
        @if($row->company && $company_logo = $row->getThumbnailUrl())
            <span class="company-logo">
                <a href="{{ $row->company->getDetailUrl() }}"><img src="{{ $company_logo }}"
                                 alt="{{ $row->company ? $row->company->name : 'company' }}"
                                 class="full-width object-cover"></a>
        </span>
        @endif
        <h4><a href="{{ $row->getDetailUrl() }}">{{ $translation->title }}</a></h4>
        <ul class="job-info">
            @if($row->category)
                @php $cat_translation = $row->category->translateOrOrigin(app()->getLocale()) @endphp
                <li><span class="icon flaticon-briefcase"></span> {{ $cat_translation->name }}</li>
            @endif
            @if($row->location)
                @php $location_translation = $row->location->translateOrOrigin(app()->getLocale()) @endphp
                <li><span class="icon flaticon-map-locator"></span> {{ $location_translation->name }}</li>
            @endif
            @if($row->created_at)
                <li><span class="icon flaticon-clock-3"></span> {{ $row->timeAgo() }}</li>
            @endif
            @if($row->salary_min && $row->salary_max)
                <li><span class="icon flaticon-money"></span> {{ $row->getSalary(false) }}</li>
            @endif
        </ul>
    </div>
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
        @if($row->position()->exists())
            <li class="w2California">{{ __($row->position->name) }}</li>
        @endif
        @if($employment_locations = json_decode($row->employment_location, true))
            @foreach($employment_locations as $employment_location_name => $employment_location_value)
                <li class="{{ $employment_location_name }}">{{ __(Str::title(str_replace('_', ' ', $employment_location_name))) }}</li>
            @endforeach
        @endif
    </ul>
    <a href="#" class="theme-btn btn-style-three">{{ __('Apply for Job') }}</a>
</div>
