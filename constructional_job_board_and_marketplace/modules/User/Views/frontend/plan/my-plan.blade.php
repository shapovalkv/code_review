@extends('layouts.user')
@section('head')

@endsection
@section('content')
    <div class="upper-title-box">
        <h3>{{__("My Current Plan")}}</h3>
        <div class="text">{{ __("Ready to jump back in?") }}</div>
    </div>
    @include('admin.message')
    <div class="row">
        <div class="col-lg-12">
            <div class="ls-widget">
                <div class="tabs-box">
                    <div class="widget-title">
                        <h4>{{__("My Current Plan")}}</h4>
                    </div>
                    <div class="widget-content">
                        @php
                            $user_plans = $user->userPlans;
                        @endphp
                        <table class="default-table manage-job-table mb-5">
                            <thead>
                            <tr>
                                <th>{{__("Plan ID")}}</th>
                                <th>{{__("Plan Name")}}</th>
                                <th>{{__("Expiry")}}</th>
                                <th>{{__("Used/Total")}}</th>
                                <th>{{__("Price")}}</th>
                                <th>{{__("Status")}}</th>
                            </tr>
                            </thead>

                            <tbody>
                            @if($user_plans && count($user_plans) > 0)
                                @foreach($user_plans as $user_plan)
                                    <tr>
                                        <td>#{{$user_plan->plan_id}}</td>
                                        <td class="trans-id">{{$user_plan->plan->title ?? ''}}</td>
                                        <td class="total-jobs">{{display_datetime($user_plan->end_date)}}</td>
                                        <td class="used">
                                            <?php
                                                $planFeatures = $user_plan->plan_data['features'] ?? [];
                                                $features = $user_plan->features;
                                            ?>

                                            @foreach ($features as $key => $value)
                                                <strong>{{ \Modules\User\Models\PlanFeature::FEATURES[$key] }}: </strong>
                                                @if($value === ''){{__("Unlimited")}} @else {{ ($planFeatures[$key] ?? 0) - $value  }} / {{$planFeatures[$key] ?? 0}} @endif
                                                <br />
                                            @endforeach

                                        </td>
                                        <td class="remaining">{{format_money($user_plan->price)}}</td>
                                        <td >
                                            @if($user_plan->is_valid)
                                                <span class="text-success">{{__('Active')}}</span>
                                            @else
                                                <div class="text-danger mb-3">{{__('Expired')}}</div>
                                                <div>
                                                    <a href="{{route('user.plan')}}" class="btn btn-warning">{{__('Renew')}}</a>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center">
                                        {{__("No Items")}}
                                    </td>
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
