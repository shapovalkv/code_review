@if($row->Marketplace)
<div class="company-block-three seller-Marketplace-item">
    <div class="inner-box">
        <div class="content">
            <div class="content-inner">
                <span class="company-logo">
                    @if($row->Marketplace->image_id)
                        {!! get_image_tag($row->Marketplace->image_id,'full',['alt'=>$row->Marketplace->title, 'class'=>'img-fluid mb-4 rounded-xs w-100']) !!}
                    @endif
                </span>
                <h4><a href="{{ route('seller.order', ['id' => $row->id]) }}">{{ $row->Marketplace->title }}</a></h4>
                <ul class="job-info">
                    <li class="view-order"><a href="{{ route('seller.order', ['id' => $row->id]) }}" class="seller-link">{{__("View Order")}}</a></li>
                    <li><span class="icon flaticon-money"></span>{{format_money($row->price)}}</li>
                </ul>
            </div>
            <ul class="job-other-info">
                <li class="privacy">{{ $row->status_text }}</li>
            </ul>
        </div>
        <div class="text">{!! \Illuminate\Support\Str::words(strip_tags($row->content), 30, '...') !!}</div>
    </div>
</div>
@endif
