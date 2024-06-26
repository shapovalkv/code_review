@if(!empty($list_item))
    <div class="bravo-how-it-works work-section">
        <div class="auto-container">
            <div class="sec-title text-center">
                <h2>{{ $title ?? '' }}</h2>
                <div class="text">{{ $sub_title ?? '' }}</div>
            </div>

            <div class="row">
                @foreach($list_item as $item)
                    <div class="work-block col-lg-4 col-md-6 col-sm-12 @if(!auth()->check()) work-block-no-padding @endif">
                        <div class="inner-box">
                            <h5>{{ $item['title'] ?? '' }}</h5>
                            <figure class="image"><img src="{{ get_file_url($item['icon_image']) }}" alt="{{ $item['title'] }}"></figure>
                            <p>{!! (auth()->check() ? strip_tags(preg_replace('#<a.*?>.*?</a>#i', '', $item['sub_title'])) : $item['sub_title']) ?? '' !!}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
