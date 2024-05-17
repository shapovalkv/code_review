@extends('admin.layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb20">
            <h1 class="title-bar">{{__('Promo Codes')}}</h1>
            <a href="{{route('user.admin.promocode.create')}}" class="btn btn-success">Create Promo Code</a>
        </div>
        @include('admin.message')
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="filter-div d-flex justify-content-between ">
                    <div class="col-left">
                        @if(!empty($rows))
                            <form method="post" action="{{route('user.admin.promocode.bulkEdit')}}"
                                  class="filter-form filter-form-left d-flex justify-content-start">
                                {{csrf_field()}}
                                <select name="action" class="form-control">
                                    <option value="">{{__(" Bulk Action ")}}</option>
                                    <option value="restore">{{__(" Restore ")}}</option>
                                    <option value="delete">{{__(" Delete ")}}</option>
                                </select>
                                <button data-confirm="{{__("Do you want to do?")}}"
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
                            <a href="{{route('user.admin.promocode.index')}}" class="btn btn-link">Clear</a>
                        </form>
                    </div>
                </div>
                <div class="panel">
                    <div class="panel-body" style="overflow-x: auto">
                        <form class="bravo-form-item">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th width="60px"><input type="checkbox" class="check-all"></th>
                                    <th width="60px">{{__("ID")}}</th>
                                    <th>{{__("Name")}}</th>
                                    <th>{{__("Code")}}</th>
                                    <th>{{__("Plan")}}</th>
                                    <th>{{__("Annual")}}</th>
                                    <th width="60px">{{__("Value")}}</th>
                                    <th width="60px">{{__("Status")}}</th>
                                    <th width="60px">{{__("Expire")}}</th>
                                    <th width="60px">{{__("Created")}}</th>
                                    <th width="100px"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($rows as $row)
                                    <tr @if($row->deleted_at) style="opacity: 0.5" @endif>
                                        <td><input type="checkbox" name="ids[]" value="{{$row->id}}" class="check-item">
                                        <td>#{{$row->id}}</td>
                                        <td class="title">
                                            <a href="{{route('user.admin.promocode.edit',['promocode'=>$row->id])}}">{{$row->title}}</a>
                                        </td>
                                        <td>{{$row->code}}</td>
                                        <td>{{$row->plan?->title}}</td>
                                        <td><i class="fa fa-{{$row->is_annual ? 'check' : 'ban'}}"></i></td>
                                        <td class="">{{$row->is_percent ? $row->value . '%' :format_money($row->value)}}</td>
                                        <td><span class="badge badge-{{ $row->deleted_at ? 'danger' : 'success' }}">{{ $row->deleted_at ? 'Deleted at ' . display_date($row->deleted_at) : 'Active' }}</span></td>
                                        <td class="">{{$row->expiration_date ? display_date($row->expiration_date) : ''}}</td>
                                        <td class="">{{display_date($row->created_at)}}</td>
                                        <td class="title">
                                            <a href="{{route('user.admin.promocode.edit',['promocode'=>$row->id])}}"
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
