<x-guest-layout>
    <div class="pt-5"></div>
    <div class="pt-5 mb-2"></div>
    <main class="main" id="top">
        @include('components.home.header')
        <div class="container" data-layout="container">
            <div class="content">
                @include('parts.flash-message')
                <div class="row g-0">
                    <div class="col-lg-12 pe-lg-2">
                        <div class="row g-0">
                            <div class="col-lg-8 pe-lg-2">
                                <div class="card mb-3 mb-lg-0">
                                    <div class="card-header bg-body-tertiary">
                                        <h5 class="mb-0 text-center">{{ $resource->title }}</h5>
                                    </div>
                                    <div class="card-body">
                                        {!! $resource->content !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 ps-lg-2">
                                <div class="sticky-sidebar">
                                    <div class="card mb-3 fs--1">
                                        <div class="card-body">
                                            <div class="hoverbox h-md-100"><img
                                                    class="h-100 w-100 object-fit-cover"
                                                    src="{{ $resource->featured_image_url }}"
                                                    alt="{{ $resource->featured_image_url }}"/>
                                                <div
                                                    class="hoverbox-content flex-center pe-none bg-holder overlay overlay-2"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mb-3 mb-lg-0">
                                        <div class="card-header bg-light">
                                            <h5 class="mb-0">Resources you may like</h5>
                                        </div>
                                        <div class="card-body fs--1">
                                            @foreach($resources as $propose_resource)
                                                <article class="card mb-3 overflow-hidden">
                                                    <div class="card-body p-0">
                                                        <div class="row g-0">
                                                            <div class="col-md-4 col-lg-3">
                                                                <div class="hoverbox h-md-100">
                                                                    <a href="{{ route('pages.resources.single', ['resource' => $propose_resource]) }}">
                                                                        <img class="h-100 w-100 object-fit-cover"
                                                                             src="{{ $propose_resource->featured_image_url }}"
                                                                             alt="{{ $propose_resource->featured_image_url }}"/>
                                                                        <div
                                                                            class="hoverbox-content flex-center pe-none bg-holder overlay overlay-2"></div>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-8 col-lg-9 p-x1">
                                                                <div class="row g-0 h-100">
                                                                    <div
                                                                        class="col-lg-12 col-xxl-12 d-flex flex-column pe-x1">
                                                                        <a href="{{ route('pages.resources.single', ['resource' => $propose_resource]) }}">
                                                                            <h4 class="mt-3 mt-sm-0 fs-0 fs-lg-1">
                                                                                <span
                                                                                    class="fw-semi-bold">{{ $propose_resource->title }}</span>
                                                                            </h4>
                                                                        </a>
                                                                        <p class="fs--1 mt-2 d-none d-lg-block">{!! Str::limit(strip_tags($propose_resource->content), 200) !!}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </article>
                                            @endforeach
                                        </div>
                                        <div class="card-footer bg-light p-0 border-top"><a
                                                class="btn btn-link d-block w-100"
                                                href="{{ route('pages.resources') }}">All Resources<span
                                                    class="fas fa-chevron-right ms-1 fs--2"></span></a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-guest-layout>

