@extends('admin.layouts.app')

@section('content')

    <div class="container-fluid">
        <div class="filter-div d-flex justify-content-between mb20">
            <div class="col-left">
                <h1 class="title-bar">{{ __('Dashboard user notices')}}</h1>
            </div>
            <div class="col-left">
                <a href="{{route('user.admin.notice.new')}}" class="btn btn-success">Create</a>
            </div>
        </div>
        @include('admin.message')
        <div class="panel">
            <div class="panel-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    @foreach($roles as $role)
                        <li class="nav-item">
                            <a class="nav-link {{$role->id === 4 ? 'active' : ''}}" id="role{{$role->id}}-tab" data-toggle="tab"
                               href="#role{{$role->id}}" role="tab" aria-controls="role{{$role->id}}"
                               aria-selected="true">{{$role->name}}</a>
                        </li>
                    @endforeach
                </ul>
                <div class="tab-content" id="myTabContent">
                    @foreach($notices as $roleId => $list)
                        <div class="tab-pane fade {{$roleId === 4 ? 'show active' : ''}}" id="role{{$roleId}}" role="tabpanel" aria-labelledby="role{{$roleId}}-tab">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>{{ __("Title") }}</th>
                                        <th>{{ __('Content')}}</th>
                                        <th>{{ __('Style')}}</th>
                                        <th>{{ __('Sort')}}</th>
                                        <th>{{ __('Status')}}</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($list as $notice)
                                        <tr @if($notice->status === \Modules\User\Models\DashboardNotice::DRAFT) style="opacity: 0.5" @endif>
                                            <td>
                                                <a href="{{route('user.admin.notice.edit', ['dashboard_notice' => $notice->id])}}"
                                                   class="btn btn-link">{{$notice->title}}</a>
                                            </td>
                                            <td>{{substr(strip_tags($notice->content), 0, 50)}} ...</td>
                                            <td><span class="badge badge-{{$notice->style}}">{{$notice->style}}</span>
                                            </td>
                                            <td>{{$notice->sort}}</td>
                                            <td>
                                                <span
                                                    class="badge badge-{{$notice->status === \Modules\User\Models\DashboardNotice::DRAFT ? 'dark' : 'success'}}">{{$notice->status}}</span>
                                            </td>
                                            <td>
                                                <a href="{{route('user.admin.notice.edit', ['dashboard_notice' => $notice->id])}}"
                                                   class="btn btn-default">
                                                    <span class="icon text-center"><i class="ion-md-filing"></i></span>
                                                    Edit
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
@endsection

@section('script.body')

@endsection
