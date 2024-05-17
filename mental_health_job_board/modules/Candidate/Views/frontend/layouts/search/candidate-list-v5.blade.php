<section class="page-title">
    <div class="auto-container">
        <div class="title-outer">
            <h1>{{ setting_item_with_lang('candidate_page_search_title') ?? __("Find Candidates") }}</h1>
        </div>
    </div>
</section>

<section class="ls-section">
    <div class="auto-container">
        <div class="filters-backdrop"></div>
        <div class="ls-cotainer">
            <!-- Filters Column -->
            <div class="filters-column hide-left">
                <div class="inner-column">
                    <div class="filters-outer">
                        <button type="button" class="theme-btn close-filters">X</button>
                        @include("Candidate::frontend.layouts.sidebars.category-sidebar")
                    </div>
                </div>
            </div>

            {{--        <!-- Map Column -->--}}
            {{--        <div class="map-column width-50">--}}
            {{--            <div id="bravo_results_map" class="results_map_inner" style="height: 100%"></div>--}}
            {{--        </div>--}}

            <!-- Content Column -->
            <div class="content-column col-lg-12">
                <div class="ls-outer">
                        <div class="ls-switcher">
                            <div class="showing-result show-filters">
                                <button type="button" class="theme-btn toggle-filters"><span class="icon icon-filter"></span> {{ __('Filter') }}</button>
                                <div class="text">{{ __("Showing") }} <strong>{{ $rows->firstItem() }}-{{ $rows->lastItem() }}</strong> {{ __("of") }} <strong>{{ $rows->total() }}</strong> {{ __("results") }}</div>
                            </div>
                            <form class="bc-form-order" method="get">
                                <div class="sort-by">
                                    @if(request()->get('_layout'))
                                        <input type="hidden" name="_layout" value="{{$layout}}"/>
                                    @endif
                                    <select class="chosen-select" name="orderby" onchange="this.form.submit()">
                                        <option value="">{{__('Sort by (Default)')}}</option>
                                        <option value="new"
                                                @if(request()->get('orderby') == 'new') selected @endif>{{__('Newest')}}</option>
                                        <option value="old"
                                                @if(request()->get('orderby') == 'old') selected @endif>{{__('Oldest')}}</option>
                                        <option value="name_high"
                                                @if(request()->get('orderby') == 'name_high') selected @endif>{{__('Name [a->z]')}}</option>
                                        <option value="name_low"
                                                @if(request()->get('orderby') == 'name_low') selected @endif>{{__('Name [z->a]')}}</option>
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
                            @if($rows->count() > 0)
                            @foreach($rows as $row)
                                <div class="candidate-block-three col-lg-6 col-md-12 col-sm-12">
                                    @include("Candidate::frontend.layouts.loop.item-v1")
                                </div>
                            @endforeach
                            @else
                                <div class="col-12">
                                    <h3 class="text-center">{{ __("No candidate results found") }}</h3>
                                </div>
                            @endif
                            @include('Job::frontend.layouts.details.invite-job-popup')
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
