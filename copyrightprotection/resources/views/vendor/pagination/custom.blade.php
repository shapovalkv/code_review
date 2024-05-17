@if ($paginator->hasPages())
<div class="d-flex flex-wrap py-2 mr-3">

    @if ($paginator->onFirstPage())
    <a href="#" class="disabled btn btn-icon btn-sm btn-light mr-2 my-1">
        <i class="ki ki-bold-arrow-back icon-xs"></i>
    </a>
    @else
    <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-icon btn-sm btn-light mr-2 my-1">
        <i class="ki ki-bold-arrow-back icon-xs"></i>
    </a>
    @endif

    @foreach ($elements as $element)

            @if (is_string($element))
                <a href="#" class="btn btn-icon btn-sm border-0 btn-light mr-2 my-1 disabled">{{ $element }}</a>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <a href="#" class="btn btn-icon btn-sm border-0 btn-light btn-hover-primary active mr-2 my-1">{{ $page }}</a>
                    @else
                        <a href="{{ $url }}" class="btn btn-icon btn-sm border-0 btn-light mr-2 my-1">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

    @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl()}}" class="btn btn-sm btn-falcon-default ms-1" type="button" title="Next" data-list-pagination="next">
                <svg class="svg-inline--fa fa-chevron-right fa-w-10" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="chevron-right" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" data-fa-i2svg=""><path fill="currentColor" d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"></path></svg><!-- <span class="fas fa-chevron-right"></span> Font Awesome fontawesome.com -->
            </a>
        @else
            <button class="btn btn-sm btn-falcon-default ms-1 disabled" type="button" title="Next" data-list-pagination="next">
                <svg class="svg-inline--fa fa-chevron-right fa-w-10" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="chevron-right" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" data-fa-i2svg=""><path fill="currentColor" d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"></path></svg><!-- <span class="fas fa-chevron-right"></span> Font Awesome fontawesome.com -->
            </button>
    @endif


    {{-- <a href="#" class="btn btn-icon btn-sm btn-light mr-2 my-1">
        <i class="ki ki-bold-double-arrow-next icon-xs"></i>
    </a> --}}
</div>
@endif
