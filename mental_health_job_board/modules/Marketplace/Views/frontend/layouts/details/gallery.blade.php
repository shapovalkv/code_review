@if($row->getGallery())
    <div class="portfolio-outer">
        <h4 class="mb-5">{{__('Photo Gallery')}}</h4>
        <div class="row">
            @foreach($row->getGallery() as $key=>$item)
                @if(!empty($item['thumb']))
                    <div class="col-lg-3 col-md-3 col-sm-6 col-6">
                        <figure class="image">
                            <img src="{{$item['thumb']}}" data-ngsrc="{{$item['large']}}" data-nanogallery2-lightbox/>
                        </figure>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@endif

@push('js')
    <script type="text/javascript"
            src="{{url('libs/nanogallery/jquery.nanogallery2.min.js')}}"></script>
@endpush

@push('css')
    <link href="{{url('libs/nanogallery/css/nanogallery2.min.css')}}" rel="stylesheet"
          type="text/css">
@endpush
