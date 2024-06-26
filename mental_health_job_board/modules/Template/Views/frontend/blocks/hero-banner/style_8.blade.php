<!-- Banner Section-->
<section class="banner-section-eight">
    @if(!empty($banner_image))
        <div class="image-outer">
            <figure class="image" >
                <img src="{{ $banner_image_url }}" alt="banner image">
            </figure>
        </div>
    @endif
    <div class="auto-container">
        <div class="row">
            <div class="content-column col-xl-6 col-lg-12 col-md-12 col-sm-12">
                <div class="inner-column">
                    <div class="title-box wow fadeInUp" data-wow-delay="500ms">
                        <h3>{!! @clean($title) !!}</h3>
                        <div class="text">{{ $sub_title }}</div>
                    </div>

                    <!-- Job Search Form -->
                    <div class="job-search-form">
                        <form method="get" action="{{ route('job.search') }}">
                            <div class="row">
                                <div class="form-group col-lg-4 col-md-12 col-sm-12">
                                    <span class="icon flaticon-search-1"></span>
                                    <input type="text" name="s" placeholder="{{ __("Job title...") }}">
                                </div>
                                <!-- Form Group -->
                                @if($location_style == 'autocomplete')
                                    @php
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
                                    @endphp
                                    <div class="form-group col-lg-3 col-md-12 col-sm-12 location smart-search">
                                        <input type="text" class="smart-search-location parent_text form-control" placeholder="{{__("All Cities")}}" value="{{ $location_name }}" data-onLoad="{{__("Loading...")}}"
                                               data-default="{{ json_encode($list_json) }}">
                                        <input type="hidden" class="child_id" name="location" value="{{ $location_id }}">
                                        <span class="icon flaticon-map-locator"></span>
                                    </div>
                                @else
                                    <div class="form-group col-lg-3 col-md-12 col-sm-12 location bc-select-has-delete">
                                        <span class="icon flaticon-map-locator"></span>
                                        <select class="chosen-select" name="location">
                                            <option value="">{{ __("All Cities") }}</option>
                                            @php
                                                $traverse = function ($locations, $prefix = '') use (&$traverse) {
                                                    foreach ($locations as $location) {
                                                        $translate = $location->translateOrOrigin(app()->getLocale());
                                                        printf("<option value='%s'>%s</option>", $location->id, $prefix . ' ' . $translate->name);
                                                        $traverse($location->children, $prefix . '-');
                                                    }
                                                };
                                                $traverse($list_locations);
                                            @endphp
                                        </select>
                                    </div>
                            @endif
                                <div class="form-group col-lg-3 col-md-12 col-sm-12 category banner-category">
                                    <span class="icon flaticon-briefcase"></span>
                                    <select class="bc-select2" name="category">
                                        <option value="">{{ __('All Categories')}}</option>
                                        @foreach($list_categories as $cat)
                                            @php
                                                $translate = $cat->translateOrOrigin(app()->getLocale());
                                            @endphp
                                            <option value="{{ $cat->id }}" @if($cat->id == request()->get('category')) selected @endif  >{{ $translate->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <input type="hidden" name="radius" value="{{ 100 }}">

                                <!-- Form Group -->
                                <div class="form-group col-lg-2 col-md-12 col-sm-12 btn-box">
                                    <button type="submit" class="theme-btn btn-style-one"><span class="btn-title">{{ __("Find Jobs") }}</span></button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="bottom-box wow fadeInUp">
                        <div class="count-employers">
                            @if($banner_image_2)
                                <img src="{{ \Modules\Media\Helpers\FileHelper::url($banner_image_2, 'full') }}" alt="img">
                            @endif
                        </div>
                        @if(!empty($upload_cv_url))
                            <a href="{{ $upload_cv_url }}" class="upload-cv"><span class="icon flaticon-file"></span> {{ __("Upload your CV") }}</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Banner Section-->
