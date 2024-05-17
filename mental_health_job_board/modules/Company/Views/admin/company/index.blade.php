@extends('admin.layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb20">
            <h1 class="title-bar">{{__("All company")}}</h1>
            <div class="title-actions">
                <a href="{{url('admin/module/company/create')}}" class="btn btn-primary">{{__("Add new Company")}}</a>
                <a class="btn btn-warning btn-icon" href="{{ route('company.admin.export', request()->query()) }}" target="_blank" title="{{ __("Export to excel") }}">
                    <i class="icon ion-md-cloud-download"></i> {{ __("Export to excel") }}
                </a>
            </div>
        </div>
        @include('admin.message')
        <div class="filter-div d-flex justify-content-between ">
            <div class="col-left">
                @if(!empty($rows))
                    <form method="post" action="{{url('admin/module/company/bulkEdit')}}"
                          class="filter-form filter-form-left d-flex justify-content-start">
                        {{csrf_field()}}
                        <select name="action" class="form-control">
                            <option value="">{{__(" Bulk Actions ")}}</option>
                            <option value="publish">{{__(" Publish ")}}</option>
                            <option value="draft">{{__(" Move to Draft ")}}</option>
                            <option value="delete">{{__(" Delete ")}}</option>
                        </select>
                        <button data-confirm="{{__("Do you want to delete?")}}" class="btn-info btn btn-icon dungdt-apply-form-btn" type="button">{{__('Apply')}}</button>
                    </form>
                @endif
            </div>
            <div class="col-left">
                <form method="get" action="{{url('/admin/module/company/')}} " class="filter-form filter-form-right d-flex justify-content-end flex-column flex-sm-row" role="search">
                    @if(request()->query('orderBy'))
                        <input type="hidden" name="orderBy" value="{{request()->query('orderBy')}}">
                    @endif
                    @if(request()->query('orderDirection'))
                        <input type="hidden" name="orderDirection" value="{{request()->query('orderDirection')}}">
                    @endif
                    <input type="text" name="s" value="{{ Request()->s }}" placeholder="{{__('Search by name')}}"
                           class="form-control">
                    <select name="category_id" class="form-control">
                        <option value="">{{ __('--All Category --')}} </option>
                        <?php
                        if (!empty($categories)) {
                            foreach ($categories as $category) {
                                if(Request()->category_id == $category->id)
                                    {
                                        printf("<option value='%s' selected >%s</option>", $category->id, $category->name);
                                    }else{
                                    printf("<option value='%s' >%s</option>", $category->id, $category->name);
                                }

                            }
                        }
                        ?>
                    </select>
                    <button class="btn-info btn btn-icon btn_search" type="submit">{{__('Search Company')}}</button>
                        <a href="{{route('company.admin.index')}}" class="btn btn-link">Clear</a>
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
                                        <th class="title"><a href="{{sortUrl('name')}}">{{__('Name')}} {!! sortDirectionIco('name') !!}</a></th>
                                        <th class="title"><a href="{{sortUrl('email')}}">{{__('Email')}} {!! sortDirectionIco('email') !!}</a></th>
                                        <th class="title"><a href="{{sortUrl('phone')}}">{{__('Phone')}} {!! sortDirectionIco('phone') !!}</a></th>
                                        <th width="130px"><a href="{{sortUrl('employer')}}">{{__('Employer')}} {!! sortDirectionIco('employer') !!}</a></th>
                                        <th width="100px"><a href="{{sortUrl('plan')}}">{{__('Plan')}} {!! sortDirectionIco('plan') !!}</a></th>
                                        <th width="100px"><a href="{{sortUrl('plan_expires')}}">{{__('Plan expires')}} {!! sortDirectionIco('plan_expires') !!}</a></th>
                                        <th width="100px"><a href="{{sortUrl('created_at')}}">{{__('Date')}} {!! sortDirectionIco('created_at') !!}</a></th>
                                        <th width="100px"><a href="{{sortUrl('status')}}">{{__('Status')}} {!! sortDirectionIco('status') !!}</a></th>
                                        <th width="100px"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if($rows->total() > 0)
                                        @foreach($rows as $row)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="check-item" name="ids[]" value="{{$row->id}}">
                                                </td>
                                                <td class="title">
                                                    <a href="{{$row->getEditUrl()}}">{{$row->name}}</a>
                                                </td>
                                                <td> {{ $row->email}}</td>
                                                <td> {{ $row->phone}}</td>
                                                <td>
                                                    @if(!empty($row->getAuthor))
                                                        {{$row->getAuthor->getDisplayName()}}
                                                    @else
                                                        {{__("[Author Deleted]")}}
                                                    @endif
                                                </td>
                                                <td> {{ $row->getAuthor->currentUserPlan->plan->title ?? 'None'}}</td>
                                                <td> {{ $row->getAuthor->currentUserPlan->end_date ?? 'None'}}</td>
                                                <td> {{ display_date($row->created_at)}}</td>
                                                <td><span class="badge badge-{{ $row->status }}">{{ $row->status }}</span></td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="fa fa-th"></i>
                                                        </button>
                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                            <a class="dropdown-item" href="{{route('company.admin.edit',['id'=>$row->id])}}"><i class="fa fa-edit"></i> {{__('Edit')}}</a>
                                                            <a class="dropdown-item" href="{{route('user.admin.login-as', ['user' => $row->getAuthor->id])}}"><i class="fa fa-user"></i> {{__('Login As')}}</a>
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
