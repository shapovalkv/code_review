@php
    $translation = $row->translateOrOrigin(app()->getLocale());
@endphp
<div class="front-list-item-inner link-item" href="{{$row->getDetailUrl()}}">
    <ul class="job-other-info">
        @if($row->is_featured)
            <li class="privacy">{{ __("Popular") }}</li>
        @endif
        <li class="time">{{ __("Open Jobs – :count",["count"=> number_format($row->job_count)]) }}</li>
    </ul>
    <div class="front-list-item-header">
        <div class="front-list-item-logo">
            @if($image_tag = get_image_tag($row->avatar_id,'full',['alt'=>$translation->title, 'class'=>' ']))
                {!! $image_tag !!}
            @else
                <img class='lazy' src="{{ asset('images/avatar.png') }}" alt="avatar">
            @endif
        </div>
        <div class="front-list-item-title">
            <h4><a href="{{$row->getDetailUrl()}}">{!! clean($translation->name) !!}</a></h4>
        </div>
    </div>
    <ul class="front-list-item-info">
        @if(($main_office = $row->offices()->where('is_main', 1)->first() ?? $row->offices()->first()) && $main_office->location)
            <li><span class="icon las la-map-marker"></span>{{__("Location(s)")}}: <span>{{ $main_office->location->name}}{!! $row->offices()->count() > 1 ? ',<br> and others...': '' !!}</span></li>
        @endif
        @if($row->getOpenJobsCount() > 1)
            <li><span class="icon flaticon-briefcase"></span> {{ __('Multiple Positions') }}</li>
        @elseif($row->getOpenJobsCount() == 1)
            @php
                $category = $row->jobs->first()->category;;
                $t = $category?->translateOrOrigin(app()->getLocale());
            @endphp
            @if($t)
                <li><span class="icon flaticon-briefcase"></span> {{$t->name ?? ''}}</li>
            @endif
        @endif
    </ul>
    <div class="front-list-item-text">{!! \Illuminate\Support\Str::words(strip_tags($translation->about), 30, '...') !!}</div>
</div>
