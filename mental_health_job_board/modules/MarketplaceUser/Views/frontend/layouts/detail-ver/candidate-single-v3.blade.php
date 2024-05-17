<div class="bravo-marketplace_users">
@php
    $title_page = setting_item_with_lang("marketplace_user_page_list_title");
    if(!empty($custom_title_page)){
        $title_page = $custom_title_page;
    }
    $translation = $row->translateOrOrigin(app()->getLocale());
@endphp

<!-- MarketplaceUser Detail Section -->
    <section class="marketplace_user-detail-section style-three">
        <div class="upper-box">
            <div class="auto-container">
                <!-- MarketplaceUser block Six -->
                <div class="marketplace_user-block-six">
                    <div class="inner-box">
                        <figure class="image"><img src="{{$row->user->getAvatarUrl()}}" alt=""></figure>
                        <h4 class="name"><a href="#">{{$row->user->getDisplayName()}}</a></h4>
                        <span class="designation">{{$row->title}}</span>
                        <div class="content">
                            @php
                                $categories = $row->getCategory();
                            @endphp
                            <ul class="post-tags">
                                @if(!empty($row->categories))
                                    @foreach($row->categories as $oneCategory)
                                        <li><a target="_blank" href="{{ route('marketplace_user.index', ['category' => $oneCategory->id]) }}">{{$oneCategory->name}}</a></li>
                                    @endforeach
                                @endif
                            </ul>

                            <ul class="marketplace_user-info">
                                @if($row->location_id)
                                    <li><span class="icon flaticon-map-locator"></span> {{$row->location->name}}</li>
                                @endif
                                @if($row->expected_salary)
                                    <li><span class="icon flaticon-money"></span> {{$row->expected_salary}} {{currency_symbol()}}  / {{$row->salary_type}}</li>
                                @endif
                                <li><span class="icon flaticon-clock"></span> {{__('Member Since')}} {{date('M d, Y', strtotime($row->user->created_at))}}</li>
                            </ul>

                            <div class="btn-box">
                                @php
                                    $url = '';
                                    if(!empty($cv)){
                                        $file = (new \Modules\Media\Models\MediaFile())->findById($cv->file_id);
                                        $url  = asset('uploads/'.$file['file_path']);
                                    }
                                @endphp
                                @if($url)
                                    @if(setting_item('marketplace_user_download_cv_required_login') && !auth()->check())
                                        <a href="#" class="theme-btn btn-style-one bc-call-modal login">{{__('Download CV')}}</a>
                                    @else
                                        <a href="{{$url}}" class="theme-btn btn-style-one" target="_blank" download>{{__('Download CV')}}</a>
                                    @endif
                                @endif
                                @if(is_employer())
                                    <button class="bookmark-btn @if($row->wishlist) active @endif service-wishlist" data-id="{{$row->id}}" data-type="{{$row->type}}"><span class="flaticon-bookmark"></span></button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="marketplace_user-detail-outer">
            <div class="auto-container">
                <div class="row">
                    <div class="sidebar-column col-lg-4 col-md-12 col-sm-12">
                        @include('MarketplaceUser::frontend.layouts.details.marketplace_user-sidebar')
                    </div>
                    <div class="content-column col-lg-8 col-md-12 col-sm-12 order-2">
                        @include('MarketplaceUser::frontend.layouts.details.marketplace_user-detail')
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End marketplace_user Detail Section -->
</div>
