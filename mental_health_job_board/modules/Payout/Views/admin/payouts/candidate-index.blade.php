@extends('admin.layouts.app')
@section('content')
    <section class="bc-dashboard">
        <div class="d-flex mb-3 align-items-center">
            <h1 class="me-3">{{__("Payouts")}}</h1>
        </div>
        <div class="panel">
            <div class="panel-body">
                @if($payout_account)
                    <div class="row">
                        <div class="col-md-8">
                            <h4 class="mb-4">{{__("Next Payout")}}</h4>
                            @if($current_payout)
                                <p class="lead">{{format_money($current_payout->total)}}</p>
                                <p class="lead">{{__("via :method_name",['method_name'=>$current_payout->method_name])}}</p>
                            @else
                                <p class="lead">{{__("You currently have :amount in earnings for next month's payout.",['amount'=>format_money($currentUser->availablePayoutAmount)])}}</p>
                            @endif
                        </div>
                        <div class="col-md-4">
                            @include("Payout::admin.payouts.setup")
                        </div>
                    </div>
                @else
                    @include("Payout::admin.payouts.setup")
                @endif
                <hr>
                <h4>{{__("Payout history")}}</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-booking-history">
                        <thead>
                        <tr>
                            <th width="2%">{{__("#")}}</th>
                            <th>{{__("Amount")}}</th>
                            <th>{{__("Payout Method")}}</th>
                            <th>{{__("Date Created")}}</th>
                            <th>{{__("Status")}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($payouts as $payout)
                            <tr>
                                <td>#{{$payout->id}}</td>
                                <th>{{format_money($payout->total)}}</th>
                                <td>
                                    {{$payout->method_name}}
                                </td>
                                <td>{{display_date($payout->created_at)}}</td>
                                <td>{{$payout->status}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="bravo-pagination">
                    {{$payouts->appends(request()->query())->links()}}
                </div>
            </div>
        </div>
    </section>
@endsection
@section ('script.body')
    <style>

    </style>
    <script type="text/javascript" src="{{ asset('module/vendor/js/payout.js?_ver='.config('app.version')) }}"></script>
@endsection
