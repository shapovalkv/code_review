
<!-- Job Block -->
@php
    $translation = $row->translateOrOrigin(app()->getLocale());
@endphp
<div class="job-block">
    <div class="inner-box">
        <div class="content">
            <span class="company-logo">
                @if($row->company && $company_logo = $row->getThumbnailUrl())
                    <span class="company-logo">
                        <a href="{{ $row->company->getDetailUrl() }}"><img src="{{ $company_logo }}" alt="{{ $row->company ? $row->company->name : 'company' }}"></a>
                    </span>
                @endif
            </span>
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
            </ul>
        </div>
    </div>
</div>
