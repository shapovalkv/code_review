@extends('layouts.user')
@section('head')

@endsection
@section('content')
    <div class="row">
        <div class="col-md-9">
            <div class="upper-title-box">
                <h3>{{__('Support - edit message')}}</h3>
            </div>
        </div>
    </div>
    @include('admin.message')
    <div class="row">
        <div class="col-lg-12">
            <!-- Ls widget -->
            <div class="ls-widget">
                <div class="tabs-box">
                    <div class="widget-title"><h4>{{ __("Message Content") }}</h4></div>
                    <div class="widget-content">
                        <form method="post"
                              action="{{ route('user.support.message.store', ['ticket' => $ticket->id, 'message' => $message->id]) }}"
                              class="default-form">
                            @csrf
                            <div class="form-group">
                                <label class="control-label">{{__("Message")}} <span
                                        class="required">*</span></label>
                                <div class="">
                                    <textarea name="content" class="d-none has-ckeditor" cols="30"
                                              rows="10">{{ old('content', $message->content) }}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <button class="theme-btn btn-style-one" type="submit"><i
                                        class="fa fa-envelope"></i> {{__('Submit')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')

@endsection
