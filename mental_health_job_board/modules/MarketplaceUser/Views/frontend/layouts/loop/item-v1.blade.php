<!-- Job Block -->
@php
    $view_profile = (!empty($hide_profile)) ? 0 : 1;
@endphp
<div class="inner-box">
    <div class="content">

        <figure class="image"><img src="{{is_applied($row->id) ? $row->user->getAvatarUrl() : asset('images/avatar.png')}}" alt="{{ $row->user->getDisplayName()}}">
        </figure>

        <h4 class="name"><a
                href="{{ $row->getDetailUrl() }}">{{ is_applied($row->id) ? $row->user->getDisplayName()  : $row->user->getShortCutName()}}</a>
        </h4>
        <ul class="marketplace_user-info">
            @if($row->title)
                <li class="designation">{{$row->title}}</li>
            @endif
                @if($row->location_id)
                    <li><span class="icon flaticon-map-locator"></span> {{$row->location->name}}</li>
                @endif
            {{--            @if($row->expected_salary)--}}
            {{--                <li><span class="icon flaticon-money"></span> {{$row->expected_salary}} {{currency_symbol()}}  / {{$row->salary_type}}</li>--}}
            {{--            @endif--}}
        </ul>
    </div>

    @if($view_profile)
        <div class="btn-box">
            @if(is_employer())
                <button class="bookmark-btn service-wishlist @if($row->wishlist) active @endif" data-id="{{$row->id}}"
                        data-type="{{$row->type}}"><span class="flaticon-bookmark"></span></button>
            @endif
            <a href="{{ $row->getDetailUrl() }}" class="theme-btn btn-style-three"><span
                    class="btn-title">{{__('View Profile')}}</span></a>
            @if(is_employer())
                <a href="#" data-id="{{$row->id}}" class="theme-btn btn-style-three bc-apply-job-button bc-call-modal invite-job">{{ __("Invite For Job") }}</a>
            @endif
        </div>
    @endif
</div>

