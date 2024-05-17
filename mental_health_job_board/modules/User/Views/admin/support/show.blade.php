@extends('admin.layouts.app')

@section('content')

    <div class="container-fluid">
        <div class="d-flex justify-content-between mb20">
            <div class="">
                <h1 class="title-bar">{{ __('Support')}}</h1>
            </div>
        </div>
        <div class="filter-div d-flex justify-content-between ">
            <div class="col-left">

            </div>
            <div class="col-left">
                <form method="post" action="{{route('user.admin.support.status', ['ticket' => $ticket->id])}}"
                      class="filter-form filter-form-left d-flex justify-content-start">
                    @csrf
                    <input type="hidden" name="status" value="{{\Modules\User\Models\Support\Ticket::COMPLETED}}">
                    <button data-confirm="Do you want to close?" class="btn-danger btn btn-icon dungdt-apply-form-btn"
                            type="button">Close ticket
                    </button>
                </form>
            </div>
        </div>
        @include('admin.message')
        <div class="panel">
            <div class="panel-body">
                Subject: {{$ticket->subject}}
            </div>
        </div>
        <div class="panel">
            <div class="panel-body">
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
                                <div>
                                    <p class="small p-2 mr-3 text-white bg-primary"
                                       style="border-radius: 10px; margin-bottom: 0">{!! nl2br(strip_tags($message->content, '<a>')) !!}</p>
                                    <p class="small mr-3 mb-1 rounded-3 text-muted d-flex justify-content-end">{{$message->created_at->diffForHumans()}}</p>
                                </div>
                                <img src="{{$message->user->getAvatarUrl()}}"
                                     alt="{{$message->user->name}}"
                                     style="width: 45px; height: 45px; border-radius: 50%">
                            </div>
                        @else
                            <div class="d-flex flex-row justify-content-start mb-1 pt-1">
                                <img src="{{$message->user->getAvatarUrl()}}"
                                     alt="{{$message->user->name}}"
                                     style="width: 45px; height: 45px; border-radius: 50%">
                                <div>
                                    <p class="small p-2 ml-3"
                                       style="background-color: #f5f6f7; border-radius: 10px; margin-bottom: 0">{!! nl2br(strip_tags($message->content, ['a','b','strong','u','i','s'])) !!}</p>
                                    <p class="small ml-3 mb-1 text-muted">{{$message->created_at->diffForHumans()}}</p>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        <div class="panel">
            <div class="panel-body">
                <div class="widget-content">
                    <form method="post"
                          action="{{ route('user.admin.support.message.store', ['ticket' => $ticket->id]) }}"
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
                            <button class="btn btn-primary" type="submit"><i
                                    class="fa fa-envelope"></i> {{__('Submit')}}</button>
                        </div>
                    </form>
                    <div class="pull-right" style="margin-top: -50px;">
                        <form method="post" action="{{route('user.admin.support.status', ['ticket' => $ticket->id])}}"
                              class="filter-form filter-form-left d-flex justify-content-start">
                            @csrf
                            <input type="hidden" name="status" value="{{\Modules\User\Models\Support\Ticket::COMPLETED}}">
                            <button data-confirm="Do you want to close?" class="btn-danger btn btn-icon dungdt-apply-form-btn"
                                    type="button">Close ticket
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script.body')

@endsection
