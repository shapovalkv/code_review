<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{$html_class ?? ''}}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php event(new \Modules\Layout\Events\LayoutBeginHead()); @endphp
    @php
        $favicon = setting_item('site_favicon');
    @endphp
    @if($favicon)
        @php
            $file = (new \Modules\Media\Models\MediaFile())->findById($favicon);
        @endphp
        @if(!empty($file))
            <link rel="icon" type="{{$file['file_type']}}" href="{{asset('uploads/'.$file['file_path'])}}" />
        @else:
        <link rel="icon" type="image/png" href="{{url('images/favicon.png')}}" />
        @endif
    @endif

    @include('Layout::parts.seo-meta')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Noto+Sans+JP:wght@400;500;700;900&display=swap" rel="stylesheet">
    <script src="https://cdn.userway.org/widget.js" data-account="TM4PT8BfWz"></script>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-J10PPRWQ2E"></script>
    <script>window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag('js', new Date()); gtag('config', 'G-J10PPRWQ2E');</script>
    <script src="https://kit.fontawesome.com/73ac451d54.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset("libs/daterange/daterangepicker.css") }}" >
    <link rel="stylesheet" type="text/css" href="{{ asset("libs/select2/css/select2.min.css") }}" >
    <link href="{{ asset('libs/bootstrap/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/frontend/css/notification.css') }}" rel="newest stylesheet">

    <!-- Stylesheets -->
    <link href="{{ asset('module/superio/css/font.css') }}" rel="stylesheet">
    <link href="{{ asset('module/superio/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('module/superio/css/style.css?_ver='.config('app.asset_version')) }}" rel="stylesheet">
    <link href="{{ asset('module/superio/css/responsive.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/frontend/css/app.css?_ver='.config('app.asset_version')) }}" rel="stylesheet">

    <link href="{{ asset('dist/frontend/module/user/css/user.css?_ver='.config('app.asset_version')) }}" rel="stylesheet">


    {!! \App\Helpers\Assets::css() !!}
    {!! \App\Helpers\Assets::js() !!}
    <script>
        var superio = {
            url:'{{url( app_get_locale() )}}',
            url_root:'{{ url('') }}',
            booking_decimals:{{(int)get_current_currency('currency_no_decimal',2)}},
            thousand_separator:'{{get_current_currency('currency_thousand')}}',
            decimal_separator:'{{get_current_currency('currency_decimal')}}',
            currency_position:'{{get_current_currency('currency_format')}}',
            currency_symbol:'{{currency_symbol()}}',
            currency_rate:'{{get_current_currency('rate',1)}}',
            date_format:'{{get_moment_date_format()}}',
            map_provider:'{{setting_item('map_provider')}}',
            map_gmap_key:'{{setting_item('map_gmap_key')}}',
            routes:{
                login:'{{route('auth.login')}}',
                register:'{{route('auth.register')}}',
                checkout:'{{is_api() ? route('api.booking.doCheckout') : route('booking.doCheckout')}}',
                applyJob: '{{ route('job.apply-job') }}'
            },
            module:{
                job:'',
            },
            currentUser: {{(int)Auth::id()}},
            isAdmin : {{is_admin() ? 1 : 0}},
            rtl: {{ setting_item_with_lang('enable_rtl') ? "1" : "0" }},
            markAsRead:'{{route('core.notification.markAsRead')}}',
            markAllAsRead:'{{route('core.notification.markAllAsRead')}}',
            loadNotify : '{{route('core.notification.loadNotify')}}',
            pusher_api_key : '{{setting_item("pusher_api_key")}}',
            pusher_cluster : '{{setting_item("pusher_cluster")}}',
        };
        var i18n = {
            warning:"{{__("Warning")}}",
            success:"{{__("Success")}}",
            applied:"{{ __("Applied") }}",
            chooseACv:"{{ __("Choose a cv") }}",
            confirm_delete: "{{ __("Do you want to delete?") }}",
            default_cv_del: "{{ __("Default CV cannot be deleted") }}",
        };
        var daterangepickerLocale = {
            "applyLabel": "{{__('Apply')}}",
            "cancelLabel": "{{__('Cancel')}}",
            "fromLabel": "{{__('From')}}",
            "toLabel": "{{__('To')}}",
            "customRangeLabel": "{{__('Custom')}}",
            "weekLabel": "{{__('W')}}",
            "first_day_of_week": {{ setting_item("site_first_day_of_the_weekin_calendar","1") }},
            "daysOfWeek": [
                "{{__('Su')}}",
                "{{__('Mo')}}",
                "{{__('Tu')}}",
                "{{__('We')}}",
                "{{__('Th')}}",
                "{{__('Fr')}}",
                "{{__('Sa')}}"
            ],
            "monthNames": [
                "{{__('January')}}",
                "{{__('February')}}",
                "{{__('March')}}",
                "{{__('April')}}",
                "{{__('May')}}",
                "{{__('June')}}",
                "{{__('July')}}",
                "{{__('August')}}",
                "{{__('September')}}",
                "{{__('October')}}",
                "{{__('November')}}",
                "{{__('December')}}"
            ],
        };
    </script>
    <!-- Styles -->
    @yield('head')
    <style>
        :root{
            --main-color:{{setting_item('style_main_color','#1967D2')}}
        }
    </style>
    {{--Custom Style--}}
    <link href="{{ route('core.style.customCss') }}" rel="stylesheet">
    <link href="{{ asset('libs/carousel-2/owl.carousel.css') }}" rel="stylesheet">
    @if(setting_item_with_lang('enable_rtl'))
        <link href="{{ asset('dist/frontend/css/rtl.css') }}" rel="stylesheet">
    @endif
    {!! setting_item('head_scripts') !!}
    {!! setting_item_with_lang_raw('head_scripts') !!}

    @php event(new \Modules\Layout\Events\LayoutEndHead()); @endphp

</head>
<body data-anm=".anm" class="frontend-page user_wrap {{$body_class ?? ''}} @if(!empty($is_home) or !empty($header_transparent)) header_transparent @endif @if(setting_item_with_lang('enable_rtl')) is-rtl @endif @if(is_api()) is_api @endif">
@php event(new \Modules\Layout\Events\LayoutBeginBody()); @endphp

{!! setting_item('body_scripts') !!}
{!! setting_item_with_lang_raw('body_scripts') !!}
<div class="page-wrapper dashboard mm-page mm-slideout bravo_wrap">
    @if(!is_api())
        @include('Layout::parts.header')
    @endif

    <!-- Sidebar Backdrop -->
    <div class="sidebar-backdrop"></div>

    <div class="user-dashboard bc-user-dashboard">
        <div class="dashboard-outer">
            @yield('content')
        </div>
    </div>

    <div class="copyright-text">
        <p>{!! @clean(setting_item_with_lang('copyright')) !!}</p>
    </div>

    @include('Layout::parts.sidebar')

    @include('Layout::parts.footer', ['footer_null' => true])
</div>
{!! setting_item('footer_scripts') !!}
{!! setting_item_with_lang_raw('footer_scripts') !!}
@php event(new \Modules\Layout\Events\LayoutEndBody()); @endphp

</body>
</html>
