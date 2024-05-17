@extends('layouts.user')

@php
    $manifestDir = 'dist/frontend';
@endphp

@section('head')
@endsection
@section('content')
    {{--    <section class="pricing-section">--}}
    {{--        @include('admin.message')--}}
    {{--        --}}{{--        route('user.update.job', $job) - free--}}
    {{--        --}}{{--        @include('User::frontend.plan.list')--}}
    {{--    </section>--}}
    <section class="pricing-section">
        <div class="manage-page manage-page--pricing-plans manage-page--promote">
            <div class="manage-page-header">
                <h3 class="manage-page-header__title">BOOST YOUR POST</h3>
                <div class="manage-page-header__subtitle">
                    You can promote your job post and bring it to the top of the search list, select pricing plan to get
                    more promotions or post for free.
                </div>
            </div>
            <form
                action="{{ route('user.store.job.plan', $job->id) }}"
                method="post"
            >
                @csrf
                <div class="pricing-section__tab-content">

                <div class="pricing-table">
                    <div class="inner-box">
                        <div class="title">FREE POST</div>

                        <div class="price">$0</div>

                        <div class="table-content">
                            <h5>Free job post</h5>

                            <ul>
                                <li>
                                    <span>Active up to 20 days</span>
                                    <i class="ri-information-line free-popover-trigger"></i>
                                </li>
                            </ul>
                        </div>

                        <div class="table-footer">
                            <button type="submit" name="action" value="free" class="f-btn primary-btn">
                                POST FOR FREE

                                <svg class="ml-2" width="28" height="8" viewBox="0 0 28 8" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M28 4L23 8V0L28 4Z" fill="white"></path>
                                    <path d="M23 3.58011H0V4.41989H23V3.58011Z" fill="white"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="pricing-table recommended">

                    <div class="tag">Recommended</div>

                    <div class="inner-box">
                        <div class="title">Promote your post</div>

                        <div class="price">
                            $5
                            <span class="duration">/ per post</span>
                        </div>

                        <div class="table-content">
                            <h5>Promote your post</h5>

                            <ul>
                                <li>
                                    <span>
                                        Active up to 20 days
                                        <i class="ri-information-line recommended-popover-trigger-1"></i>
                                    </span>
                                </li>
                                <li>
                                    <span>
                                        Visible in the top of search list
                                        <i class="ri-information-line recommended-popover-trigger-2"></i>
                                    </span>
                                </li>
                                <li>
                                    <span>
                                        Sponsored badge
                                    <i class="ri-information-line recommended-popover-trigger-3"></i>
                                    </span>
                                </li>
                            </ul>
                        </div>

                        <div class="table-footer">
                            <button type="submit" name="action" value="promote" class="f-btn primary-btn mt-2">
                                PROMOTE
                            </button>
                        </div>
                    </div>
                </div>

{{--                @if ($user->user_plan)--}}
{{--                @if(!empty($user_plan))--}}
{{--                    @php--}}
{{--                        $plan = $user->user_plan->plan;--}}
{{--                        $translate = $plan->translateOrOrigin(app()->getLocale());--}}
{{--                    @endphp--}}

{{--                    <div class="pricing-table current ">--}}
{{--                        <div class="tag">{{__('Current plan')}}</div>--}}

{{--                        <div class="inner-box">--}}
{{--                            <div class="title">{{$translate->title}}</div>--}}

{{--                            <div class="price">{{$plan->price ? format_money($plan->price) : __('Free')}}--}}
{{--                                @if($plan->price)--}}
{{--                                    <span--}}
{{--                                        class="duration">/ {{$plan->duration > 1 ? $plan->duration : ''}} {{$plan->duration_type_text}}</span>--}}
{{--                                @endif--}}
{{--                            </div>--}}
{{--                            <div class="table-content">--}}
{{--                                {!! clean($translate->content) !!}--}}
{{--                                @if($user->user_plan && array_key_exists('job-sponsored', $user->user_plan->features))--}}
{{--                                <ul><li><span>{{ __('sponsored jobs - (:jobs remains per your plan)', ['jobs' => $user->user_plan->features['job-sponsored']]) }}</span></li></ul>--}}
{{--                                @endif--}}
{{--                            </div>--}}

{{--                            <div class="table-footer">--}}

{{--                                <button type="submit" name="action" value="current_plan" class="f-btn primary-btn">--}}
{{--                                    {{__("Promote your job")}}--}}

{{--                                    <svg class="ml-2" width="28" height="8" viewBox="0 0 28 8" fill="none"--}}
{{--                                         xmlns="http://www.w3.org/2000/svg">--}}
{{--                                        <path d="M28 4L23 8V0L28 4Z" fill="white"></path>--}}
{{--                                        <path d="M23 3.58011H0V4.41989H23V3.58011Z" fill="white"></path>--}}
{{--                                    </svg>--}}
{{--                                </button>--}}

