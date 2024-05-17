<form method="get" action="{{ (!empty($category) || !empty($location)) ? route('marketplace.search') : request()->fullUrl() }}" >
    <div class="filter-block">
        <h4>{{ __('Search by Keywords') }}</h4>
        <div class="form-group">
            <input type="text" name="s" value="{{ request()->input('s') }}" placeholder="{{ __("Announcement title...") }}">
            <span class="icon flaticon-search-3"></span>
        </div>
    </div>
    @if($list_categories)
        <div class="switchbox-outer" id="categoryBlock">
            <h4>{{ __('Categories') }}</h4>
            <ul class="switchbox">
                @foreach($list_categories as $category)
                    @php
                        $translation = $category->translateOrOrigin(app()->getLocale());
                    @endphp
                    <li>
                        <label class="switch">
                            <input type="radio" name="category" value="{{ $category->slug  }}" @if(!empty(request()->get('category')) && $category->slug == request()->get('category')) checked @endif>
                            <span class="slider round"></span>
                            <span class="title">{{ $translation->name }}</span>
                        </label>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="switchbox-outer" id="locationStatus">
        <h4>{{ __('Training Location') }}</h4>
        <ul class="switchbox">
            <li>
                    <label class="switch">
                        <input type="checkbox" name="announcement_status[]" value="online" @if(!empty(request()->get('announcement_status')) && in_array('online', request()->get('announcement_status'))) checked @endif>
                        <span class="slider round"></span>
                        <span class="title">{{ __('Online') }}</span>
                    </label>
                </li>
            <li>
                <label class="switch">

                    <input type="checkbox" name="announcement_status[]" value="in_person" @if(!empty(request()->get('announcement_status')) && in_array('in_person', request()->get('announcement_status'))) checked @endif>
                    <span class="slider round"></span>
                    <span class="title">{{ __('In Person') }}</span>
                </label>
            </li>
        </ul>
    </div>
    <!-- Filter Block -->
    @if($list_locations)
        <div class="filter-block" id='locationContainer'>
            <h4>{{ __('Location') }}</h4>
                    <?php
                    $location_name = "";
                    $list_json = [];
                    $location_id = request()->get('location');
                    $traverse = function ($locations, $prefix = '') use (&$traverse, &$list_json, &$location_name, $location_id) {
                        foreach ($locations as $location) {
                            $translate = $location->translateOrOrigin(app()->getLocale());
                            if ($location_id == $location->id) {
                                $location_name = $translate->name;
                            }
                            $list_json[] = [
                                'id'    => $location->id,
                                'title' => $prefix.' '.$translate->name,
                            ];
                            $traverse($location->children, $prefix.'-');
                        }
                    };
                    $traverse($list_locations);
                    ?>
                <div class="form-group smart-search">
                    <input type="text" class="smart-search-location parent_text form-control" placeholder="{{__("Start Typing")}}" value="{{ $location_name }}" data-onLoad="{{__("Loading...")}}"
                           data-default="{{ json_encode($list_json) }}">
                    <input type="hidden" class="child_id" name="location" value="{{ $location_id }}">
                    <span class="icon flaticon-map-locator"></span>
                </div>
        </div>
        <div class="filter-block">
            <h4><span id="amount">{{ __('Radius') }}: Start at {{ request()->get('radius') ?? 25}} Miles</span></h4>
            <div class="range-slider-one radius">
                <input type="hidden" name="radius" value="{{ request()->get('radius') ?? 25 }}">
                <div id="radius-slider"></div>
            </div>
        </div>
    @endif
    <div class="wrapper-submit flex-middle col-xs-12 col-md-12">
        @if(isset($_GET['_layout']))
            <input type="hidden" name="_layout" value="{{ $_GET['_layout'] }}">
        @endif
        <button type="submit" class="theme-btn btn-style-ten">{{ __("Find Announcements") }}</button>
    </div>
</form>
@include('layouts.saveSearch')
