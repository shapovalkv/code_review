@extends('admin.layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb20">
            <h1 class="title-bar">{{__("User Plans")}}</h1>
            <a href="{{route('user.admin.plan.create')}}" class="btn btn-success">Create Plan</a>
        </div>
        @include('admin.message')
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="filter-div d-flex justify-content-between ">
                    <div class="col-left">
                        @if(!empty($rows))
                            <form method="post" action="{{route('user.admin.plan.bulkEdit')}}"
                                  class="filter-form filter-form-left d-flex justify-content-start">
                                {{csrf_field()}}
                                <select name="action" class="form-control">
                                    <option value="">{{__(" Bulk Action ")}}</option>
                                    <option value="publish">{{__(" Publish ")}}</option>
                                    <option value="draft">{{__(" Move to Draft ")}}</option>
                                    <option value="delete">{{__(" Delete ")}}</option>
                                </select>
                                <button data-confirm="{{__("Do you want to delete?")}}"
                                        class="btn-info btn btn-icon dungdt-apply-form-btn"
                                        type="button">{{__('Apply')}}</button>
                            </form>
                        @endif
                    </div>
                    <div class="col-left">
                        <form method="get" action="" class="filter-form filter-form-right d-flex justify-content-end"
                              role="search">
                            <input type="text" name="s" value="{{ Request()->s }}" class="form-control"
                                   placeholder="{{__("Search by name")}}">
                            <button class="btn-info btn btn-icon btn_search" id="search-submit"
                                    type="submit">{{__('Search')}}</button>
                        </form>
                    </div>
                </div>
                <div class="panel">
                    <div class="panel-body" style="overflow-x: auto">
                        <form class="bravo-form-item">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th style="max-width: 60px"><input type="checkbox" class="check-all"></th>
                                    <th style="max-width: 60px">{{__("ID")}}</th>
                                    <th>{{__("Name")}}</th>
                                    <th>{{__("Type")}}</th>
                                    <th>{{__("For Role")}}</th>
                                    <th style="max-width: 50px">{{__("Price")}}</th>
                                    <th style="max-width: 60px">{{__("Annual Price")}}</th>
                                    <th style="max-width: 60px">{{__("Duration")}}</th>
                                    <th style="max-width: 60px">{{__("Expired days")}}</th>
                                    <th style="max-width: 60px">{{__("Job Post")}}</th>
                                    <th style="max-width: 60px">{{__("Popular Job Post")}}</th>
                                    <th style="max-width: 60px">{{__("Announcement Post")}}</th>
                                    <th style="max-width: 60px">{{__("Status")}}</th>
                                    <th style="max-width: 60px">{{__("Date")}}</th>
                                    <th style="max-width: 60px"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($rows as $row)
                                    <tr>
                                        <td><input type="checkbox" name="ids[]" value="{{$row->id}}" class="check-item">
                                        <td>#{{$row->id}}</td>
                                        <td class="title">
                                            <a href="{{route('user.admin.plan.edit',['plan'=>$row->id])}}">{{$row->title}}</a>
                                        </td>
                                        <td>{{$row->plan_type}}</td>
                                        <td>{{$row->role->name ?? ''}}</td>
                                        <td class="">{{$row->price ? format_money($row->price) : __("Free")}}</td>
                                        <td class="">{{$row->annual_price ? format_money($row->annual_price) : ''}}</td>
                                        <td class="">{{$row->duration_text ?? ''}}</td>
                                        <td class="">{{$row->expiration_job_time ?? ''}}</td>
                                        @php
                                        $jobCreated = $row->features()->where('slug', '=', \Modules\User\Models\PlanFeature::JOB_CREATE)->first();
                                        $jobSponsored = $row->features()->where('slug', '=', \Modules\User\Models\PlanFeature::JOB_SPONSORED)->first();
                                        $announcementCreated = $row->features()->where('slug', '=', \Modules\User\Models\PlanFeature::ANNOUNCEMENT_CREATE)->first();
                                        @endphp

                                        @if(!empty($jobCreated))
                                            <td class="">{{$jobCreated->value ?: 0}}</td>
                                        @else
                                            <td class="">0</td>
                                        @endif

                                        @if(!empty($jobSponsored))
                                            <td class="">{{$jobSponsored->value ?: 0}}</td>
                                        @else
                                            <td class="">0</td>
                                        @endif

                                        @if(!empty($announcementCreated))
                                            <td class="">{{$announcementCreated->value ?: 0}}</td>
                                        @else
                                            <td class="">0</td>
                                        @endif

                                        <td><span class="badge badge-{{ $row->status }}">{{ $row->status }}</span></td>
                                        <td class="">{{ display_date($row->updated_at)}}</td>
                                        <td class="title">
                                            <a href="{{route('user.admin.plan.edit',['plan'=>$row->id])}}"
                                               class="btn btn-default btn-sm"><i class="fa fa-edit"></i> {{__("Edit")}}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{$rows->appends(request()->query())->links()}}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
