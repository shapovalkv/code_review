@if(!empty($row->company))
    @php $company_translation = $row->company->translateOrOrigin(app()->getLocale()); @endphp
<div class="sidebar-widget company-widget">
    <div class="widget-content">
        <div class="company-title">
            @if(!empty($row->company->avatar_id))
                <div class="company-logo">
                    <a href="{{ $row->company->getDetailUrl() }}"><img src="{{ \Modules\Media\Helpers\FileHelper::url($row->company->avatar_id) }}" alt="{{ $row->company->name }}"></a>
                </div>
            @endif
            <h5 class="company-name">{{ $company_translation->name }}</h5>
            <a href="{{ $row->company->getDetailUrl() }}" class="profile-link">{{ __("View company profile") }}</a>
        </div>

        <ul class="company-info">
            @if($row->company->category)
                <li>{{ __("Primary industry:") }} <span>{{ $row->company->category->name }}</span></li>
            @endif
            @if($row->company->teamSize)
                <li>{{ __("Company size:") }} <span>{{ $row->company->teamSize->name }}</span></li>
            @endif
            @if($row->company->founded_in)
                <li>{{ __("Founded in:") }} <span>{{ date('Y', strtotime($row->company->founded_in)) }}</span></li>
            @endif
            @if($row->company->phone)
                @php
                $phone = $row->company->phone;
                    if(setting_item('enable_hide_email_company')) {
                        $phone = "****".substr($row->company->phone, -3);
                    }
                @endphp
                <li>{{ __("Phone:") }} <span>{{ $phone }}</span></li>
            @endif
            @if($row->company->email)
                @php
                $email = $row->company->email;
                if(setting_item('enable_hide_email_company')) {
                    $email_e = explode("@",$row->company->email);
                    if(isset($email_e[0]) && isset($email_e[1]))
                    {
                        $email = '****@'.$email_e[1];
                    }
                }
                @endphp
                <li>{{ __("Email:") }} <span>{{ $email }}</span></li>
            @endif
            @if($row->company->location)
                <li>{{ __("Location:") }} <span>{{ $row->company->location->name }}</span></li>
            @endif
            @if(!empty($row->company->social_media) && is_array($row->company->social_media) && count($row->company->social_media) > 0)
                <li>{{ __("Social media:") }}
                    <div class="social-links">
                        @foreach($row->company->social_media as $key => $social)
                            @if(!empty($social))
                                <a href="{{ $social }}"><i class="fab fa-{{ $key }}"></i></a>
                            @endif
                        @endforeach
                    </div>
                </li>
            @endif
        </ul>
        @if($row->company->website)
            <div class="btn-box"><a href="{{ ($row->company->website) }}" class="theme-btn btn-style-three" target="_blank">{{ $row->company->website }}</a></div>
        @endif
    </div>
</div>
@endif
