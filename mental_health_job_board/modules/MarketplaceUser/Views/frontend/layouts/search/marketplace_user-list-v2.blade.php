<section class="page-title">
    <div class="auto-container">
        <div class="title-outer">
            <h1>{{ setting_item_with_lang('marketplace_user_page_search_title') ?? __("Find MarketplaceUsers") }}</h1>
            <ul class="page-breadcrumb">
                <li><a href="{{ home_url() }}">{{ __("Home") }}</a></li>
                <li>{{ __("MarketplaceUsers") }}</li>
            </ul>
        </div>
    </div>
</section>
<!--End Page Title-->

<!-- Listing Section -->
<section class="ls-section">
    <div class="auto-container">
        <div class="filters-backdrop"></div>

        <div class="row">

            <!-- Filters Column -->
            <div class="filters-column col-lg-4 col-md-12 col-sm-12">
                <div class="inner-column">
                    <div class="filters-outer">
                        <button type="button" class="theme-btn close-filters">X</button>
                        @include("MarketplaceUser::frontend.layouts.sidebars.category-sidebar")
                    </div>

                @php
                    $marketplace_user_sidebar_cta = setting_item_with_lang('marketplace_user_sidebar_cta',request()->query('lang'), $settings['marketplace_user_sidebar_cta'] ?? false);
                    if(!empty($marketplace_user_sidebar_cta)) $marketplace_user_sidebar_cta = json_decode($marketplace_user_sidebar_cta);

                @endphp
{{--                @if(!empty($marketplace_user_sidebar_cta->title))--}}
{{--                    <!-- Call To Action -->--}}
{{--                        <div class="call-to-action-four">--}}
{{--                            <h5>{{ $marketplace_user_sidebar_cta->title ?? '' }}</h5>--}}
{{--                            <p>{{ $marketplace_user_sidebar_cta->desc ?? '' }}</p>--}}
{{--                            @if(!empty($marketplace_user_sidebar_cta->button->url))--}}
{{--                                <a href="{{ ($marketplace_user_sidebar_cta->button->url) }}" target="_{{ $marketplace_user_sidebar_cta->button->target ?? "self" }}" class="theme-btn btn-style-one bg-blue">--}}
{{--                                    <span class="btn-title">{{ $marketplace_user_sidebar_cta->button->name ?? __("Start Recruiting Now") }}</span>--}}
{{--                                </a>--}}
{{--                            @endif--}}
{{--                            <div class="image" style="background-image: url({{ !empty($marketplace_user_sidebar_cta->image) ? \Modules\Media\Helpers\FileHelper::url($marketplace_user_sidebar_cta->image, 'full') : '' }});"></div>--}}
{{--                        </div>--}}
{{--                        <!-- End Call To Action -->--}}
{{--                    @endif--}}
                </div>
            </div>

            <!-- Content Column -->
            <div class="content-column col-lg-8 col-md-12 col-sm-12">
                <div class="ls-outer">
                    <button type="button" class="theme-btn btn-style-two toggle-filters">{{ __("Show Filters") }}</button>

                @if(!empty($rows) && count($rows) > 0)
                    <!-- ls Switcher -->
                        <div class="ls-switcher">
                            <div class="showing-result">
                                <div class="text">{{ __("Showing") }} <strong>{{ $rows->firstItem() }}-{{ $rows->lastItem() }}</strong> {{ __("of") }} <strong>{{ $rows->total() }}</strong> {{ __("results") }}</div>
                            </div>
                            <form class="bc-form-order" method="get">
                            <div class="sort-by">
                                @if(request()->get('_layout'))
                                    <input type="hidden" name="_layout" value="{{$layout}}" />
                                @endif
                                <select class="chosen-select" name="orderby" onchange="this.form.submit()">
                                    <option value="">{{__('Sort by (Default)')}}</option>
                                    <option value="new" @if(request()->get('orderby') == 'new') selected @endif>{{__('Newest')}}</option>
                                    <option value="old" @if(request()->get('orderby') == 'old') selected @endif>{{__('Oldest')}}</option>
                                    <option value="name_high" @if(request()->get('orderby') == 'name_high') selected @endif>{{__('Name [a->z]')}}</option>
                                    <option value="name_low" @if(request()->get('orderby') == 'name_low') selected @endif>{{__('Name [z->a]')}}</option>
                                </select>

                                <select class="chosen-select" name="limit" onchange="this.form.submit()">
                                    <option value="10"
                                            @if(request()->get('limit') == 10 || (empty(request()->get('limit')) && $list_search == 10)) selected @endif >{{ __("Show 10") }}</option>
                                    <option value="20"
                                            @if(request()->get('limit') == 20 || (empty(request()->get('limit')) && $list_search == 20)) selected @endif >{{ __("Show 20") }}</option>
                                    <option value="30"
                                            @if(request()->get('limit') == 30 || (empty(request()->get('limit')) && $list_search == 30)) selected @endif >{{ __("Show 30") }}</option>
                                    <option value="40"
                                            @if(request()->get('limit') == 40 || (empty(request()->get('limit')) && $list_search == 40)) selected @endif >{{ __("Show 40") }}</option>
                                    <option value="50"
                                            @if(request()->get('limit') == 50 || (empty(request()->get('limit')) && $list_search == 50)) selected @endif >{{ __("Show 50") }}</option>
                                    <option value="60"
                                            @if(request()->get('limit') == 60 || (empty(request()->get('limit')) && $list_search == 60)) selected @endif >{{ __("Show 60") }}</option>
                                </select>
                            </div>
                            </form>
                        </div>

                        <div class="row">
                            @foreach($rows as $row)
                                <div class="marketplace_user-block-four col-lg-6 col-md-6 col-sm-12">
                                    @include("MarketplaceUser::frontend.layouts.loop.item-v2")
                                </div>
                            @endforeach
                        </div>
                        @include('Job::frontend.layouts.details.invite-job-popup')

                        <!-- Listing pagination -->
                        <div class="ls-pagination">
                            {{$rows->appends(request()->query())->links()}}
                        </div>
                    @else
                        <div class="marketplace_user-results-not-found">
                            <h3>{{ __("No marketplace_user results found") }}</h3>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
