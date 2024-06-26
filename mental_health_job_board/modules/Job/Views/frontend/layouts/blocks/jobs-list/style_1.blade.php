<!-- Job Section -->
<section class="job-section">
    <div class="auto-container">
        <div class="sec-title text-center">
            <h2>{{ $title }}</h2>
            <div class="text">{{ $sub_title }}</div>
        </div>

        <div class="row wow fadeInUp">
            @foreach($rows as $row)
                <div class="job-block col-lg-6 col-md-12 col-sm-12">
                    @include("Job::frontend.layouts.loop.job-item-1")
                </div>
            @endforeach
        </div>
        @if(!empty($load_more_url))
            <div class="btn-box">
                <a href="{{ $load_more_url }}" class="theme-btn btn-style-ten text-white"><span class="btn-title">{{ __("Load more Listings") }}</span></a>
            </div>
        @endif
    </div>
</section>
<!-- End Job Section -->
