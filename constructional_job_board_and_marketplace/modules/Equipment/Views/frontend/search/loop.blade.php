@php $translation = $row->translateOrOrigin(app()->getLocale()); @endphp
<div class="equipment-item h-100 border-radius-8">
    <div class="d-flex flex-column h-100">
        <div class="equipment-thumb-title">
            <a href="{{$row->getDetailUrl()}}" class="equipment-img" >
                @if($row->image_id)
                    {!! get_image_tag($row->image_id,'full',['alt'=>$row->title]) !!}
                @else
                    {{ __("equipment") }}
                @endif
            </a>
            <div class="equipment-content flex-grow-1">
                <div class="equipment-author mb-3 align-items-center d-none d-md-flex">
                    @if(!empty($author = $row->author))
                        <div class="equipment-author-img mr-2">
                            <img src="{{$author->avatar_url}}" alt="{{$author->display_name}}">
                        </div>
                        <div class="author-name"><a class="c-222325" href="{{$author->getDetailUrl()}}">{{$author->display_name}}</a></div>
                    @endif
                </div>
                <h3 class="g-title fs-16 fs-16"><a href="{{$row->getDetailUrl()}}" title="{{$translation->title}}">{{$translation->title}}</a></h3>

                <div class="div equipment-review d-block d-md-none mt-2">
                    <?php
                    $reviewData = $row->getScoreReview();
                    $score_total = $reviewData['score_total'];
                    ?>
                    @if($reviewData['total_review'] > 1)
                        <div class="rating d-inline-block">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <div class="rating-active" style="width: {{  $score_total * 2 * 10 ?? 0  }}%">
                                <div class="inner">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </div>
                            </div>
                        </div>
                        ({{$reviewData['total_review']}})
                    @endif
                </div>
            </div>
        </div>
        <div class="equipment-footer p-md-3 d-flex justify-content-between flex-shrink-0">
            <div class="div equipment-review d-none d-md-block">
                <?php
                $reviewData = $row->getScoreReview();
                $score_total = $reviewData['score_total'];
                ?>
                @if($reviewData['total_review'] > 1)
                <div class="rating d-inline-block">
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <div class="rating-active" style="width: {{  $score_total * 2 * 10 ?? 0  }}%">
                        <div class="inner">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                        </div>
                    </div>
                </div>
                ({{$reviewData['total_review']}})
                @endif
            </div>
            <div class="equipment-author mb-md-3 d-flex align-items-center d-md-none">
                @if(!empty($author = $row->author))
                    <div class="equipment-author-img mr-2">
                        <img src="{{$author->avatar_url}}" alt="{{$author->display_name}}">
                    </div>
                    <div class="author-name"><a class="c-222325" href="{{$author->getDetailUrl()}}">{{$author->display_name}}</a></div>
                @endif
            </div>
            <div>
                <span class="c-7a7d85">{{__("Starting at ")}}</span>
                 <span class="fs-20">{{format_money($row->basic_price)}}</span>
            </div>
        </div>
    </div>
</div>
