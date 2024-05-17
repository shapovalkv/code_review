<x-app-layout>

    <div class="row g-0">
        <div class="col-lg-16">
            <div class="card mb-3">
                <div class="card-header">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Report information</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-0">
        <div class="col-lg-16">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0 text-center">Report information</h5>
                </div>
                <div class="row">
                    <div class="card-body px-xxl-0 pt-4">
                        <div class="row g-0">
                            <div
                                class="col-xxl-3 col-md-6 px-3 text-center border-end-md border-bottom border-bottom-xxl-0 pb-3 p-xxl-0 ps-md-0">
                                <div class="icon-circle icon-circle-primary"><span
                                        class="fs-2 fas fa-search text-primary"></span></div>
                                <h4 class="mb-1 font-sans-serif"><span class="text-700 mx-2"
                                                                       data-countup='{"endValue":""}'>{{ $report->googleSearchReports->count() }}</span><span
                                        class="fw-normal text-600">GOOGLE SEARCH</span></h4>
                            </div>
                            <div
                                class="col-xxl-3 col-md-6 px-3 text-center border-end-xxl border-bottom border-bottom-xxl-0 pb-3 pt-4 pt-md-0 pe-md-0 p-xxl-0">
                                <div class="icon-circle icon-circle-info"><span
                                        class="fs-2 fas fa-image text-info"></span></div>
                                <h4 class="mb-1 font-sans-serif"><span class="text-700 mx-2"
                                                                       data-countup='{"endValue":""}'>{{ $report->googleImagesReports->count() }}</span><span
                                        class="fw-normal text-600">GOOGLE IMAGES</span></h4>
                            </div>
                            <div
                                class="col-xxl-3 col-md-6 px-3 text-center border-end-md border-bottom border-bottom-md-0 pb-3 pt-4 p-xxl-0 pb-md-0 ps-md-0">
                                <div class="icon-circle icon-circle-success"><span
                                        class="fs-2 fas fa-icons text-success"></span></div>
                                <h4 class="mb-1 font-sans-serif"><span class="text-700 mx-2"
                                                                       data-countup='{"endValue":""}'>{{ $report->socialMediaReports->count() }}</span><span
                                        class="fw-normal text-600">SOCIAL MEDIA</span></h4>
                            </div>
                            <div
                                class="col-xxl-3 col-md-6 px-3 text-center border-end-md border-bottom border-bottom-md-0 pb-3 pt-4 p-xxl-0 pb-md-0 ps-md-0">
                                <div class="icon-circle icon-circle-warning"><span
                                        class="fs-2 fas fa-thumbtack text-warning"></span></div>
                                <h4 class="mb-1 font-sans-serif"><span class="text-700 mx-2"
                                                                       data-countup='{"endValue":""}'>{{ $report->atSourceReports->count() }}</span><span
                                        class="fw-normal text-600">AT-SOURCE</span></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if(!$google_searches->isEmpty())
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Google Search</h5>
                    </div>
                    <div>
                        <a class="btn btn-falcon-default btn-sm"
                           href="{{ route('project.exportGoogleSearch', ['projectReport' => $report->id]) }}">
                            <span class="fas fa-plus me-1" data-fa-transform="shrink-3"></span>
                            Export Google Search
                        </a>
                    </div>
                </div>
                <div class="table-responsive scrollbar">
                    <div class="table-responsive scrollbar" id="google_searches">
                        <table class="table">
                            <tbody>
                            @foreach($google_searches as $google_search)
                                <tr>
                                    <td>{{ $google_search->content }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="card-footer d-flex align-items-center justify-content-center">
                            {!! $google_searches->links('pagination::bootstrap-5') !!}
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if(!$google_images->isEmpty())
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Google Images</h5>
                    </div>
                    <div>
                        <a class="btn btn-falcon-default btn-sm"
                           href="{{ route('project.exportGoogleImage', ['projectReport' => $report->id]) }}">
                            <span class="fas fa-plus me-1" data-fa-transform="shrink-3"></span>Export
                            Google Images
                        </a>
                    </div>
                </div>
                <div class="table-responsive scrollbar">
                    <div class="table-responsive scrollbar" id="google_images">
                        <table class="table">
                            <tbody>
                            @foreach($google_images as $google_image)
                                <tr>
                                    <td>{{ $google_image->content }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-center">
                        {!! $google_images->links('pagination::bootstrap-4') !!}
                    </div>
                </div>
            </div>
        @endif
        @if(!$social_medias->isEmpty())
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Social Media</h5>
                    </div>
                    <div>
                        <a class="btn btn-falcon-default btn-sm"
                           href="{{ route('project.exportSocialMedia', ['projectReport' => $report->id]) }}">
                            <span class="fas fa-plus me-1" data-fa-transform="shrink-3"></span>Export
                            Social Media
                        </a>
                    </div>
                </div>
                <div class="table-responsive scrollbar">
                    <div class="table-responsive scrollbar" id="social_medias">
                        <table class="table">
                            <tbody>
                            @foreach($social_medias as $social_media)
                                <tr>
                                    <td>{{ $social_media->content }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-center">
                        {!! $social_medias->links('pagination::bootstrap-5') !!}
                    </div>
                </div>
            </div>
        @endif
        @if(!$at_sources->isEmpty())
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">At-Source</h5>
                    </div>
                    <div>
                        <a class="btn btn-falcon-default btn-sm"
                           href="{{ route('project.exportAtResource', ['projectReport' => $report->id]) }}">
                            <span class="fas fa-plus me-1" data-fa-transform="shrink-3"></span>Export
                            At-Source
                        </a>
                    </div>
                </div>
                <div class="table-responsive scrollbar">
                    <div class="table-responsive scrollbar" id="at_sources">
                        <table class="table">
                            <tbody>
                            @foreach($at_sources as $at_source)
                                <tr >
                                    <td>{{ $at_source->content }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-center">
                        {!! $at_sources->links('pagination::bootstrap-5') !!}
                    </div>
                </div>
            </div>
        @endif
    </div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.has('google_searches')) {
                    const targetItem = document.getElementById('google_searches');
                    if (targetItem) {
                        targetItem.scrollIntoView({behavior: 'smooth'});
                    }
                } else if(urlParams.has('google_images')) {
                    const targetItem = document.getElementById('google_images');
                    if (targetItem) {
                        targetItem.scrollIntoView({behavior: 'smooth'});
                    }
                }else if(urlParams.has('social_medias')) {
                    const targetItem = document.getElementById('social_medias');
                    if (targetItem) {
                        targetItem.scrollIntoView({behavior: 'smooth'});
                    }
                }else if(urlParams.has('at_sources')) {
                    const targetItem = document.getElementById('at_sources');
                    if (targetItem) {
                        targetItem.scrollIntoView({behavior: 'smooth'});
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>
