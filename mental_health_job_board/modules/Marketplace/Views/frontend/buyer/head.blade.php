<section class="page-title">
    <div class="auto-container">
        <div class="title-outer">
            <h1>{{__("My Marketplace Orders")}}</h1>
        </div>
    </div>
</section>
<div class="auto-container">
    <div class="mt-3 mb-5">
        <div class="default-tabs style-two tabs-box">
            <ul class="tab-buttons clearfix">

                <li class="@if(request()->get('status') == \Modules\Marketplace\Models\MarketplaceOrder::INCOMPLETE) active-btn @endif"><a href="{{ route('buyer.orders',['status'=>\Modules\Marketplace\Models\MarketplaceOrder::INCOMPLETE]) }}">{{__("Incomplete")}}</a></li>
                <li class="@if(request()->get('status') == \Modules\Marketplace\Models\MarketplaceOrder::IN_PROGRESS) active-btn @endif"><a href="{{ route('buyer.orders',['status'=>\Modules\Marketplace\Models\MarketplaceOrder::IN_PROGRESS]) }}">{{__("In Progress")}}</a></li>
                <li class="@if(request()->get('status') == \Modules\Marketplace\Models\MarketplaceOrder::DELIVERED) active-btn @endif"><a href="{{ route('buyer.orders',['status'=>\Modules\Marketplace\Models\MarketplaceOrder::DELIVERED]) }}">{{__("Delivered")}}</a></li>
                <li class="@if(request()->get('status') == \Modules\Marketplace\Models\MarketplaceOrder::IN_REVISION) active-btn @endif"><a href="{{ route('buyer.orders',['status'=>\Modules\Marketplace\Models\MarketplaceOrder::IN_REVISION]) }}">{{__("In Revision")}}</a></li>
                <li class="@if(request()->get('status') == \Modules\Marketplace\Models\MarketplaceOrder::COMPLETED) active-btn @endif"><a href="{{ route('buyer.orders',['status'=>\Modules\Marketplace\Models\MarketplaceOrder::COMPLETED]) }}">{{__("Completed")}}</a></li>
                <li class="@if(request()->get('status') == \Modules\Marketplace\Models\MarketplaceOrder::CANCELLED) active-btn @endif"><a href="{{ route('buyer.orders',['status'=>\Modules\Marketplace\Models\MarketplaceOrder::CANCELLED]) }}">{{__("Cancelled")}}</a></li>
                <li class="@if(request()->get('status') == '') active-btn @endif"><a href="{{route('buyer.orders')}}">{{__("All")}}</a></li>
            </ul>
        </div>
    </div>
</div>