{{--                            </div>--}}
{{--                        </div>--}}

{{--                    </div>--}}
{{--                @else--}}
{{--                    // TODO Commented job select plans for a time--}}
                    {{--    if user already have plan            <form action="{{route('user.update.job', ['job' => $job->id, 'action' => 'sponsored'])}}" method="post">--}}
                    {{--     for empty plan           <form action="{{route('user.plan')}}" method="post">--}}
{{--                    <div class="pricing-table">--}}
{{--                        <div class="inner-box">--}}
{{--                            <div class="title">SELECT PRICING PLAN</div>--}}

{{--                            <div class="price">$30--}}
{{--                                <span class="duration">/  month</span>--}}
{{--                            </div>--}}

{{--                            <div class="table-content">--}}
{{--                                <h5>Small team with up to 10 users.</h5>--}}

{{--                                <ul>--}}
{{--                                    <li>--}}
{{--                                        <span>--}}
{{--                                            Active up to 30 days--}}
{{--                                            <i class="ri-information-line new-popover-trigger-1"></i>--}}
{{--                                        </span>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <span>--}}
{{--                                            Visible in the top of search list--}}
{{--                                            <i class="ri-information-line new-popover-trigger-2"></i>--}}
{{--                                        </span>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <span>--}}
{{--                                            Sponsored badge--}}
{{--                                            <i class="ri-information-line new-popover-trigger-3"></i>--}}
{{--                                        </span>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <span>--}}
{{--                                            Promote more posts--}}
{{--                                            <i class="ri-information-line new-popover-trigger-4"></i>--}}
{{--                                        </span>--}}
{{--                                    </li>--}}
{{--                                </ul>--}}
{{--                            </div>--}}

{{--                            <div class="table-footer">--}}
{{--                                <a href="{{ route('user.plan', ['redirectTo' => request()->url()]) }}" class="f-btn primary-btn">--}}
{{--                                    {{__("SELECT PRICING PLAN")}}--}}

{{--                                    <svg class="ml-2" width="28" height="8" viewBox="0 0 28 8" fill="none"--}}
{{--                                         xmlns="http://www.w3.org/2000/svg">--}}
{{--                                        <path d="M28 4L23 8V0L28 4Z" fill="white"></path>--}}
{{--                                        <path d="M23 3.58011H0V4.41989H23V3.58011Z" fill="white"></path>--}}
{{--                                    </svg>--}}
{{--                                </a>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                @endif--}}
            </div>
            </form>

        </div>

        <div class="d-none">
            <div class="popover-content free-popover-content">
                Your post will be
                active during next 20 days with opportunity to renew
            </div>

            <div class="popover-content free-popover-content-2">
                Your post will be
                active during next 30 days with opportunity to renew
            </div>

            <div class="popover-content recommended-popover-content-2">
                Your post gets to the top of the search listing to attract more visitors.

                <img src="{{ asset('images/pages/post-plan/promote-plan-1.png') }}" alt="..." />
            </div>

            <div class="popover-content recommended-popover-content-3">
                Boost your post with sponsored badge.

                <img src="{{ asset('images/pages/post-plan/promote-plan-2.png') }}" alt="..." />
            </div>

            <div class="popover-content new-popover-content-1">
                Boost your posts with more promotions. Select your plan and get from 3 to 5 promotions for each job or equipment post.
            </div>
        </div>
    </section>
@endsection
@section('footer')
    <script>
        {{--const countAvailableItems = {!! $user->user_plan ? ($user->user_plan->features['job-sponsored'] ?? 0) : -1 !!}; // php code--}}

        {{--if (parseInt(countAvailableItems, 10) == 0) {--}}
        {{--    bootbox.alert(--}}
        {{--        {--}}
        {{--            title:'{{__("Attention")}}',--}}
        {{--            message:'{{__('Нou have no posts left to promote, switch to a plan with a large number of posts, or use a one-time post promotion')}}'--}}
        {{--        }--}}
        {{--    )--}}
        {{--}--}}

        const popoverList = [
            { element: '.free-popover-trigger', content: '.free-popover-content'},
            { element: '.recommended-popover-trigger-1', content: '.free-popover-content'},
            { element: '.recommended-popover-trigger-2', content: '.recommended-popover-content-2'},
            { element: '.recommended-popover-trigger-3', content: '.recommended-popover-content-3'},
            { element: '.new-popover-trigger-1', content: '.free-popover-content-2'},
            { element: '.new-popover-trigger-2', content: '.recommended-popover-content-2'},
            { element: '.new-popover-trigger-3', content: '.recommended-popover-content-3'},
            { element: '.new-popover-trigger-4', content: '.new-popover-content-1'},
        ]

        $(document).ready(function(){
            popoverList.forEach(item => {
                $(item.element).popover({ content: $(item.content), placement: 'top', trigger: 'hover focus', html: true });
            })
        });
    </script>
@endsection
