@extends('layouts.user')
@section('head')

@endsection
@section('content')
    <div class="row">
        <div class="col-md-9 col-sm-6 col-6">
            <div class="upper-title-box">
                <h3>{{__('Support')}}</h3>
            </div>
        </div>
        <div class="col-md-3 col-6 text-right">
            <a class="theme-btn btn-style-seven" href="{{route('user.support.create')}}">{{__("Report Issue")}}</a>
        </div>
    </div>
    @include('admin.message')
    <div class="row">
        <div class="col-lg-12">
            <!-- Ls widget -->
            <div class="ls-widget">
                <div class="tabs-box">
                    <div class="widget-title">
                        <h4>{{ __("Manage Tickets") }}</h4>

                        <div class="chosen-outer">
                            <form method="get" class="default-form form-inline"
                                  action="{{ route('user.support.index') }}">
                                <!--Tabs Box-->
                                <div class="form-group mb-2 mr-md-2 width-xs-100">
                                    <input type="text" name="s" value="{{ request()->input('s') }}"
                                           placeholder="{{__('Search by subject')}}" class="form-control">
                                </div>
                                <button type="submit" class="theme-btn btn-style-ten width-xs-100">{{ __("Search") }}</button>
                            </form>
                        </div>
                    </div>
                    <div class="widget-content">
                        <div class="table-outer">
                            <table class="default-table manage-job-table">
                                <thead>
                                <thead>
                                <tr>
                                    <th>{{ __("Subject") }}</th>
                                    <th class="text-center">{{ __('Messages')}}</th>
                                    <th class="text-center">{{ __('Status')}}</th>
                                    <th class="text-center">{{ __('Updated At')}}</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($tickets as $ticket)
                                    <tr @if($ticket->status === \Modules\User\Models\Support\Ticket::COMPLETED) style="opacity: 0.5" @endif>
                                        <td><a href="{{route('user.support.show', ['ticket' => $ticket->id])}}" class="btn btn-link">{{$ticket->subject}}</a></td>
                                        <td class="text-center">{{$ticket->messages()->count()}}</td>
                                        <td class="text-center">
                                            <span class="badge @if($ticket->status === \Modules\User\Models\Support\Ticket::ANSWERED)
                                            badge-warning
                                            @elseif($ticket->status === \Modules\User\Models\Support\Ticket::COMPLETED)
                                            badge-dark
                                            @elseif($ticket->status === \Modules\User\Models\Support\Ticket::WAITING)
                                            badge-primary bg-style-eleven
                                            @else
                                            badge-default @endif">
                                            {{$ticket->status}}
                                            </span>
                                        </td>
                                        <td class="text-center">{{$ticket->updated_at->diffForHumans()}}</td>
                                        <td>
                                            <div class="option-box">
                                                <ul class="option-list">
                                                    <li><a href="{{route('user.support.show', ['ticket' => $ticket->id])}}" data-text="View ticket"><span class="la la-eye"></span></a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="ls-pagination">
                            {{$tickets->appends(request()->query())->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')

@endsection
