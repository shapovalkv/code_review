@extends('admin.layouts.app')

@section('content')
    <form action="{{route('user.admin.notice.store', ['dashboard_notice'=> $notice->id ?: null])}}" method="post">
        @csrf
        <div class="container-fluid">
            <div class="d-flex justify-content-between mb20">
                <div class="">
                    <h1 class="title-bar">{{$notice->id ? __('Edit: ').$notice->title : __('Add new notice')}}</h1>
                </div>
            </div>
            @include('admin.message')
            <div class="lang-content-box">
                <div class="row">
                    <div class="col-md-9">
                        <div class="panel">
                            <div class="panel-body">
                                <h3 class="panel-body-title">{{__("Notice Content")}}</h3>
                                <div class="form-group">
                                    <label>{{__("Title")}} <span class="text-danger">*</span></label>
                                    <input type="text" required value="{{old('title',$notice->title)}}" placeholder="{{__("title")}}" name="title"
                                           class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>{{__("Content")}} </label>
                                    <textarea name="content" class="d-none has-ckeditor">{{old('content',$notice->content)}}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>{{__("For Role")}} <span class="text-danger">*</span></label>
                                    <select name="filter[role_id]" class="form-control">
                                        <option value="">{{__("-- Please Select --")}}</option>
                                        @foreach(\Modules\User\Models\Role::all() as $role)
                                            <option @if(old('role_id',$notice->filter['role_id'] ?? null) == $role->id) selected
                                                    @endif value="{{$role->id}}">{{$role->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">{{__("Style")}}</label>
                                    @php
                                        $styles = [
                                             \Modules\User\Models\DashboardNotice::SUCCESS,
                                             \Modules\User\Models\DashboardNotice::WARNING,
                                             \Modules\User\Models\DashboardNotice::DANGER,
                                             \Modules\User\Models\DashboardNotice::PRIMARY,
                                        ];
                                    @endphp
                                    <select name="style" class="form-control">
                                        @foreach($styles as $style)
                                            <option @if(old('style', $notice->style) === $style) selected @endif value="{{ $style }}">{{ ucfirst($style) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">{{__("Sorting value")}} </label>
                                    <input type="number" min="0" value="{{old('sort',max(0,$notice->sort))}}" name="sort" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="panel">
                            <div class="panel-title"><strong>{{__('Publish')}}</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="control-label">{{__("Status")}}</label>
                                    <select name="status" class="form-control">
                                        <option value="publish">{{__("Publish")}}</option>
                                        <option @if(old('status',$notice->status) == 'draft') selected @endif value="draft">{{__("Draft")}}</option>
                                    </select>
                                </div>
                                <div class="text-right">
                                    <button class="btn btn-primary" type="submit"><i
                                            class="fa fa-save"></i> {{__('Save Changes')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('script.body')

@endsection
