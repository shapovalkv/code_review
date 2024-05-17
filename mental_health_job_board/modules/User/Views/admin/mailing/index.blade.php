@extends('admin.layouts.app')

@section('content')

    <div class="container-fluid">
        <div class="d-flex justify-content-between mb20">
            <div class="">
                <h1 class="title-bar">{{ __('Mailing')}}</h1>
            </div>
        </div>
        @include('admin.message')
        <form action="{{route('user.admin.mailing.store')}}" method="post">
            @csrf
            <div class="lang-content-box">
                <div class="row">
                    <div class="col-md-9">
                        <div class="panel">
                            <div class="panel-title"><strong>{{__("Mail content")}}</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label>{{__("Subject")}}</label>
                                    <input type="text" value="{{ old('subject') }}" placeholder="{{__("Subject")}}"
                                           name="subject" required class="form-control">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">{{__("Body")}}</label>
                                    <div class="m-2 p-2">
                                        Available tags:
                                        @foreach($tags as $tag)
                                            <span class="tag">{{$tag}}</span>,
                                        @endforeach
                                    </div>
                                    <div class="">
                                        {{-- https://www.tiny.cloud/docs/tinymce/6/mergetags/ --}}
                                        <textarea name="body" id="tiny" class="d-none has-ckeditor" cols="30"
                                                  rows="10">{{ old('body') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="panel">
                            <div class="panel-title"><strong>{{__('User role')}}</strong></div>
                            <div class="panel-body">
                                <select name="role_ids[]" multiple required class="form-control">
                                    @foreach($roles as $role)
                                        <option value="{{$role->id}}">{{$role->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="panel">
                            <div class="panel-title"><strong>{{__('Publish profile')}}</strong></div>
                            <div class="panel-body">
                                <select name="status" class="form-control">
                                    <option value="">All</option>
                                    <option value="{{\Modules\User\Enums\UserStatusEnum::PUBLISH}}">{{ucfirst(\Modules\User\Enums\UserStatusEnum::PUBLISH)}}</option>
                                    <option value="{{\Modules\User\Enums\UserStatusEnum::DRAFT}}">{{ucfirst(\Modules\User\Enums\UserStatusEnum::DRAFT)}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="panel">
                            <div class="panel-title"><strong>{{__('Register from')}}</strong></div>
                            <div class="panel-body">
                                <input type="date" name="register_from" value="" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-envelope"></i> Send</button>
        </form>
    </div>
@endsection

@section('script.body')
    <script>
        $(function () {
            $('.tag').on('click', function () {
                // don't work. throw exception
                // tinymce.editors[0].insertContent($(this).innerText);
            })
        })
    </script>
@endsection
