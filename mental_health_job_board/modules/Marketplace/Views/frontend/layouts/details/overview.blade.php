<!-- Job Overview -->
<h4 class="widget-title">{{ __("Announcement Overview") }}</h4>
<div class="widget-content">
    <ul class="job-overview">
        @if($row->announcement_date)
            <li>
                <i class="icon icon-calendar"></i>
                <h5>{{ __("Announcement date:") }}</h5>
                <span>{{ display_date($row->announcement_date) }}</span>
            </li>
        @endif
        @if($row->MarketplaceCategory->slug === 'trainings')
            @if(!empty($announcement_status = json_decode($row->announcement_status, true)))
                <li>
                    <i class="icon icon-location"></i>
                    <h5>{{ __("Training Location") }}</h5>
                    @foreach($announcement_status as $key=>$val)
                        <span>{{ ucfirst(str_replace('_', ' ', $key)) }}</span>
                    @endforeach
                </li>
            @endif
            @if(!empty($announcement_status) && key_exists('online', $announcement_status) && !empty($row->location))
                @php $location_translation = $row->location->translateOrOrigin(app()->getLocale()) @endphp
                <li>
                    <i class="icon icon-location"></i>
                    <h5>{{ __("Location:") }}</h5>
                    <span>{{ $location_translation->name }}</span>
                </li>
            @endif
        @endif
        @if($row->website)
            <li>
                <i class="icon icon-location"></i>
                <h5>{{ __("WebSite Link:") }}</h5>
                <span><a
                        href="{{  !Str::startsWith($row->website, 'https://') ? 'https://' . $row->website : $row->website }}">{{ Str::startsWith($row->website, 'https://') ? Str::replaceFirst('https://', '', $row->website) : $row->website }}</a></span>
            </li>
        @endif
            @php
                $phone = $row->author->phone;
                    if(setting_item('enable_hide_phone_company')) {
                        $phone = "****".substr($row->author->phone, -3);
                    }
            @endphp
            <li>
                <i class="icon flaticon-phone" style="color: #0055b9; font-weight: bold;"></i>
                <h5> {{ __("Phone:") }}</h5>
                <span>{{ $phone }}</span>
            </li>
            @php
                $email = $row->author->email;
                if(setting_item('enable_hide_email_company')) {
                    $email_e = explode("@",$row->author->email);
                    if(isset($email_e[0]) && isset($email_e[1]))
                    {
                        $email = '****@'.$email_e[1];
                    }
                }
            @endphp
            <li>
                <i class="icon flaticon-email" style="color: #0055b9; font-weight: bold;"></i>
                <h5> {{ __("Email:") }}</h5>
                <span>{{ $email }}</span>
            </li>
        @if($row->created_at)
            <li>
                <i class="icon icon-calendar"></i>
                <h5>{{ __("Date Posted:") }}</h5>
                <span>{{ __("Posted :time_ago", ['time_ago' => $row->timeAgo()]) }}</span>
            </li>
        @endif
        @if($row->expiration_date)
            <li>
                <i class="icon icon-expiry"></i>
                <h5>{{ __("Expiration date:") }}</h5>
                <span>{{ display_date($row->expiration_date) }}</span>
            </li>
        @endif
    </ul>
</div>
