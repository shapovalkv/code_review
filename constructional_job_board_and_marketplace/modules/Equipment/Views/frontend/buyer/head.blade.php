<section class="page-title">
    <div class="auto-container">
        <div class="title-outer">
            <h1>{{__("My equipment Orders")}}</h1>
        </div>
    </div>
</section>
<div class="auto-container">
    <div class="mt-3 mb-5">
        <div class="default-tabs style-two tabs-box">
            <ul class="tab-buttons clearfix">

                <li class="@if(request()->get('status') == \Modules\equipment\Models\equipmentOrder::INCOMPLETE) active-btn @endif"><a href="{{ route('buyer.orders',['status'=>\Modules\equipment\Models\equipmentOrder::INCOMPLETE]) }}">{{__("Incomplete")}}</a></li>
                <li class="@if(request()->get('status') == \Modules\equipment\Models\equipmentOrder::IN_PROGRESS) active-btn @endif"><a href="{{ route('buyer.orders',['status'=>\Modules\equipment\Models\equipmentOrder::IN_PROGRESS]) }}">{{__("In Progress")}}</a></li>
                <li class="@if(request()->get('status') == \Modules\equipment\Models\equipmentOrder::DELIVERED) active-btn @endif"><a href="{{ route('buyer.orders',['status'=>\Modules\equipment\Models\equipmentOrder::DELIVERED]) }}">{{__("Delivered")}}</a></li>
                <li class="@if(request()->get('status') == \Modules\equipment\Models\equipmentOrder::IN_REVISION) active-btn @endif"><a href="{{ route('buyer.orders',['status'=>\Modules\equipment\Models\equipmentOrder::IN_REVISION]) }}">{{__("In Revision")}}</a></li>
                <li class="@if(request()->get('status') == \Modules\equipment\Models\equipmentOrder::COMPLETED) active-btn @endif"><a href="{{ route('buyer.orders',['status'=>\Modules\equipment\Models\equipmentOrder::COMPLETED]) }}">{{__("Completed")}}</a></li>
                <li class="@if(request()->get('status') == \Modules\equipment\Models\equipmentOrder::CANCELLED) active-btn @endif"><a href="{{ route('buyer.orders',['status'=>\Modules\equipment\Models\equipmentOrder::CANCELLED]) }}">{{__("Cancelled")}}</a></li>
                <li class="@if(request()->get('status') == '') active-btn @endif"><a href="{{route('buyer.orders')}}">{{__("All")}}</a></li>
            </ul>
        </div>
    </div>
</div>
