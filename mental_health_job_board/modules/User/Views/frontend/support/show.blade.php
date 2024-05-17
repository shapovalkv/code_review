@extends('layouts.user')
@section('head')
    <style>
        .support p.bg-primary > a {
            color: #fff;
            text-decoration: underline;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-9">
            <div class="upper-title-box">
                <h3>{{__('Support - creating issue')}}</h3>
            </div>
        </div>
    </div>
    @include('admin.message')
    <div class="row">
        <div class="col-lg-{{$ticket->status === \Modules\User\Models\Support\Ticket::COMPLETED ? 12 : 7 }}">
            <div class="ls-widget">
                <div class="tabs-box">
                    <div class="widget-title"><h4>{{$ticket->subject}}</h4></div>
                    <div class="widget-content support">
                        @php
                            $todayStarted = false;
                        @endphp
                        @foreach($ticket->messages as $message)
                            @if(false === $todayStarted && $message->created_at->isToday())
                                <div class="divider d-flex align-items-center mb-4">
                                    <p class="text-center mx-3 mb-0" style="color: #a2aab7;">Today</p>
                                </div>
                                @php
                                    $todayStarted = true;
                                @endphp
                            @endif
                            @if($message->user->id === auth()->user()->id)
                                <div class="d-flex flex-row justify-content-end mb-1 pt-1">
                                    @if($message->id === $ticket->messages->last()->id)
                                        <a href="{{route('user.support.message.edit', ['ticket' => $ticket->id, 'message' => $message->id])}}" title="Edit your message"><i
                                                class="la la-pencil pull-left"></i></a>
                                    @endif
                                    <div>
                                        <p class="small p-2 mr-3 text-white bg-primary"
                                           style="border-radius: 10px; margin-bottom: 0">{!! nl2br(strip_tags($message->content, '<a>')) !!}</p>
                                        <p class="small mr-3 mb-1 rounded-3 text-muted d-flex justify-content-end">{{$message->created_at->diffForHumans()}}</p>
                                    </div>
                                    <img src="{{$message->user->getAvatarUrl()}}"
                                         alt="{{$message->user->name}}"
                                         style="width: 45px; height: 45px; border-radius: 50%; min-width: 45px;">
                                </div>
                            @else
                                <div class="d-flex flex-row justify-content-start mb-1 pt-1">
                                    <img src="{{$message->user->getAvatarUrl()}}"
                                         alt="{{$message->user->name}}"
                                         style="width: 45px; height: 45px; border-radius: 50%">
                                    <div>
                                        <p class="small p-2 ml-3"
                                           style="background-color: #f5f6f7; border-radius: 10px; margin-bottom: 0">{!! nl2br(strip_tags($message->content, '<a>')) !!}</p>
                                        <p class="small ml-3 mb-1 text-muted">{{$message->created_at->diffForHumans()}}</p>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @if($ticket->status !== \Modules\User\Models\Support\Ticket::COMPLETED)
            <div class="col-lg-5">
                <div class="ls-widget">
                    <div class="tabs-box">
                        <div class="widget-title"><h4>{{ __("New message") }}</h4></div>
                        <div class="widget-content">
                            @if ($ticket->status === \Modules\User\Models\Support\Ticket::ANSWERED)
                                <form method="post"
                                      action="{{ route('user.support.message.store', ['ticket' => $ticket->id]) }}"
                                      class="default-form">
                                    @csrf
                                    <div class="form-group">
                                        <label class="control-label">{{__("New message")}} <span
                                                class="required">*</span></label>
                                        <div class="">
                                    <textarea name="content" class="d-none has-ckeditor" cols="30"
                                              rows="10">{{ old('content') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button class="theme-btn btn-style-one" type="submit"><i
                                                class="fa fa-envelope"></i> {{__('Submit')}}</button>
                                    </div>
                                </form>
                            @else
                                <p>Wait for an answer</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
@section('footer')

@endsection
