@extends('admin.layouts.app')

@section('content')

    <div class="container-fluid">
        <div class="d-flex justify-content-between mb20">
            <div class="">
                <h1 class="title-bar">{{ __('Support')}}</h1>
            </div>
        </div>
        @include('admin.message')
        <div class="panel">
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>{{ __("Subject") }}</th>
                            <th class="text-center">{{ __('User')}}</th>
                            <th class="text-center">{{ __('Messages')}}</th>
                            <th class="text-center">{{ __('Status')}}</th>
                            <th class="text-center">{{ __('Updated At')}}</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($tickets as $ticket)
                            <tr @if($ticket->status === \Modules\User\Models\Support\Ticket::COMPLETED) style="opacity: 0.5" @endif>
                                <td><a href="{{route('user.admin.support.show', ['ticket' => $ticket->id])}}"
                                       class="btn btn-link">{{$ticket->subject}}</a></td>
                                <td class="text-center">{{$ticket->user->name}}</td>
                                <td class="text-center">{{$ticket->messages()->count()}}</td>
                                <td class="text-center">
                                            <span class="badge @if($ticket->status === \Modules\User\Models\Support\Ticket::ANSWERED)
                                            badge-warning
                                            @elseif($ticket->status === \Modules\User\Models\Support\Ticket::COMPLETED)
                                            badge-dark
                                            @elseif($ticket->status === \Modules\User\Models\Support\Ticket::WAITING)
                                            badge-primary badge-style-one
                                            @else
                                            badge-default @endif">
                                            {{$ticket->status}}
                                            </span>
                                </td>
                                <td class="text-center">{{$ticket->updated_at->diffForHumans()}}</td>
                                <td>
                                  <a href="{{route('user.admin.support.show', ['ticket' => $ticket->id])}}" title="View ticket" class="btn btn-link">
                                      <span class="icon text-center"><i class="ion-md-eye"></i></span>
                                  </a>
                                    <form action="{{route('user.admin.support.delete', ['ticket' => $ticket->id])}}" method="post" style="display: inline;">
                                        @csrf
                                        <a onclick="if(confirm('Are you sure?')){$(this).parent().submit()}" title="Delete ticket" class="btn btn-link">
                                            <span class="icon text-center"><i class="ion-md-trash"></i></span>
                                        </a>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                {{$tickets->appends(request()->query())->links()}}
            </div>
        </div>
    </div>
@endsection

@section('script.body')

@endsection
