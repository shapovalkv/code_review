@extends('layouts.user')

@php
    $manifestDir = 'dist/frontend';
@endphp

@section('head')
@endsection
@section('content')
    @include('admin.message')
    @if(!$current_plan && $isHasAvailablePlan)
    <div class="alert alert-danger alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>You don't have a subscription. Buy it to post content. <a href="{{route('subscription')}}" target="_blank">See Subscriptions</a></strong>
    </div>
    @endif
    <div class="pricing-tabs tabs-box">
        <div class="tabs-content">
            <div class="tab active-tab" id="monthly">
                <div class="content">
                    <div class="row">
                        @foreach($plans as $plan)
                            <form
                                action="{{ route('seller.store.marketplace.plan', $marketplace->id) }}"
                                method="post"
                            >
                                @csrf
                                <div class="pricing-table recommended m-3">
                                    <div class="tag">Recommended</div>
                                    <div class="inner-box">
                                        <div class="title">{{$plan->title}}</div>
                                        <div class="price">
                                            @if($plan->price)
                                                ${{$plan->price}}
                                                <span class="duration">/ per post</span>
                                            @else
                                                Free
                                            @endif
                                        </div>
                                        <div class="table-content">
                                            {!! $plan->content !!}
                                        </div>
                                        <div class="table-footer">
                                            <input type="hidden" value="{{$plan->id}}" name="plan_id">
                                            <button type="submit" name="action"
                                                    value="{{$plan->plan_type}}"
                                                    class="theme-btn btn-style-three">
                                                Post an Announcement
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        @endforeach
                        @if($current_plan)
                            @if ($available_announcements > 0)
                                <form
                                    action="{{ route('seller.store.marketplace.plan', $marketplace->id) }}"
                                    method="post"
                                >
                                    @csrf
                                    @endif
                                    <div class="pricing-table recommended m-3">
                                        <div class="tag">Recommended</div>
                                        <div class="inner-box">
                                            <div class="title">{{$current_plan->plan->title}}</div>
                                            <div class="price">
                                                Free
                                            </div>
                                            <div class="table-content">
                                                <h5>Sponsor your post</h5>
                                                <ul>
                                                    <li>
                                    <span>
                                        You have <strong
                                            class="{{$available_announcements <= 0 ? 'text-danger' : ''}}">{{$available_announcements}}</strong> free announcement posting available
                                        <i class="ri-information-line recommended-popover-trigger-1"></i>
                                    </span>
                                                    </li>
                                                    <li>
                                    <span>
                                        Active up to {{$current_plan->plan->expiration_announcement_time}} days
                                        <i class="ri-information-line recommended-popover-trigger-1"></i>
                                    </span>
                                                    </li>
                                                    <li>
                                    <span>
                                        Visible on the Marketplace
                                        <i class="ri-information-line recommended-popover-trigger-2"></i>
                                    </span>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="table-footer">
                                                @if ($available_announcements > 0)
                                                    <button type="submit" name="action" value="user_plan"
                                                            class="theme-btn btn-style-three">
                                                        Post an Announcement
                                                    </button>
                                                @endif
                                        </div>
                                    </div>
                                    </div>
                                    @if ($available_announcements > 0)
                                </form>
                            @endif
                        @endif
                            @foreach($userPlans as $plan)
                                <form
                                    action="{{ route('seller.store.marketplace.plan', $marketplace->id) }}"
                                    method="post"
                                >
                                    @csrf
                                    <div class="pricing-table recommended m-3">
                                        <div class="tag">Best Value</div>
                                        <div class="inner-box">
                                            <div class="title">{{$plan->plan->title}}</div>
                                            <div class="price">
                                                Free
                                            </div>
                                            <div class="table-content">
                                                {!! $plan->plan->content !!}
                                            </div>
                                            <div class="table-footer">
                                                <input type="hidden" value="{{$plan->id}}" name="plan_id">
                                                <button type="submit" name="action"
                                                        value="package"
                                                        class="theme-btn btn-style-three">
                                                    Post an Announcement
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script>
        {{--const countAvailableItems = {!! $user->user_plan ? ($user->user_plan->features['equipment-sponsored'] ?? 0) : -1 !!}; // php code--}}

        {{--if (parseInt(countAvailableItems, 10) == 0) {--}}
        {{--    bootbox.alert(--}}
        {{--        {--}}
        {{--            title:'{{__("Attention")}}',--}}
        {{--            message:'{{__('Нou have no posts left to promote, switch to a plan with a large number of posts, or use a one-time post promotion')}}'--}}
        {{--        }--}}
        {{--    )--}}
        {{--}--}}

        const popoverList = [
            {element: '.free-popover-trigger', content: '.free-popover-content'},
            {element: '.recommended-popover-trigger-1', content: '.free-popover-content'},
            {element: '.recommended-popover-trigger-2', content: '.recommended-popover-content-2'},
            {element: '.recommended-popover-trigger-3', content: '.recommended-popover-content-3'},
            {element: '.new-popover-trigger-1', content: '.free-popover-content-2'},
            {element: '.new-popover-trigger-2', content: '.recommended-popover-content-2'},
            {element: '.new-popover-trigger-3', content: '.recommended-popover-content-3'},
            {element: '.new-popover-trigger-4', content: '.new-popover-content-1'},
        ]

        $(document).ready(function () {
            popoverList.forEach(item => {
                $(item.element).popover({
                    content: $(item.content),
                    placement: 'top',
                    trigger: 'hover focus',
                    html: true
                });
            })
        });
    </script>
@endsection
