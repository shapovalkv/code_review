<!--Page Title-->
<section class="page-title">
    <div class="auto-container">
        <div class="title-outer">
            <h1>{{ setting_item_with_lang('company_page_search_title') ?? __("Companies") }}</h1>
<!--            <ul class="page-breadcrumb">
                <li><a href="{{ url("/") }}">{{__("Home")}}</a></li>
                <li>{{__("Companies")}}</li>
            </ul>-->
        </div>
    </div>
</section>
<!--End Page Title-->
<!-- Listing Section -->
<section class="ls-section">
    <div class="auto-container">
        <div class="filters-backdrop"></div>
        <div class="ls-cotainer">

            <!-- Filters Column -->
            <div class="filters-column hide-left">
                <div class="inner-column">
                        <div class="filters-column">
                            @include('Company::frontend.layouts.sidebars.category-sidebar')
                        </div>
                </div>

            </div>
            <!-- Content Column -->
            <div class="content-column col-lg-12">
                <div class="ls-outer">
                    <button type="button" class="theme-btn btn-style-two toggle-filters">{{__("Show Filters")}}</button>
                    <!-- ls Switcher -->
                    <div class="ls-switcher">
                        <div class="showing-result show-filters">
                            <button type="button" class="theme-btn toggle-filters"><span
                                    class="icon icon-filter"></span> {{ __('Filter') }}</button>
                            <div class="text">{{ __("Showing") }} <strong>{{ $rows->firstItem() }}-{{ $rows->lastItem() }}</strong> {{ __("of") }}
                                <strong>{{ $rows->total() }}</strong> {{ __("results") }}</div>
                        </div>
                        @include('Company::frontend.layouts.search.company-sort')
                    </div>
                    <!-- Block Block -->

                    <div class="row">

                        @if($rows->count() > 0)
                            @foreach($rows as $row)
                                <div class="company-list-block col-lg-6 col-md-12 col-sm-12">
                                    @include('Company::frontend.layouts.loop.company-item-1')
                                </div>
                            @endforeach
                        @else
                            <div class="col-12">
                                <h3 class="text-center">{{ __("No company results found") }}</h3>
                            </div>

                        @endif
                    </div>
                    <div class="bravo-pagination">
                        {{$rows->appends(request()->query())->links()}}
                        @if($rows->total() > 0)
                            <span
                                class="count-string">{{ __("Showing :from - :to of :total",["from"=>$rows->firstItem(),"to"=>$rows->lastItem(),"total"=>$rows->total()]) }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--End Listing Page Section -->
