@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb20">
            <h1 class="title-bar">{{ __('All Users')}}</h1>
            <div class="title-actions">
                <a href="{{url('admin/module/user/create')}}" class="btn btn-primary">{{ __('Add new user')}}</a>
                <a class="btn btn-warning btn-icon" href="{{ route('user.admin.export', request()->query()) }}" target="_blank" title="{{ __("Export to excel") }}">
                    <i class="icon ion-md-cloud-download"></i> {{ __("Export to excel") }}
                </a>
            </div>
        </div>
        @include('admin.message')
        <div class="filter-div d-flex justify-content-between ">
            <div class="col-left">
                @if(!empty($rows))
                    <form method="post" action="{{url('admin/module/user/bulkEdit')}}" class="filter-form filter-form-left d-flex justify-content-start">
                        {{csrf_field()}}
                        <select name="action" class="form-control">
                            <option value="">{{__(" Bulk Actions ")}}</option>
                            <option value="delete">{{__(" Delete ")}}</option>
                        </select>
                        <button data-confirm="{{__("Do you want to delete?")}}" class="btn-info btn btn-icon dungdt-apply-form-btn" type="button">{{__('Apply')}}</button>
                    </form>
                @endif
            </div>
            <div class="col-left">
                <form method="get" action="{{route(request()->route()->getName(), request()->query())}}" class="filter-form filter-form-right d-flex justify-content-end flex-column flex-sm-row" role="search">
                    @if(request()->query('orderBy'))
                        <input type="hidden" name="orderBy" value="{{request()->query('orderBy')}}">
                    @endif
                    @if(request()->query('orderDirection'))
                        <input type="hidden" name="orderDirection" value="{{request()->query('orderDirection')}}">
                    @endif
                    <select class="form-control" name="role">
                        <option value="">{{ __('-- Select --')}}</option>
                        @foreach($roles as $role)
                            <option value="{{$role->name}}" @if(Request()->role == $role->name) selected @endif >{{ucfirst($role->name)}}</option>
                        @endforeach
                    </select>
                    <input type="text" name="s" value="{{ Request()->s }}" placeholder="{{__('Search by name')}}" class="form-control">
                    <button class="btn-info btn btn-icon btn_search" type="submit">{{__('Search User')}}</button>
                    <a href="{{route('user.admin.index')}}" class="btn btn-link">Clear</a>
                </form>
            </div>
        </div>
        <div class="text-right">
            <p><i>{{__('Found :total items',['total'=>$rows->total()])}}</i></p>
        </div>
        <div class="panel">
            <div class="panel-body">
                <form action="" class="bravo-form-item">
                    <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th width="60px"><input type="checkbox" class="check-all"></th>
                            <th><a href="{{sortUrl('name')}}">{{__('Name')}} {!! sortDirectionIco('name') !!}</a></th>
                            <th><a href="{{sortUrl('email')}}">{{__('Email')}} {!! sortDirectionIco('email') !!}</a></th>
                            <th><a href="{{sortUrl('phone')}}">{{__('Phone')}} {!! sortDirectionIco('phone') !!}</a></th>
                            <th><a href="{{sortUrl('role')}}">{{__('Role')}} {!! sortDirectionIco('role') !!}</a></th>
                            <th class="date"><a href="{{sortUrl('created_at')}}">{{__('Date')}} {!! sortDirectionIco('created_at') !!}</a></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($rows as $row)
                            <tr>
                                <td><input type="checkbox" name="ids[]" value="{{$row->id}}" class="check-item"></td>
                                <td class="title">
                                    <a href="{{url('admin/module/user/edit/'.$row->id)}}">{{$row->getDisplayName()}}</a>
                                </td>
                                <td>{{$row->email}}</td>
                                <td>{{$row->phone}}</td>
                                <td>
                                    {{$row->role->name ?? ''}}
                                </td>
                                <td>{{ display_date($row->created_at)}}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-th"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item"  href="{{url('admin/module/user/edit/'.$row->id)}}"><i class="fa fa-edit"></i> {{__('Edit')}}</a>
                                            <a class="dropdown-item"  href="{{route('user.admin.login-as', ['user' => $row])}}"><i class="fa fa-user"></i> {{__('Login As')}}</a>
                                            @if(!$row->hasVerifiedEmail())
                                                <a class="dropdown-item"  href="{{route('user.admin.verifyEmail',$row)}}"><i class="fa fa-edit"></i> {{__('Verify email')}}</a>
                                                @else
                                                <a class="dropdown-item"><i class="fa fa-check"></i> {{__('Email verified')}}</a>
                                            @endif
                                            <a class="dropdown-item" href="{{url('admin/module/user/password/'.$row->id)}}"><i class="fa fa-lock"></i> {{__('Change Password')}}</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    </div>
                </form>
                {{$rows->appends(request()->query())->links()}}
            </div>
        </div>
    </div>
@endsection
