@php
    $translation = $row->translateOrOrigin(app()->getLocale());
@endphp
<div class="front-list-item-inner link-item" href="{{$row->getDetailUrl()}}">
    <div class="front-list-item-header">
        <div class="front-list-item-logo">
            @if($image_tag = get_image_tag($row->thumbnail_id,'full',['alt'=>$translation->title, 'class'=>'', 'style' => 'border-radius: 0;']))
                {!! $image_tag !!}
            @else
                <img class='' src="{{ asset('images/avatar.png') }}" alt="avatar"
                     style="border-radius: 0;">
            @endif
        </div>
        <div class="front-list-item-title">
            <h4><a href="{{$row->getDetailUrl()}}">{!! clean($translation->title) !!}</a></h4>
        </div>
    </div>
    <ul class="job-other-info">
        @if($row->is_featured)
            <li class="privacy">{{ __("Popular") }}</li>
        @endif
        <?php $announcement_status = json_decode($row->announcement_status, true); ?>
        @if(!empty($announcement_status) && key_exists('online', $announcement_status))
            <li class="online_announcement">{{ __("Online") }}</li>
        @endif
        @if(!empty($announcement_status) && key_exists('in_person', $announcement_status))
            <li class="in_person_announcement">{{ __(" In Person") }}</li>
        @endif
        @if($row->announcement_date)
            <li class="announcement_date"><span
                    class="icon flaticon-clock-3"></span> {{ $row->announcement_date ? date(get_date_format(), strtotime($row->announcement_date)) : '' }}
            </li>
        @endif
    </ul>
    <ul class="front-list-item-info">
        @if($row->MarketplaceCategory)
            @php $cat_translation = $row->MarketplaceCategory->translateOrOrigin(app()->getLocale()) @endphp
            <li><span class="icon flaticon-briefcase"></span> {{ $cat_translation->name }}</li>
        @endif
        @if($row->created_at)
            <li class="announcement_date"><span
                    class="icon flaticon-clock-3"></span>Publication
                date: {{ $row->created_at ? date(get_date_format(), strtotime($row->created_at)) : '' }}
            </li>
        @endif

        @if($row->location)
            @php $location_translation = $row->location->translateOrOrigin(app()->getLocale()) @endphp
            <li>
                <div class="location"><span
                        class="icon flaticon-map-locator"></span>{{ $location_translation->name }}
                </div>
            </li>
        @endif

        @if($employment_locations = json_decode($row->employment_location, true))
            @foreach($employment_locations as $employment_location_name => $employment_location_value)
                <li class="{{ $employment_location_name }}">{{ __(Str::title(str_replace('_', ' ', $employment_location_name))) }}</li>
            @endforeach
        @endif
    </ul>

    <div class="front-list-item-text">{!! \Illuminate\Support\Str::words(strip_tags($translation->content), 30, '...') !!}</div>
</div>
