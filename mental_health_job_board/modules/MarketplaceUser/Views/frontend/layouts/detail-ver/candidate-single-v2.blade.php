<div class="bravo-marketplace_users">
@php
    $title_page = (!empty($custom_title_page)) ? $custom_title_page : setting_item_with_lang("marketplace_user_page_list_title");
    $translation = $row->translateOrOrigin(app()->getLocale());
@endphp
    <section class="marketplace_user-detail-section style-two">
        <div class="marketplace_user-detail-outer">
            <div class="auto-container">
                <div class="row">
                    <div class="content-column col-lg-8 col-md-12 col-sm-12">
                        <div class="marketplace_user-block-five">
                            <div class="inner-box">
                                @include('MarketplaceUser::frontend.layouts.details.marketplace_user-block')
                            </div>
                        </div>
                        @include('MarketplaceUser::frontend.layouts.details.marketplace_user-detail')
                    </div>
                    <div class="sidebar-column col-lg-4 col-md-12 col-sm-12">
                        <div class="sidebar">
                            @include('MarketplaceUser::frontend.layouts.details.marketplace_user-btn-box')
                            @include('MarketplaceUser::frontend.layouts.details.marketplace_user-sidebar')
                        </div>
                    </div>
                </div>
            </div>
            {{--<!-- Upper Box -->
            @include('MarketplaceUser::frontend.layouts.details.marketplace_user-header')
            <div class="marketplace_user-detail-outer">
                <div class="auto-container">
                    <div class="row">
                        @include('MarketplaceUser::frontend.layouts.details.marketplace_user-detail')

                        @include('MarketplaceUser::frontend.layouts.details.marketplace_user-sidebar')
                    </div>
                </div>
            </div>--}}
        </div>
    </section>
</div>
