@extends('layouts.user')

@section('content')
    @if(!auth()->user()->currentUserPlan || !auth()->user()->currentUserPlan->plan->hasFeature(\Modules\User\Models\PlanFeature::SUB_ACCOUNTS))
        <div class="col-12 col-md-12">
            <div class="alert alert-danger alert-block text-center mt-5 ml-5 mr-5">
                <h4>{!! __('Please upgrade your Subscription Plan to use the Employees Feature') !!}</h4>
                <a class="btn btn-link" href="{{route('subscription')}}">{{__('Change Your Subscription Plan')}}</a>
            </div>
        </div>
    @else
        @include('admin.message')
        <div class="row">
            <div class="col-lg-12">
                <div class="ls-widget">
                    <div class="tabs-box">
                        <div class="widget-title">
                            <h4>{{__("Add Users")}}
                                (Active Accounts: {{count($rows->whereNull('deleted_at'))}}
                                /{{auth()->user()->getCurrentPlanFeatureCount(\Modules\User\Models\PlanFeature::SUB_ACCOUNTS)}}
                                )
                            </h4>
                            <a href="{{route('user.company.staff.create')}}"
                               class="theme-btn  btn-style-seven text-white pull-right"><i
                                    class="fa fa-plus"></i> {{__('Add New User')}}</a>
                        </div>
                        <div class="widget-content" style="overflow-x: auto">
                            <table class="default-table manage-job-table mb-5">
                                <thead>
                                <tr>
                                    <th>{{__("Avatar")}}</th>
                                    <th>{{__("First Name")}}</th>
                                    <th>{{__("Last Name")}}</th>
                                    <th>{{__("Email")}}</th>
                                    <th>{{__("Phone")}}</th>
                                    <th>{{__("Status")}}</th>
                                    <th>{{__("Created At")}}</th>
                                    <th>{{__("Action")}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($rows as $row)
                                    <tr @if($row->deleted_at) style="opacity: 0.5" @endif>
                                        <td><img src="{{$row->getAvatarUrl()}}" alt="{{$row->name}}" class=""
                                                 style="width: 50px;height: 50px;border-radius: 50%"></td>
                                        <td>{{$row->first_name}}</td>
                                        <td>{{$row->last_name}}</td>
                                        <td>{{$row->email}}</td>
                                        <td>{{$row->phone}}</td>
                                        <td>
                                            @if($row->deleted_at === null)
                                                <span class="badge badge-success">{{__('Active')}}</span>
                                            @else
                                                <span
                                                    class="badge badge-danger">{{__('Disabled At :date', ['date' => display_date($row->deleted_at)])}}</span>
                                            @endif</td>
                                        <td>{{ display_date($row->created_at)}}</td>
                                        <td style="display: flex">
                                            @if(!$row->deleted_at)
                                                <a href="{{route('user.company.staff.edit', ['user' => $row])}}"
                                                   class="btn btn-link" title="{{__('Edit')}}"><i
                                                        class="fa fa-pencil"></i></a>
                                                <form action="{{route('user.company.staff.disable', ['trashed_user' => $row])}}"
                                                      method="post">
                                                    @csrf
                                                    <button type="submit" class="btn btn-link text-success"
                                                            onclick="return confirm('Are You Sure?')"
                                                            title="{{__('Disable')}}"><i
                                                            class="fa fa-power-off"></i></button>
                                                </form>
                                            @else
                                                <form action="{{route('user.company.staff.enable', ['trashed_user' => $row])}}"
                                                      method="post">
                                                    @csrf
                                                    <button type="submit" class="btn btn-link text-warning"
                                                            onclick="return confirm('Are You Sure?')"
                                                            title="{{__('Enable')}}"><i
                                                            class="fa fa-power-off"></i></button>
                                                </form>
                                            @endif

                                            <form action="{{route('user.company.staff.delete', ['trashed_user' => $row])}}"
                                                  method="post">
                                                @csrf
                                                <button type="submit" class="btn btn-link text-danger"
                                                        onclick="return confirm('Are You Sure?')"
                                                        title="{{__('Delete')}}"><i
                                                        class="fa fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
