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
                        @if(!$resources->isEmpty())
                            @foreach($resources as $resource)
                                <article class="card mb-3 overflow-hidden resource-card">
                                    <div class="card-body p-0">
                                        <div class="row g-0">
                                            <div class="col-md-4 col-lg-3">
                                                <div class="hoverbox h-md-100">
                                                    <a href="{{ route('pages.resources.single', ['resource' => $resource]) }}">
                                                        <img class="h-100 w-100 object-fit-cover" src="{{ $resource->featured_image_url }}" alt="{{ $resource->featured_image_url }}"/>
                                                        <div class="hoverbox-content flex-center pe-none bg-holder overlay overlay-2"></div>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-md-8 col-lg-9 p-x1">
                                                <div class="row g-0 h-100">
                                                    <div class="col-xxl-12 d-flex flex-column pe-x1">
                                                        <a href="{{ route('pages.resources.single', ['resource' => $resource]) }}">
                                                            <h4 class="mt-3 mt-sm-0 fs-0 fs-lg-1">
                                                                <span class="fw-semi-bold">{{ $resource->title }}</span>
                                                            </h4>
                                                        </a>
                                                        <p class="fs--1 mt-2 d-lg-block">{!! Str::limit(strip_tags($resource->content), 300) !!}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        @else
                            <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
                                <div class="text-center">
                                    <h2>There are no posts here yet</h2>
                                </div>
                            </div>
                        @endif
                        @if ($resources->lastPage() > 1)
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-footer d-flex align-items-center justify-content-center">
                                        {!! $resources->links('pagination::bootstrap-4') !!}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>
    </main>
</x-guest-layout>

