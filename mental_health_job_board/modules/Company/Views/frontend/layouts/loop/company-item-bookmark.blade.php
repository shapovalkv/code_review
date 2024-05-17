@php
    $translation = $row->translateOrOrigin(app()->getLocale());
@endphp
<div class="company-block-three company-bookmark-item">
    <div class="inner-box link-item" href="{{$row->getDetailUrl()}}">
        <div class="content">
            <div class="content-inner">
                <span class="company-logo">
                    @if($image_tag = get_image_tag($row->avatar_id,'full',['alt'=>$translation->title, 'class'=>'img-fluid mb-4 rounded-xs w-100']))
                        {!! $image_tag !!}
                    @endif
                </span>
                <h4><a href="{{$row->getDetailUrl()}}">{!! clean($translation->name) !!}</a></h4>
                <ul class="job-info">
                    @if($row->location)
                        @php $location_translation = $row->location->translateOrOrigin(app()->getLocale()); @endphp
                        <li><span class="icon flaticon-map-locator"></span> {{ $location_translation->name }}</li>
                    @endif
                    @php $category = $row->category; @endphp
                    @if(!empty($category))
                        @php $t = $category->translateOrOrigin(app()->getLocale()); @endphp
                        <li><span class="icon flaticon-briefcase"></span> {{$t->name ?? ''}}</li>
                    @endif
                </ul>
            </div>
            <ul class="job-other-info">
                @if($row->is_featured)
                    <li class="privacy">{{ __("Popular") }}</li>
                @endif
                <li class="time">{{ __("Open Jobs – :count",["count"=> number_format($row->job_count)]) }}</li>
            </ul>
        </div>
{{--        <a href="#" data-text="Remove" data-confirm="{{ __("Do you want to remove?") }}" data-id="{{ $wishlist->id }}" class="remove-wishlist bookmark-btn" ><span class="la la-trash"></span></a>--}}
    </div>
</div>
