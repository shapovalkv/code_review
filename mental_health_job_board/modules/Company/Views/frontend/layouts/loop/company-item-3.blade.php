@php
    $translation = $row->translateOrOrigin(app()->getLocale());
@endphp
<div class="company-block-four col-xl-3 col-lg-4 col-md-6 col-sm-12">
    <div class="inner-box link-item" href="{{$row->getDetailUrl()}}">
{{--        <button class="bookmark-btn @if($row->wishlist) active @endif service-wishlist" data-id="{{$row->id}}" data-type="{{$row->type}}"><span class="flaticon-bookmark"></span></button>--}}
        @if($row->is_featured)
            <span class="featured">{{ __("Popular") }}</span>
        @endif
        <span class="company-logo">
            @if($image_tag = get_image_tag($row->avatar_id,'full',['alt'=>$translation->title, 'class'=>'img-fluid mb-4 rounded-xs w-100']))
                {!! $image_tag !!}
            @endif
        </span>
        <h4><a href="{{$row->getDetailUrl()}}">{!! clean($translation->name) !!}</a></h4>
        <ul class="job-info">
            @if($main_office = $row->offices()->where('is_main', 1)->first() ?? $row->offices()->first())
                <li>{{__("Location(s)")}}: <span>{{ $main_office->location->name}}{!! $row->offices()->count() > 1 ? ',<br> and others...': '' !!}</span></li>
            @endif
            @php $category = $row->category; @endphp
            @if(!empty($category))
                @php $t = $category->translateOrOrigin(app()->getLocale()); @endphp
                <li><span class="icon flaticon-briefcase"></span> {{$t->name ?? ''}}</li>
            @endif

        </ul>
        <div class="job-type">{{ __("Open Jobs – :count",["count"=> number_format($row->job_count)]) }}</div>
    </div>
</div>
