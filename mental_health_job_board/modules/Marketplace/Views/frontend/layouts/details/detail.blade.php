<section class="job-detail-section">
    <!-- Upper Box -->
    <div class="upper-box">
        <div class="auto-container">
            <!-- Job Block -->
            <div class="job-block-seven marketplace-block">
                <div class="inner-box">
                    @include("Marketplace::frontend.layouts.details.upper-box")
                </div>
            </div>
        </div>
    </div>

    <div class="job-detail-outer">
        <div class="auto-container">
            <div class="row">
                <div class="content-column marketplace-post-content col-lg-8 col-md-12 col-sm-12">

                    @if(!empty($translation->content))
                        <h4 class="mb-4">{{ __("Announcement Description") }}</h4>
                        <div class="job-detail">
                            {!! $translation->content !!}
                            <br>
                        </div>
                    @endif

                    @include("Marketplace::frontend.layouts.details.gallery")

                    @include("Marketplace::frontend.layouts.details.video")

                    @include("Marketplace::frontend.layouts.details.social-share")

                    @include("Marketplace::frontend.layouts.details.related")

                </div>

                <div class="sidebar-column col-lg-4 col-md-12 col-sm-12">
                    <aside class="sidebar">
                        <div class="sidebar-widget">

                            @include("Marketplace::frontend.layouts.details.overview")

                            <?php $announcement_status = json_decode($row->announcement_status, true); ?>
                            @if(!empty($announcement_status) && !key_exists('online', $announcement_status) && !empty($row->location))
                                <h4 class="widget-title">{{ __("Location") }}</h4>
                                <div class="widget-content">
                                    @include("Marketplace::frontend.layouts.details.location")
                                </div>
                            @endif
                        </div>

{{--                        @include("Marketplace::frontend.layouts.details.company")--}}

                    </aside>
                </div>
            </div>
        </div>
    </div>
</section>
