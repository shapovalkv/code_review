<div class="bravo-companies job-detail-section">
    <div class="upper-box">
        <div class="auto-container">
            <!-- Job Block -->
            <div class="job-block-seven">
                <div class="inner-box">
                    <div class="content">
                        <span class="company-logo">
                            @if($image_tag = get_image_tag($row->avatar_id,'full',['alt'=>$translation->title]))
                                {!! $image_tag !!}
                            @else
                                <img class='img-fluid mb-4 rounded-xs w-100 lazy' src="{{ asset('images/avatar.png') }}" alt="avatar">
                            @endif
                        </span>
                        <h4><a href="{{$row->getDetailUrl()}}">{{ $translation->name }}</a></h4>
                        <ul class="job-info">
                            @if($row->location)
                                @php $location =  $row->location->translateOrOrigin(app()->getLocale()) @endphp
                                <li><span class="icon flaticon-map-locator"></span> {{ $location->name }}</li>
                            @endif
                            @if($row->getOpenJobsCount() > 1)
                                <li><span class="icon flaticon-briefcase"></span> {{ __('Multiple Positions') }}</li>
                            @elseif($row->getOpenJobsCount() == 1)
                                @php
                                    $category = $row->jobs->first()->category;
                                    $t = $category->translateOrOrigin(app()->getLocale());
                                @endphp
                                <li><span class="icon flaticon-briefcase"></span> {{$t->name ?? ''}}</li>
                            @endif
                            @if(!empty($row->phone) && \Illuminate\Support\Facades\Auth::user())
                                <li><span class="icon flaticon-telephone-1"></span>{{ $row->phone }}</li>
                            @endif
                            @if(!empty($row->email) && \Illuminate\Support\Facades\Auth::user())
                                <li><span class="icon flaticon-mail"></span>{{ $row->email }}</li>
                            @endif
                        </ul>
                        @if($row->job_count > 0)
                            <ul class="job-other-info">
                                <li class="time">{{ __("Open Jobs – :count",["count"=>number_format($row->job_count)]) }}</li>
                            </ul>
                        @endif
                    </div>
                    @if(is_candidate())
                        <div>
                            <div class="btn-box mb-0 mb-md-3">
                                <a class="bookmark-btn service-wishlist"
                                   href="{{route('user.chat.start', ['targetUser' => $row->getAuthor->id])}}"
                                   data-toggle="tooltip" data-placement="bottom" title="{{__('Messaging')}}"><i class="fa fa-lg fa-comment-dots"></i>
                                </a>
                            </div>
                            <div class="btn-box mt-3 mt-md-0">
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="job-detail-outer">
        <div class="auto-container">
            <div class="row">
                <div class="content-column col-lg-8 col-md-12 col-sm-12">
                    <div class="job-detail">
                        <h4>{{__("About Company")}}</h4>
                        {!! $translation->about !!}
                    </div>
                    <!-- Related Jobs -->
                    <div class="related-jobs">
                        @if($row->job_count > 0)
                            <div class="title-box">
                                <h3>{{ __(":count jobs at :title",["count"=>$row->job_count, "title"=> $translation->name]) }}</h3>
                            </div>
                        @endif
                        @if($jobs->count() > 0)
                            @foreach($jobs as $job)
                                <div class="job-block">
                                    @include('Job::frontend.layouts.loop.job-item-1', ['row' => $job])
                                </div>
                            @endforeach
                        @endif
                        <div class="ls-pagination">
                            {{$jobs->appends(request()->query())->links()}}
                            @if($jobs->total() > 0)
                                <span
                                    class="count-string">{{ __("Showing :from - :to of :total",["from"=>$jobs->firstItem(),"to"=>$jobs->lastItem(),"total"=>$jobs->total()]) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="sidebar-column col-lg-4 col-md-12 col-sm-12">
                    @include('Company::frontend.layouts.details.companies-sidebar')
                    {{--                    @include('Job::frontend.layouts.details.contact',['origin_id'=>$row->owner_id,'job_id'=>false])--}}
                </div>
            </div>
        </div>
    </div>
</div>
