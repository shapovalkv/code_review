@extends('admin.layouts.app')
@section('title','MarketplaceUser')
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb20">
            <h1 class="title-bar">{{__("All Marketplace Users")}}</h1>
            <div class="title-actions">
                <a href="{{route('user.admin.create', ['marketplace_user_create' => 1])}}" class="btn btn-primary">{{__("Add new Marketplace User")}}</a>
                <a class="btn btn-warning btn-icon" href="{{ route('marketplace_user.admin.export', request()->query()) }}" target="_blank" title="{{ __("Export to excel") }}">
                    <i class="icon ion-md-cloud-download"></i> {{ __("Export to excel") }}
                </a>
            </div>
        </div>
        @include('admin.message')
        <div class="row">
            <div class="col-md-12">
                @if(!empty($rows))
                    <form method="post" action="{{url('admin/module/marketplace_user/bulkEdit')}}"
                          class="filter-form filter-form-left d-flex justify-content-start">
                        {{csrf_field()}}
                        <select name="action" class="form-control">
                            <option value="">{{__(" Bulk Actions ")}}</option>
                            <option value="delete">{{__(" Delete ")}}</option>
                        </select>
                        <button data-confirm="{{__("Do you want to delete?")}}" class="btn-info btn btn-icon dungdt-apply-form-btn" type="button">{{__('Apply')}}</button>
                    </form>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <form method="get" action="{{url('/admin/module/marketplace_user/')}} " class="filter-form filter-form-right form-inline" role="search">
                    @if(request()->query('orderBy'))
                        <input type="hidden" name="orderBy" value="{{request()->query('orderBy')}}">
                    @endif
                    @if(request()->query('orderDirection'))
                        <input type="hidden" name="orderDirection" value="{{request()->query('orderDirection')}}">
                    @endif
                    <input type="text" name="s" value="{{ Request()->s }}" placeholder="{{__('Search by name')}}"
                           class="form-control">
                    <select name="status" class="form-control">
                        <option value="">{{ __('-- Status --')}} </option>
                        <option @if((Request()->status == 'publish')) selected @endif value="publish"> {{__('Publish')}} </option>
                        <option @if((Request()->status == 'blocked')) selected @endif value="blocked"> {{__('Blocked')}} </option>
                    </select>
                    <button class="btn-info btn btn-icon btn_search" type="submit">{{__('Search MarketplaceUser')}}</button>
                        <a href="{{route('marketplace_user.admin.index')}}" class="btn btn-link">Clear</a>
                </form>
            </div>
        </div>
        <div class="text-right">
            <p><i>{{__('Found :total items',['total'=>$rows->total()])}}</i></p>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-body">
                        <form action="" class="bravo-form-item">
                            <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th width="60px"><input type="checkbox" class="check-all"></th>
                                    <th class="title"><a href="{{sortUrl('users.name')}}">{{__('Name')}} {!! sortDirectionIco('users.name') !!}</a></th>
                                    <th class="title"><a href="{{sortUrl('users.email')}}">{{__('Email')}} {!! sortDirectionIco('users.email') !!}</a></th>
                                    <th class="title"><a href="{{sortUrl('users.phone')}}">{{__('Phone')}} {!! sortDirectionIco('users.phone') !!}</a></th>
                                    <th width="100px"><a href="{{sortUrl('bc_marketplace_users.created_at')}}">{{__('Date')}} {!! sortDirectionIco('bc_marketplace_users.created_at') !!}</a></th>
                                    <th width="100px"><a href="{{sortUrl('users.status')}}">{{__('Status')}} {!! sortDirectionIco('users.status') !!}</a></th>
                                    <th width="100px"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($rows->total() > 0)
                                    @foreach($rows as $row)
                                        <tr  @if($row->user->deleted_at) style="opacity:0.5" @endif>
                                            <td>
                                                <input type="checkbox" class="check-item" name="ids[]" value="{{$row->id}}">
                                            </td>
                                            <td class="title">
                                                <a href="{{route('user.admin.detail',['id'=>$row->id])}}">{{$row->user->name}}</a>
                                            </td>
                                            <td> {{ $row?->user->email}}</td>
                                            <td> {{ $row?->user->phone}}</td>
                                            <td> {{ display_date($row->updated_at)}}</td>
                                            <td>
                                                @if($row->user->deleted_at)
                                                    <span class="badge badge-danger">{{ __('Deleted at :date', ['date' => $row->user->deleted_at->format('m/d/Y')]) }}</span>
                                                    @else
                                                <span class="badge badge-{{ $row->user->status }}">{{ $row->user->status }}</span>
                                                    @endif
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fa fa-th"></i>
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <a class="dropdown-item" href="{{route('user.admin.detail', ['id' => $row->id])}}"><i class="fa fa-edit"></i> {{__('Edit')}}</a>
                                                        <a class="dropdown-item" href="{{route('user.admin.login-as', ['user' => $row->id])}}"><i class="fa fa-user"></i> {{__('Login As')}}</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6">{{__("No data")}}</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                            </div>
                        </form>
                        {{$rows->appends(request()->query())->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
