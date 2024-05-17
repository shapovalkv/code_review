<div class="manage-page manage-page--pricing-plans">
    <div class="manage-page-header">
        <div class="manage-page-header__title-wrap">
            <h3 class="manage-page-header__title">{{ __("Pricing Plans") }}</h3>
        </div>

        <div class="manage-page-header__tabs">
            <ul class="nav" id="pricingPlanTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="manage-page-header__tabs-btn nav-link active" id="monthly-tab" data-toggle="tab"
                            data-target="#monthly" type="button" role="tab" aria-controls="monthly"
                            aria-selected="true">Monthly
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="manage-page-header__tabs-btn nav-link" id="annual-tab" data-toggle="tab"
                            data-target="#annual" type="button" role="tab" aria-controls="annual" aria-selected="false">
                        Annual
                    </button>
                </li>
            </ul>
        </div>
    </div>
    <div class="pricing-tabs tabs-box">
        <div class="tab-content" id="pricingPlanTabContent">
            <div class="tab-pane fade show active" id="monthly" role="tabpanel" aria-labelledby="monthly-tab">
                <div class="pricing-section__tab-content">
                    @foreach($plans as $plan)
                        @php
                            $translate = $plan->translateOrOrigin(app()->getLocale());
                            $user_plan = !empty($plan) ? $user->user_plan : '';
                            $params = array_merge(['id'=> $plan->id], request()->query());
                        @endphp

                        <div
                            class="
                                pricing-table
                                @if(!empty($user_plan) && $user_plan->is_valid && $user_plan->plan_id == $plan->id) current @endif
                                @if($plan->is_recommended && !(!empty($user_plan) && $user_plan->plan_id == $plan->id)) recommended @endif
                            "
                        >
                            @if($plan->is_recommended && !(!empty($user_plan) && $user_plan->plan_id == $plan->id))
                                <div class="tag">{{__('Recommended')}}</div>
                            @endif

                            @if(!empty($user_plan) && $user_plan->plan_id == $plan->id)
                                @if ($user_plan->cancelled())
                                    <div class="tag warning">{{__('Cancelled plan')}}</div>
                                @elseif (!$user_plan->is_valid)
                                    <div class="tag error">{{__('Expired plan')}}</div>
                                @else
                                    <div class="tag">{{__('Current plan')}}</div>
                                @endif
                            @endif

                            <div class="inner-box">
                                <div class="title">{{$translate->title}}</div>

                                <div class="price">{{$plan->price ? format_money($plan->price) : __('Free')}}
                                    @if($plan->price)
                                        <span
                                            class="duration">/ {{$plan->duration > 1 ? $plan->duration : ''}} {{$plan->duration_type_text}}</span>
                                    @endif
                                </div>

                                <div class="table-content">
                                    {!! clean($translate->content) !!}
                                    @if(!empty($user_plan) && $user_plan->plan_id == $plan->id)
                                        @if(array_key_exists('job-sponsored', $user->user_plan->features))
                                            <ul>
                                                <li>
                                                    <span>{{ __('sponsored jobs - (:jobs remains per your plan)', ['jobs' => $user->user_plan->features['job-sponsored']]) }}</span>
                                                </li>
                                            </ul>
                                        @endif
                                        @if(array_key_exists('equipment-sponsored', $user->user_plan->features))
                                            <ul>
                                                <li>
                                                    <span>{{ __('sponsored equipment - (:equipment remains per your plan)', ['equipment' => $user->user_plan->features['equipment-sponsored']]) }}</span>
                                                </li>
                                            </ul>
                                        @endif
                                    @endif
                                </div>

                                <div class="table-footer">
                                    @if(!empty($user) && !empty($user_plan) && $user_plan->plan_id == $plan->id)
                                        @if ($user_plan->is_valid)
                                            @if ($user_plan->cancelled())
                                                <a href="{{route('user.plan.buy', $params)}}"
                                                   class="f-btn primary-btn mt-2">{{__('Renew')}}</a>
                                            @else
                                                <form action="{{ route('user.plan.cancel', ['id' => $user_plan->id]) }}"
                                                      method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                            class="f-btn secondary-btn">{{__("Cancel plan")}}</button>
                                                </form>
                                            @endif




                                            {{--                                        @else--}}
                                            {{--                                            <a href="{{ route('user.plan.buy', $params) }}"--}}
                                            {{--                                               class="f-btn primary-btn ">{{__('Repurchase')}}</a>--}}
                                        @endif
                                    @else
                                        <a href="{{route('user.plan.buy', $params)}}"
                                           class="f-btn primary-btn">
                                            {{__('get')}} {{$translate->title}}

                                            <svg class="ml-2" width="28" height="8" viewBox="0 0 28 8" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path d="M28 4L23 8V0L28 4Z" fill="white"></path>
                                                <path d="M23 3.58011H0V4.41989H23V3.58011Z" fill="white"></path>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="tab-pane fade" id="annual" role="tabpanel" aria-labelledby="annual-tab">
                <div class="pricing-section__tab-content">
                    @foreach($plans as $plan)
                        @php
                            if(!$plan->annual_price) continue;

                            $translate = $plan->translateOrOrigin(app()->getLocale());
                            $user_plan = $user->user_plan;
                            $params = array_merge(['id'=> $plan->id], request()->query());
                        @endphp

                        <div
                            class="
                                pricing-table
                                @if(!empty($user_plan) && $user_plan->is_valid && $user_plan->plan_id == $plan->id) current @endif
                                @if($plan->is_recommended && empty($user_plan)) recommended @endif
                            "
                        >
                            @if($plan->is_recommended && empty($user_plan))
                                <div class="tag">{{__('Recommended')}}</div>
                            @endif

                            @if(!empty($user_plan) && $user_plan->plan_id == $plan->id)
                                <div class="tag">{{__('Current plan')}}</div>
                            @endif

                            <div class="inner-box">
                                <div class="title">{{$plan->title}}</div>

                                <div class="price">{{format_money($plan->annual_price)}} <span
                                        class="duration">/ {{__("year")}}</span></div>

                                <div class="table-content">
                                    {!! clean($plan->content) !!}
                                </div>

                                <div class="table-footer">
                                    @if($user && !empty($user_plan) && $user->user_plan && $user_plan->plan_id == $plan->id)
                                        @if(!empty($user_plan) && $user_plan->is_valid)
                                            <a href="{{ route('user.plan') }}"
                                               class="f-btn secondary-btn">{{__("Cancel plan")}}</a>

{{--                                            @if(setting_item_with_lang('enable_multi_user_plans'))--}}
{{--                                                <a href="{{route('user.plan.buy', $params)}}"--}}
{{--                                                   class="f-btn primary-btn mt-2">{{__('Repurchase')}}</a>--}}
{{--                                            @endif--}}
{{--                                        @else--}}
{{--                                            <a href="{{route('user.plan.buy', $params)}}"--}}
{{--                                               class="f-btn primary-btn ">{{__('Repurchase')}}</a>--}}
                                        @endif
                                    @else
                                        <a href="{{route('user.plan.buy', $params)}}"
                                           class="f-btn primary-btn">
                                            {{__('get')}} {{$translate->title}}

                                            <svg class="ml-2" width="28" height="8" viewBox="0 0 28 8" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path d="M28 4L23 8V0L28 4Z" fill="white"></path>
                                                <path d="M23 3.58011H0V4.41989H23V3.58011Z" fill="white"></path>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
