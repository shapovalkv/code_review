<div class="content">
    <figure class="image"><img src="{{is_applied($row->id) ? $row->user->getAvatarUrl() : asset('images/avatar.png')}}" alt=""></figure>
    <h4 class="name"><a href="#">{{ is_applied($row->id) ? $row->user->getDisplayName() : $row->user->getShortCutName()}}</a></h4>
    <ul class="marketplace_user-info">
        <li class="designation">{{$row->title}}</li>
        @if($row->location_id)
            <li><span class="icon flaticon-map-locator"></span> {{$row->location->name}}</li>
        @endif
{{--        @if($row->expected_salary)--}}
{{--            <li><span class="icon flaticon-money"></span> {{$row->expected_salary}} {{currency_symbol()}}  / {{$row->salary_type}}</li>--}}
{{--        @endif--}}
        @if($row->user->created_at)
            <li><span class="icon flaticon-clock"></span> {{__('Member Since')}} {{date('M d, Y', strtotime($row->user->created_at))}}</li>
        @endif
    </ul>
    @php
        $categories = $row->getCategory();
    @endphp
    <ul class="post-tags">
        @if(!empty($row->categories))
            @foreach($row->categories as $oneCategory)
                @php $trans = $oneCategory->translateOrOrigin(app()->getLocale()); @endphp
                <li><a target="_blank" href="{{ route('marketplace_user.index', ['category' => $oneCategory->id]) }}">{{$trans->name}}</a></li>
            @endforeach
        @endif
    </ul>
</div>
