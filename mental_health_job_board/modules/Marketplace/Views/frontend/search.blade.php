@extends('layouts.app')
@section('head')
    <link rel="stylesheet" type="text/css" href="{{ asset("libs/ion_rangeslider/css/ion.rangeSlider.min.css") }}"/>
@endsection
@section('content')

    <section class="page-title">
        <div class="auto-container">
            <div class="title-outer">
                @if(isset($_GET['category']))
                    @if(!empty(setting_item("marketplace_trainings_link")) && Str::contains(setting_item("marketplace_trainings_link"), $request->query('category')))
                        <h1> {{ setting_item("marketplace_trainings_title") ?? '' }}</h1>
                    @elseif(!empty(setting_item("marketplace_subLeasing_link")) && Str::contains(setting_item("marketplace_subLeasing_link"), $request->query('category')))
                        <h1>{{  setting_item("marketplace_subLeasing_title") ?? '' }}</h1>
                    @elseif(!empty(setting_item("marketplace_professionalAssistance_link")) && Str::contains(setting_item("marketplace_professionalAssistance_link"), $request->query('category')))
                        <h1> {{ setting_item("marketplace_professionalAssistance_title") ?? '' }}</h1>
                    @endif
                @else
                    <h1>{{ !empty(setting_item("marketplace_page_title")) ? setting_item("marketplace_page_title") : __('All Announcements') }}
                @endif
            </div>
        </div>
    </section>
    <!--Page Title-->
    @if(!isset($_GET['category']))
        <section class="announcement-page-title marketplace-badge">
            <div class="auto-container">
                <div class="sec-title text-center">
                    <div class="text">
                        @if(!empty(setting_item("marketplace_trainings_link")) && Str::contains(setting_item("marketplace_trainings_link"), $request->query('category')))
                            {{ setting_item("marketplace_trainings_sub_title") ?? '' }}
                        @elseif(!empty(setting_item("marketplace_subLeasing_link")) && Str::contains(setting_item("marketplace_subLeasing_link"), $request->query('category')))
                            {{  setting_item("marketplace_subLeasing_sub_title") ?? '' }}
                        @elseif(!empty(setting_item("marketplace_professionalAssistance_link")) && Str::contains(setting_item("marketplace_professionalAssistance_link"), $request->query('category')))
                            {{ setting_item("marketplace_professionalAssistance_sub_title") ?? '' }}
                        @endif
                    </div>
                </div>

                @if(!empty(setting_item("marketplace_trainings_title")) &&
                    !empty(setting_item("marketplace_subLeasing_title")) &&
                    !empty(setting_item("marketplace_professionalAssistance_title")))

                    <div class="auto-container">
                        <div class="row">
                            <div class="work-block pb-0 col-lg-4 col-md-6 col-sm-12">
                                <a href="{{route('marketplace.search').!empty(setting_item("marketplace_trainings_link")) ? setting_item("marketplace_trainings_link") : __('?category=trainings')}}">
                                    <div class="inner-announcement-box">
                                        <h5 class="text-style-one">{{ !empty(setting_item("marketplace_trainings_title")) ? setting_item("marketplace_trainings_title") : __('Trainings') }}</h5>
                                        <figure class="image"><img
                                                src="{{ get_file_url(setting_item("marketplace_trainings_img")  ?? '') }}"
                                                alt="{{ !empty(setting_item("marketplace_page_sub_title")) ? setting_item("marketplace_page_sub_title") : __('Trainings') }}">
                                        </figure>
                                        <p>{!! setting_item('marketplace_trainings_desc') !!}</p>
                                        <div class="mt-2">
                                            <a href="{{ route('marketplace.search').!empty(setting_item("marketplace_trainings_link")) ? setting_item("marketplace_trainings_link") : __('?category=trainings')  }}"
                                               class="theme-btn btn-style-two bg-blue">{{ __('View ') }}{{ /*!empty(setting_item("marketplace_trainings_title")) ?\Illuminate\Support\Str::plural(setting_item("marketplace_trainings_title")) :*/ __('Trainings') }}</a>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="work-block col-lg-4 col-md-6 col-sm-12">
                                <a href="{{route('marketplace.search').!empty(setting_item("marketplace_subLeasing_link")) ? setting_item("marketplace_subLeasing_link") : __('?category=trainings')}}">
                                    <div class="inner-announcement-box">
                                        <h5 class="text-style-one">{{ !empty(setting_item("marketplace_subLeasing_title")) ? setting_item("marketplace_subLeasing_title") : __('Sub-Leasing') }}</h5>
                                        <figure class="image"><img
                                                src="{{ get_file_url(setting_item("marketplace_subLeasing_img")  ?? '') }}"
                                                alt="{{ !empty(setting_item("marketplace_page_sub_title")) ? setting_item("marketplace_page_sub_title") : __('Sub-Leasing') }}">
                                        </figure>
                                        <p>{!! setting_item('marketplace_subLeasing_desc') !!}</p>
                                        <div class="mt-2">
                                            <a href="{{route('marketplace.search').!empty(setting_item("marketplace_subLeasing_link")) ? setting_item("marketplace_subLeasing_link") : __('?category=trainings') }}"
                                               class="theme-btn btn-style-two bg-blue">{{ __('View ') }}{{ /*!empty(setting_item("marketplace_subLeasing_title")) ? \Illuminate\Support\Str::plural(setting_item("marketplace_subLeasing_title")) :*/ __('Office Spaces') }}</a>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="work-block col-lg-4 col-md-6 col-sm-12">
                                <a href="{{ route('marketplace.search').!empty(setting_item("marketplace_professionalAssistance_link")) ? setting_item("marketplace_professionalAssistance_link") : __('?category=trainings') }}">
                                    <div class="inner-announcement-box">
                                        <h5 class="text-style-one">{{ !empty(setting_item("marketplace_professionalAssistance_title")) ? setting_item("marketplace_professionalAssistance_title") : __('Professional Resources') }}</h5>
                                        <figure class="image"><img
                                                src="{{ get_file_url(setting_item("marketplace_professionalAssistance_img")  ?? '') }}"
                                                alt="{{ !empty(setting_item("marketplace_page_sub_title")) ? setting_item("marketplace_page_sub_title") : __('Professional Resources') }}">
                                        </figure>
                                        <p>{!! setting_item('marketplace_professionalAssistance_desc') !!}</p>
                                        <div class="mt-2">
                                            <a href="{{  route('marketplace.search').!empty(setting_item("marketplace_professionalAssistance_link")) ? setting_item("marketplace_professionalAssistance_link") : __('?category=trainings')  }}"
                                               class="theme-btn btn-style-two bg-blue">{{ __('View ') }}{{ /*!empty(setting_item("marketplace_professionalAssistance_title")) ? setting_item("marketplace_professionalAssistance_title") : */__('Professional Resources') }}</a>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </section>
        @endif


        @if(!isset($_GET['category']))
            <section class="announcement-page-title marketplace-badge">
                <div class="auto-container">
                    <div class="title-outer">
                        <h1>{{ __('All Announcements') }}
                    </div>
                </div>
            </section>
        @endif
        <!--End Page Title-->

        <section class="ls-section" style="{{ isset($_GET['category']) ?: "padding-top: 10px" }}">
            <div class="auto-container">
                <div class="filters-backdrop"></div>


                <div class="ls-cotainer">
                    <!-- Filters Column -->
                    <div class="filters-column hide-left">
                        <div class="inner-column">
                            <div class="filters-outer">
                                <button type="button" class="theme-btn close-filters">X</button>
                                @include("Marketplace::frontend.search.filter")
                            </div>
                        </div>
                    </div>

                    <!-- Content Column -->
                    <div class="content-column col-lg-12">
                        <div class="ls-outer">
                            @if(!empty($rows) && count($rows) > 0)
                                <!-- ls Switcher -->
                                <div class="ls-switcher">
                                    <div class="showing-result show-filters">
                                        <button type="button" class="theme-btn toggle-filters"><span
                                                class="icon icon-filter"></span> {{ __("Filter") }}</button>
                                        <div class="text">{{ __("Showing") }} <strong>{{ $rows->firstItem() }}
                                                -{{ $rows->lastItem() }}</strong> {{ __("of") }}
                                            <strong>{{ $rows->total() }}</strong> {{ __("results") }}</div>
                                    </div>
                                    <div class="sort-by">
                                        <form class="bc-form-order" method="get"
                                              action="{{ route('marketplace.search') }}">
                                            @include("Marketplace::frontend.search.order-sort")
                                        </form>
                                    </div>
                                </div>

                                <div class="row">
                                    @foreach($rows as $row)
                                        <div class="marketplace-list-block col-lg-6 col-md-12 col-sm-12">
                                            @include('Marketplace::frontend.search.loop')
                                        </div>
                                    @endforeach
                                </div>


                                <!-- Listing pagination -->
                                <div class="bravo-pagination">
                                    {{$rows->appends(request()->query())->links()}}
                                    @if($rows->total() > 0)
                                        <span
                                            class="count-string">{{ __("Showing :from - :to of :total",["from"=>$rows->firstItem(),"to"=>$rows->lastItem(),"total"=>$rows->total()]) }}</span>
                                    @endif
                                </div>
                            @else
                                <div class="ls-switcher">
                                    <div class="showing-result show-filters">
                                        <button type="button" class="theme-btn toggle-filters"><span
                                                class="icon icon-filter"></span> {{ __("Filter") }}</button>
                                    </div>
                                    <div class="sort-by">
                                        <form class="bc-form-order" method="get"
                                              action="{{ route('marketplace.search') }}">
                                            @include("Marketplace::frontend.search.order-sort")
                                        </form>
                                    </div>
                                </div>
                                <div class="job-results-not-found mb-5 text-center">
                                    <h3>{{ __("No results found") }}</h3>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endsection

        @section('footer')
            <script type="text/javascript" src="{{ asset("libs/ion_rangeslider/js/ion.rangeSlider.min.js") }}"></script>
{{--            <script type="text/javascript"--}}
{{--                    src="{{ asset('module/Marketplace/js/Marketplace.js?_ver='.config('app.version')) }}"></script>--}}

            <script>
                const categoryBlockСheckboxes = document.querySelectorAll('#categoryBlock input[type="checkbox"]');
                const locationStatusСheckboxes = document.querySelectorAll('#locationStatus input[type="checkbox"]');
                const locationInput = document.querySelector('.smart-search-location');

                $(document).ready(function () {
                    const isLocationChecked = Array.from(locationStatusСheckboxes).find(checkbox => checkbox.checked);

                    if (isLocationChecked) {
                        if (isLocationChecked.value === 'online') {
                            $('#locationContainer').hide()
                        }
                    }
                })

                categoryBlockСheckboxes.forEach(function (categoryBlockcheckbox) {
                    categoryBlockcheckbox.addEventListener('change', function () {
                        if (this.checked) {
                            categoryBlockСheckboxes.forEach(function (otherCheckbox) {
                                if (otherCheckbox !== categoryBlockcheckbox) {
                                    otherCheckbox.checked = false;
                                }
                            });
                        }
                    });
                });

                locationStatusСheckboxes.forEach(function (locationStatusСheckbox) {
                    locationStatusСheckbox.addEventListener('change', function () {
                        if (this.checked) {
                            locationStatusСheckboxes.forEach(function (otherCheckbox) {
                                if (otherCheckbox !== locationStatusСheckbox) {
                                    otherCheckbox.checked = false;
                                }
                            });
                        }
                        if (this.value === 'online' && this.checked) {
                            $('#locationContainer').hide()
                        } else {
                            $('#locationContainer').show()
                        }
                    });
                });
            </script>
        @endsection
