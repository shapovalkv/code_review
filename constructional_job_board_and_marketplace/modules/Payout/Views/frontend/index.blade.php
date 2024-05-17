@extends('layouts.user')

@section('content')
    <div class="row">
        <div class="col-md-9">
            <div class="upper-title-box">
                <h3>{{__("Payouts")}}</h3>
                <div class="text">{{ __("Ready to jump back in?") }}</div>
            </div>
        </div>
    </div>
    @include('admin.message')
    <div class="row">
        <?php
            $vendor_payout_methods = setting_item_array('vendor_payout_methods');
        ?>
        <div class="col-lg-12">
            <div class="ls-widget">
                <div class="tabs-box row widget-title">
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
                        @include("Payout::frontend.setup")
                    </div>
                </div>
            </div>
            <!-- Ls widget -->
            <div class="ls-widget">
                <div class="tabs-box">
                    <div class="widget-title">
                        <h4>{{__("Payout history")}}</h4>

                        <div class="chosen-outer">
                            <form method="get" class="default-form form-inline" action="{{ route('payout.candidate.index') }}">
                                <div class="form-group mb-0 mr-1">
                                    <select name="payout_method" class="form-control">
                                        <option value="">--{{ __("All") }}--</option>
                                        @foreach($vendor_payout_methods as $k=>$method)
                                            <option @if(request()->get('payout_method') == $method['id']) selected @endif value="{{ $method['id'] }}">{{ $method['id'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="theme-btn btn-style-one">{{ __("Search") }}</button>
                            </form>
                        </div>
                    </div>
                    <div class="widget-content">
                        <div class="table-outer">
                            <table class="default-table manage-job-table">
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

                                @if($payouts->count() > 0)
                                    @foreach($payouts as $payout)
                                        <tr>
                                            <td>#{{$payout->id}}</td>
                                            <td>{{format_money($payout->total)}}</td>
                                            <td>{{$payout->method_name}}</td>
                                            <td>{{display_date($payout->created_at)}}</td>
                                            <td><span class="badge badge-{{ $payout->status }}">{{ $payout->status }}</span></td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr><td colspan="7" class="text-center">{{ __("No Items") }}</td></tr>
                                @endif
                                </tbody>
                            </table>
                            <div class="ls-pagination">
                                {{$payouts->appends(request()->query())->links()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection

@section ('footer')
    <script type="text/javascript" src="{{ asset('module/vendor/js/payout.js?_ver='.config('app.version')) }}"></script>
@endsection
