@if(!$user->futureUserPlan)
    <div class="sec-title text-center">
        <h2>{{ setting_item_with_lang('user_plans_page_title', app()->getLocale()) ?? __("Employers Pricing Packages")}}</h2>
        <div
            class="text">{{ setting_item_with_lang('user_plans_page_sub_title', app()->getLocale()) ?? __("Choose your pricing plan") }}</div>
    </div>
    <div class="pricing-tabs tabs-box">
        <div class="tab-buttons">
            <h4>{{ setting_item_with_lang('user_plans_sale_text', app()->getLocale()) ?? __('Save up to 10%') }}</h4>
            <ul class="tab-btns">
                <li data-tab="#monthly" class="tab-btn active-btn">{{__('Monthly')}}</li>
                <li data-tab="#annual" class="tab-btn">{{__('Annual')}}</li>
            </ul>
        </div>
        <div class="tabs-content">
            <div class="tab active-tab" id="monthly">
                <div class="content">
                    <div class="row">
                        @foreach($plans as $plan)
                            @php
                                $translate = $plan->translateOrOrigin(app()->getLocale());
                            @endphp
                            <div class="pricing-table col-lg-4 col-md-6 col-sm-12">
                                <div class="inner-box"
                                     @if($user->currentUserPlan && $plan->id === $user->currentUserPlan->plan->id) style="background: #007bff38;" @endif>
                                    @if($plan->is_recommended)
                                        <span class="tag">{{__('Popular')}}</span>
                                    @endif
                                    @if($plan->best_value)
                                        <span class="best-tag">{{__('Best Value')}}</span>
                                    @endif
                                    <div class="title">{{$translate->title}}</div>
                                    <div class="price">{{$plan->price ? format_money($plan->price) : __('Free')}}
                                        @if($plan->price)
                                            <span
                                                class="duration">/ {{$plan->duration > 1 ? $plan->duration : ''}} {{$plan->duration_type_text}}</span>
                                        @endif
                                    </div>
                                    <div class="table-content">
                                        {!! clean($translate->content) !!}
                                    </div>
                                    <div class="table-footer">
                                        @if($user)
                                            @if($user->currentUserPlan && $user->currentUserPlan->is_valid)
                                                @if($user->currentUserPlan->plan->id === $plan->id)
                                                    <div class="d-flex text-center">
                                                        <a href="{{route('user.plan.buy',['id'=>$plan->id])}}"
                                                           class="theme-btn btn-style-ten btn-confirm"
                                                           onclick="if(!confirm('{{__('Are you sure you want to change your subscribtion plan?\n(your current plan will be stopped and the new plan will be used)')}}')) return false;">{{__(':action and change plan now', ['action' => $plan->price ? 'Repurchase' : 'Reselect'])}}</a>
                                                    </div>
                                                    @if($plan->price && $user->currentUserPlan->plan->price)
                                                        <div class="d-flex text-center">
                                                            <a href="{{route('user.plan.buy',['id'=>$plan->id, 'start_after_current_plan' => true])}}"
                                                               class="theme-btn btn-style-seven mt-2 btn-confirm wrap">{{__("Purchase and change plan on :start", ['start' => display_date($user->currentUserPlan->end_date)])}}</a>
                                                        </div>
                                                    @endif
                                                    <div class="text-center mt-1">
                                                        <i class="fa fa-clock"></i> Expires
                                                        at {{display_date($user->currentUserPlan->end_date)}}
                                                    </div>
                                                @else
                                                    <div class="d-flex text-center">
                                                        <a href="{{route('user.plan.buy',['id'=>$plan->id])}}"
                                                           class="theme-btn btn-style-three btn-confirm"
                                                           onclick="if(!confirm('{{__('Are you sure you want to change your subscribtion plan?\n(your current plan will be stopped and the new plan will be used)')}}')) return false;">{{__(($plan->price ? 'Purchase' : 'Select') . ' and change plan now')}}</a>
                                                    </div>
                                                    @if($plan->price && $user->currentUserPlan->plan->price)
                                                        <div class="d-flex text-center">
                                                            <a href="{{route('user.plan.buy',['id'=>$plan->id, 'start_after_current_plan' => true])}}"
                                                               class="theme-btn btn-style-seven mt-2 btn-confirm wrap">{{__("Purchase and change plan on :start", ['start' => display_date($user->currentUserPlan->end_date)])}}</a>
                                                        </div>
                                                    @endif
                                                @endif
                                            @else
                                                @if($plan->price)
                                                    <div class="d-flex text-center">
                                                        <a href="{{route('user.plan.buy', ['id'=>$plan->id])}}"
                                                           class="theme-btn btn-style-three">{{__('Purchase')}}</a>
                                                    </div>
                                                @else
                                                    <div class="d-flex text-center">
                                                        <a href="{{route('user.plan.buy', ['id'=>$plan->id])}}"
                                                           class="theme-btn btn-style-three">{{__('Select')}}</a>
                                                    </div>
                                                @endif
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="tab" id="annual">
                <div class="content">
                    <div class="row">
                        @foreach($plans as $plan)
                                <?php if (!$plan->annual_price) continue; ?>
                            <div class="pricing-table col-lg-4 col-md-6 col-sm-12">
                                <div class="inner-box">
                                    @if($plan->is_recommended)
                                        <span class="tag">{{__('Popular')}}</span>
                                    @endif
                                    @if($plan->best_value)
                                        <span class="best-tag">{{__('Best Value')}}</span>
                                    @endif
                                    <div class="title">{{$plan->title}}</div>
                                    <div class="price">{{format_money($plan->annual_price)}} <span
                                            class="duration">/ {{__("year")}}</span></div>
                                    <div class="table-content">
                                        {!! clean($plan->content) !!}
                                    </div>
                                    <div class="table-footer">
                                        @if($user and $user_plan = $user->currentUserPlan and $user_plan->plan_id == $plan->id)
                                            @if($user_plan->is_valid)
                                                <div class="d-flex text-center">
                                                    <a href="{{ route('user.subscription') }}"
                                                       class="theme-btn btn-style-ten mr-2">{{__("Current Plan")}}</a>
                                                    <a href="{{route('user.plan.buy',['id'=>$plan->id])}}"
                                                       class="theme-btn btn-style-seven">{{__('Repurchase')}}</a>
                                                </div>
                                            @else
                                                <a href="{{route('user.plan.buy',['id'=>$plan->id,'annual'=>1])}}"
                                                   class="theme-btn btn-style-seven">{{__('Repurchase')}}</a>
                                            @endif
                                        @else
                                            <a href="{{route('user.plan.buy',['id'=>$plan->id,'annual'=>1])}}"
                                               class="theme-btn btn-style-three">{{__('Purchase')}}</a>
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
@else
    <div class="alert alert-warning">
        {{__('You have already purchased :name Plan that will start :date. You can buy a new plan once your current plan changes.', ['name' => $user->futureUserPlan->plan->title, 'date' => $user->futureUserPlan->start_date->format('m/d/Y')])}}
    </div>
@endif
