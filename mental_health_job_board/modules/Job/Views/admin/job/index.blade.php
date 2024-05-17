@extends('admin.layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb20">
            <h1 class="title-bar">{{ __("All Jobs") }}</h1>
            <div class="title-actions">
                <a href="{{ route('job.admin.create') }}" class="btn btn-primary">{{__("Post a Job")}}</a>
                <a class="btn btn-warning btn-icon" href="{{ route('job.admin.export', request()->query()) }}" target="_blank" title="{{ __("Export to excel") }}">
                    <i class="icon ion-md-cloud-download"></i> {{ __("Export to excel") }}
                </a>
            </div>
        </div>
        @include('admin.message')
        <div class="filter-div d-flex justify-content-between ">
            <div class="col-left">
                @if(!empty($rows))
                    <form method="post" action="{{url('admin/module/job/bulkEdit')}}" class="filter-form filter-form-left d-flex justify-content-start">
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
                <form method="get" action="{{ route('job.admin.index') }}" class="filter-form filter-form-right d-flex justify-content-end flex-column flex-sm-row" role="search">
                    @if(request()->query('orderBy'))
                        <input type="hidden" name="orderBy" value="{{request()->query('orderBy')}}">
                    @endif
                    @if(request()->query('orderDirection'))
                        <input type="hidden" name="orderDirection" value="{{request()->query('orderDirection')}}">
                    @endif
                    @if(is_admin())
                        <?php
                        $company = \Modules\Company\Models\Company::find(Request()->input('company_id'));
                        \App\Helpers\AdminForm::select2('company_id', [
                            'configs' => [
                                'ajax'        => [
                                    'url' => route('company.admin.getForSelect2'),
                                    'dataType' => 'json'
                                ],
                                'allowClear'  => true,
                                'placeholder' => __('-- Select Company --')
                            ]
                        ], !empty($company->id) ? [
                            $company->id,
                            $company->name . ' (#' . $company->id . ')'
                        ] : false)
                        ?>
                    @endif
                    <input type="text" name="s" value="{{ Request()->input('s') }}" placeholder="{{__('Search by name')}}" class="form-control">
                    <button class="btn-info btn btn-icon btn_search" type="submit">{{__('Search')}}</button>
                        <a href="{{route('job.admin.index')}}" class="btn btn-link">Clear</a>
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
                                <th><a href="{{sortUrl('title')}}">{{__('Title')}} {!! sortDirectionIco('title') !!}</a></th>
                                <th><a href="{{sortUrl('bc_job_positions.name')}}">{{__('Employment Type')}} {!! sortDirectionIco('bc_job_positions.name') !!}</a></th>
                                <th width="200px"><a href="{{sortUrl('bc_locations.name')}}">{{__('Location')}} {!! sortDirectionIco('bc_locations.name') !!}</a></th>
                                <th width="150px"><a href="{{sortUrl('bc_job_categories.name')}}">{{__('Category')}} {!! sortDirectionIco('bc_job_categories.name') !!}</a></th>
                                <th width="150px"><a href="{{sortUrl('bc_companies.name')}}">{{__('Company')}} {!! sortDirectionIco('bc_companies.name') !!}</a></th>
                                <th width="100px"><a href="{{sortUrl('status')}}">{{__('Status')}} {!! sortDirectionIco('status') !!}</a></th>
                                <th width="100px"><a href="{{sortUrl('created_at')}}">{{__('Date')}} {!! sortDirectionIco('created_at') !!}</a></th>
                                @if(setting_item('job_need_approve'))
                                <th width="100px"> {{ __('Approved')}}</th>
                                @endif
                                <th width="100px"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($rows->total() > 0)
                                @foreach($rows as $row)
                                    <tr class="{{$row->status}}">
                                        <td><input type="checkbox" name="ids[]" class="check-item" value="{{$row->id}}">
                                        </td>
                                        <td class="title">
                                            <a href="{{ $row->getEditUrl() }}">{{$row->title}}</a>
                                        </td>
                                        <td>{{ $row->position->name ?? "Not Selected"}}</td>
                                        <td>{{$row->location->name ?? ''}}</td>
                                        <td>{{$row->category->name ?? ''}}</td>
                                        <td>{{$row->company->name ?? ''}}</td>
                                        <td><span class="badge badge-{{ $row->status }}">{{ $row->status }}</span></td>
                                        <td>{{ display_date($row->updated_at)}}</td>
                                        @if(setting_item('job_need_approve'))
                                        <td> @if(empty($row->is_approved) || $row->is_approved == 'approved')
                                            <span class="badge badge-success"> {{ __('Approved')}} </span>
                                            @elseif($row->is_approved == 'draft')
                                            <span class="badge badge-secondary"> {{ __('Draft') }} </span>
                                            @elseif($row->is_approved == 'waiting')
                                            <span class="badge badge-warning"> {{ __('Waiting for approve') }} </span>
                                            @endif
                                        </td>
                                        @endif
                                        <td>
                                            <a href="{{  $row->getEditUrl() }}" class="btn btn-default btn-sm"><i class="fa fa-edit"></i> {{__('Edit')}}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7">{{__("No data")}}</td>
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
@endsection
