@extends('layouts.app')
@section("head")
    <link href="{{ asset('libs/flip/flip.min.css') }}" rel="stylesheet">
    <style>
        body.mm-wrapper{
            overflow-x: visible;
        }
        .bravo_wrap.page-wrapper{
            overflow: visible;
        }
    </style>
@endsection
@section('content')
    <div class="order-status-wrap">
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <div class="order-details">
                        {{ __("Order") }}: #{{ $order->id }}
                    </div>
                </div>
                <div class="col-6 text-right">
                    <div class="order-status">
                        {{ __("Status") }}: <span class="status-{{ $order->status }}">{{ $order->status_text }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="bc-Marketplace-order-details">
        <div class="container">
            @include("admin.message")
            <div class="order-flex">
                <div class="col-order-left">
                    <div class="default-tabs style-two tabs-box">
                        <ul class="tab-buttons clearfix">
                            <li class="@if($tab == 'activity') active-btn @endif"><a href="{{ route("seller.order.activity", [ 'id' => $order->id ]) }}">{{ __("Activity")}}</a></li>
                            @if(!empty($order->requirements))
                                <li class="@if($tab == 'requirements') active-btn @endif"><a href="{{ route("seller.order.requirements", [ 'id' => $order->id ]) }}">{{ __("Requirements")}}</a></li>
                            @endif
                        </ul>
                    </div>

                    @includeIf("Marketplace::frontend.seller.order.tab." . $tab)

                </div>
                @if($order->Marketplace)
                    @php
                        $disableCountdown = false;
                            if($order->status == \Modules\Marketplace\Models\MarketplaceOrder::COMPLETED || $order->status == \Modules\Marketplace\Models\MarketplaceOrder::CANCELLED || $order->status == \Modules\Marketplace\Models\MarketplaceOrder::INCOMPLETE){
                                $disableCountdown = true;
                            }
                    @endphp
                    <div class="col-order-right {{ !$disableCountdown ? 'has-countdown' : '' }}">
                        <div class="sticky-order-right">
                            @includeWhen(!$disableCountdown,"Marketplace::frontend.elements.order-countdown")

                            @include("Marketplace::frontend.elements.order-overview")
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection

@section('footer')
    <script type="text/javascript" src="{{ asset('module/Marketplace/js/Marketplace-order.js?_ver='.config('app.version')) }}"></script>
    <script type="text/javascript" src="{{ asset('libs/flip/flip.min.js') }}"></script>
    <script>
        function handleTickInit(tick) {
            // Uncomment to set labels to different language ( in this case Dutch )
            var locale = {
                DAY_PLURAL: '<?php echo __("Days") ?>',
                DAY_SINGULAR: '<?php echo __("Day") ?>',
                HOUR_PLURAL: '<?php echo __("Hours") ?>',
                HOUR_SINGULAR: '<?php echo __("Hour") ?>',
                MINUTE_PLURAL: '<?php echo __("Minutes") ?>',
                MINUTE_SINGULAR: '<?php echo __("Minutes") ?>',
                SECOND_PLURAL: '<?php echo __("Seconds") ?>',
                SECOND_SINGULAR: '<?php echo __("Second") ?>'
            };

            for (var key in locale) {
                if (!locale.hasOwnProperty(key)) { continue; }
                tick.setConstant(key, locale[key]);
            }
            var delivery_date = '<?php echo date('Y-m-d h:s:i', strtotime($order->delivery_date)) ?>';
            // var delivery_date = '1970-01-01 00:00:00';
            Tick.count.down(delivery_date).onupdate = function(value) {
                tick.value = value;
            };
        }
    </script>
@endsection
