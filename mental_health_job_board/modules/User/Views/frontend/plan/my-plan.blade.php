@php use Illuminate\Database\Eloquent\Builder; @endphp
@extends('layouts.user')
@section('head')

@endsection
@section('content')
    <div class="upper-title-box">
        <h3>{{__("My Current Subscription")}}</h3>
    </div>
    @include('admin.message')
    <div class="row justify-content-center">

        <div class="ui-block col-xxl-3 col-xl-6 col-md-6 col-sm-12">
            <div class="ui-item">
                <div class="left">
                    <i class="icon flaticon-briefcase"></i>
                </div>
                <div class="right">
                    <h4>
                        @if(is_employer())
                            @if($user->company)
                                {{$user->company->jobsPublish()->whereDate('expiration_date', '>=',  date('Y-m-d'))->count('id')}}
                            @else
                                0
                            @endif
                        @else
                            {{$user->gigs()->count('id')}}
                        @endif
                        /
                        {{$user->getCurrentPlanFeatureCount(\Modules\User\Models\PlanFeature::JOB_CREATE)}}
                    </h4>
                    <p>{{__("Total Jobs Published")}}</p>
                </div>
            </div>
        </div>

        <div class="ui-block col-xxl-3 col-xl-6 col-md-6 col-sm-12">
            <div class="ui-item">
                <div class="left">
                    <i class="icon flaticon-briefcase"></i>
                </div>
                <div class="right">
                    <h4>
                        @if(is_employer())
                            @if($user->company)
                                {{$user->company->jobsPublish()->whereDate('expiration_date', '>=',  date('Y-m-d'))->where('is_featured', '=', 1)->count('id')}}
                            @else
                                0
                            @endif
                        @else
                            {{$user->gigs()->count('id')}}
                        @endif
                        /
                        {{$user->getCurrentPlanFeatureCount(\Modules\User\Models\PlanFeature::JOB_SPONSORED)}}
                    </h4>
                    <p>{{__("Total Popular Jobs Published")}}</p>
                </div>
            </div>
        </div>
        <div class="ui-block col-xxl-3 col-xl-6 col-md-6 col-sm-12">
            <div class="ui-item ui-yellow">
                <div class="left">
                    <i class="icon la la-comment-o"></i>
                </div>
                <div class="right">
                    <h4>
                        @if(is_employer())
                            {{$publishedAnnouncements}}
                        @else
                            {{$user->gigs()->count('id')}}
                        @endif
                        /
                        {{$user->getCurrentPlanFeatureCount(\Modules\User\Models\PlanFeature::ANNOUNCEMENT_CREATE)}}
                    </h4>
                    <p>{{__("Free Announcements Published")}}</p>
                </div>
            </div>
        </div>
        <div class="ui-block col-xxl-3 col-xl-6 col-md-6 col-sm-12">
            <div class="ui-item ui-yellow">
                <div class="left">
                    <i class="icon la la-comment-o"></i>
                </div>
                <div class="right">
                    <h4>
                        @if(is_employer())
                            {{$user->marketplacesPublish()->count('id')}}
                        @else
                            {{$user->gigs()->count('id')}}
                        @endif
                    </h4>
                    <p>{{__("Total Announcements Published")}}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="ls-widget">
                <div class="tabs-box">
                    <div class="widget-title">
                        <h4>{{__("My Subscriptions")}}</h4>
                        <a href="{{route('subscription')}}" class="theme-btn  btn-style-ten text-white pull-right"><i
                                class="fa fa-list"></i> {{__('View Subscription Plans')}}</a>
                    </div>
                    <div class="widget-content" style="overflow-x: auto">
                        @php
                            $userPlan = $user->currentUserPlan;
                            $jobFeature = $user->getCurrentPlanFeatureCount(\Modules\User\Models\PlanFeature::JOB_CREATE);
                            $jobSponsorFeature = $user->getCurrentPlanFeatureCount(\Modules\User\Models\PlanFeature::JOB_SPONSORED);
                            $announcementFeature = $user->getCurrentPlanFeatureCount(\Modules\User\Models\PlanFeature::ANNOUNCEMENT_CREATE);
                        @endphp
                        <table class="default-table manage-job-table mb-5">
                            <thead>
                            <tr>
                                <th>{{__("Name")}}</th>
                                <th>{{__("Period")}}</th>
                                <th>{{__("Published Jobs Allowed")}}</th>
                                <th>{{__("Published Popular Jobs Allowed")}}</th>
                                <th>{{__("Published Announcement Allowed")}}</th>
                                <th>{{__("Price")}}</th>
                                <th>{{__("Status")}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($userPlan)
                                <tr style="background: #f5f7fc6e">
                                    <td class="trans-id">{{$userPlan->plan->title ?? ''}}</td>
                                    <td class="total-jobs"><small>{{display_date($userPlan->start_date)}}
                                            - {{display_date($userPlan->end_date)}}</small></td>
                                    <td class="used">
                                        @if($user->company)
                                            {{$user->company->jobsPublish()->whereDate('expiration_date', '>=',  date('Y-m-d'))->count('id')}}
                                        @else
                                            0
                                        @endif
                                        /
                                        @if($jobFeature === -1)
                                            {{__("Unlimited")}}
                                        @else
                                            {{ $jobFeature }}
                                        @endif
                                    </td>
                                    <td class="used">
                                        @if($user->company)
                                            {{$user->company->jobsPublish()->whereDate('expiration_date', '>=',  date('Y-m-d'))->where('is_featured', '=', 1)->count()}}
                                        @else
                                            0
                                        @endif
                                        /
                                        @if($jobSponsorFeature === -1)
                                            {{__("Unlimited")}}
                                        @else
                                            {{ $jobSponsorFeature }}
                                        @endif
                                    </td>
                                    <td class="used">
                                        {{$publishedAnnouncements}} / @if($announcementFeature === -1)
                                            {{__("Unlimited")}}
                                        @else
                                            {{ $announcementFeature  }}
                                        @endif
                                    </td>
                                    <td class="remaining">{{format_money($userPlan->price)}}</td>
                                    <td class="text-center">
                                        @if($userPlan->is_valid)
                                            <span class="text-success">{{__('Active')}}</span>
                                        @else
                                            <div class="text-danger mb-3">{{__('Expired')}}</div>
                                            <div>
                                                <a href="{{route('subscription')}}"
                                                   class="btn btn-warning">{{__('Renew')}}</a>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                            @foreach($user->userPlans->where('status', '!=', \Modules\User\Models\UserPlan::CURRENT)->sortByDesc('created_at') as $plan)
                                @if($plan->plan->plan_type === \Modules\User\Models\Plan::TYPE_RECURRING || ($plan->plan->plan_type === \Modules\User\Models\Plan::TYPE_ONE_TIME && ($plan->status === \Modules\User\Models\UserPlan::NOT_USED || $plan->status === \Modules\User\Models\UserPlan::USED)))
                                    <tr>
                                        <td class="trans-id">{{$plan->plan->title ?? ''}}</td>
                                        <td class="total-jobs"><small>{{display_date($plan->start_date)}}
                                                - {{display_date($plan->end_date)}}</small></td>
                                        <td class="used">
                                            @if($plan->plan->plan_type === \Modules\User\Models\Plan::TYPE_ONE_TIME)
                                                {{ $plan->features[\Modules\User\Models\PlanFeature::JOB_CREATE] ?? 0 }}
                                            @elseif($plan->plan->features->where('slug', \Modules\User\Models\PlanFeature::JOB_CREATE)->first()?->value === -1)
                                                {{__("Unlimited")}}
                                            @else
                                                {{ (int)$plan->plan->features->where('slug', \Modules\User\Models\PlanFeature::JOB_CREATE)->first()?->value }}
                                            @endif
                                        </td>
                                        <td class="used">
                                            @if($plan->plan->plan_type === \Modules\User\Models\Plan::TYPE_ONE_TIME)
                                                {{ $plan->features[\Modules\User\Models\PlanFeature::JOB_SPONSORED] ?? 0 }}
                                            @elseif($plan->plan->features->where('slug', \Modules\User\Models\PlanFeature::JOB_SPONSORED)->first()?->value === -1)
                                                {{__("Unlimited")}}
                                            @else
                                                {{ (int)$plan->plan->features->where('slug', \Modules\User\Models\PlanFeature::JOB_SPONSORED)->first()?->value }}
                                            @endif
                                        </td>
                                        <td class="used">
                                            @if($plan->plan->plan_type === \Modules\User\Models\Plan::TYPE_ONE_TIME)
                                                {{ $plan->features[\Modules\User\Models\PlanFeature::ANNOUNCEMENT_CREATE] ?? 0 }}
                                            @elseif($plan->plan->features->where('slug', \Modules\User\Models\PlanFeature::ANNOUNCEMENT_CREATE)->first()?->value === -1)
                                                {{__("Unlimited")}}
                                            @else
                                                {{ (int)$plan->plan->features->where('slug', \Modules\User\Models\PlanFeature::ANNOUNCEMENT_CREATE)->first()?->value }}
                                            @endif
                                        </td>
                                        <td class="remaining">{{format_money($plan->price)}}</td>
                                        <td class="text-center">
                                            @if($plan->status === \Modules\User\Models\UserPlan::NOT_USED)
                                                <div class="text-primary">{{__('Not Used')}}</div>
                                            @elseif($plan->plan->plan_type === \Modules\User\Models\Plan::TYPE_ONE_TIME && $plan->start_date->isPast())
                                                    <div class="text-danger">{{__('Used')}}</div>
                                            @elseif($plan->start_date->isPast())
                                                <div class="text-danger">{{__('Expired')}}</div>
                                            @else
                                                <div class="text-warning">{{__('Waiting')}}</div>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="ls-widget">
                <div class="tabs-box">
                    <div class="widget-title">
                        <h4>{{__("My Payments")}}</h4>
                    </div>
                    <div class="widget-content" style="overflow-x: auto">
                        <table class="default-table manage-job-table mb-5">
                            <thead>
                            <tr>
                                <th>{{__("Package Name")}}</th>
                                <th>{{__("Payment Date")}}</th>
                                <th>{{__("Object")}}</th>
                                <th>{{__("Price")}}</th>
                                <th>{{__("Status")}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($user->orders->count())
                                @foreach($user->orders as $order)
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td>{{$item->model()->title ?? '-'}}</td>
                                            <td>{{display_datetime($order->updated_at)}}</td>
                                            <td>{{$item->item()->title ?? '-'}}</td>
                                            <td>{{format_money($item->subtotal)}}</td>
                                            <td><span
                                                    class="text-{{$item->status === \Modules\Order\Models\Order::COMPLETED ? 'success' : 'danger'}}">{{$item->status}}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="text-center">{{__('No data')}}</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
@endsection
