@extends('layouts.user')
@section('head')

@endsection
@section('content')
    <div class="upper-title-box">
        <h3>{{__("Manage Orders")}}</h3>
        <div class="text">{{ __("Ready to jump back in?") }}</div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="ls-widget">
                <div class="tabs-box">
                    <div class="widget-title">
                        <h4>{{__("Manage Orders")}}</h4>
                    </div>
                    <div class="widget-content">
                        <div class="mb-4">
                            <div class="default-tabs style-two tabs-box">
                                <ul class="tab-buttons clearfix">
                                    <li class="@if(request()->get('status') == \Modules\Marketplace\Models\MarketplaceOrder::INCOMPLETE) active-btn @endif"><a href="{{ route('seller.orders',['status'=>\Modules\Marketplace\Models\MarketplaceOrder::INCOMPLETE]) }}">{{__("Incomplete")}}</a></li>
                                    <li class="@if(request()->get('status') == \Modules\Marketplace\Models\MarketplaceOrder::IN_PROGRESS) active-btn @endif"><a href="{{ route('seller.orders',['status'=>\Modules\Marketplace\Models\MarketplaceOrder::IN_PROGRESS]) }}">{{__("In Progress")}}</a></li>
                                    <li class="@if(request()->get('status') == \Modules\Marketplace\Models\MarketplaceOrder::DELIVERED) active-btn @endif"><a href="{{ route('seller.orders',['status'=>\Modules\Marketplace\Models\MarketplaceOrder::DELIVERED]) }}">{{__("Delivered")}}</a></li>
                                    <li class="@if(request()->get('status') == \Modules\Marketplace\Models\MarketplaceOrder::IN_REVISION) active-btn @endif"><a href="{{ route('seller.orders',['status'=>\Modules\Marketplace\Models\MarketplaceOrder::IN_REVISION]) }}">{{__("In Revision")}}</a></li>
                                    <li class="@if(request()->get('status') == \Modules\Marketplace\Models\MarketplaceOrder::COMPLETED) active-btn @endif"><a href="{{ route('seller.orders',['status'=>\Modules\Marketplace\Models\MarketplaceOrder::COMPLETED]) }}">{{__("Completed")}}</a></li>
                                    <li class="@if(request()->get('status') == \Modules\Marketplace\Models\MarketplaceOrder::CANCELLED) active-btn @endif"><a href="{{ route('seller.orders',['status'=>\Modules\Marketplace\Models\MarketplaceOrder::CANCELLED]) }}">{{__("Cancelled")}}</a></li>
                                    <li class="@if(request()->get('status') == '') active-btn @endif"><a href="{{route('seller.orders')}}">{{__("All")}}</a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="buyer-order">
                            <div class="table-outer table-responsive">
                                <table class="default-table manage-job-table">
                                    <thead>
                                    <tr>
                                        <th>{{__('Title')}}</th>
                                        <th>{{__('Created')}}</th>
                                        <th>{{__("Price")}}</th>
                                        <th>{{__("Status")}}</th>
                                        <th>{{__('Action')}}</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @if($rows && count($rows) > 0)
                                        @foreach($rows as $row)
                                            <tr>
                                                <td class="Marketplace-name">
                                                    @if(!empty($row->Marketplace->image_id))
                                                        {!! get_image_tag($row->Marketplace->image_id,'full',['alt'=>$row->Marketplace->title,'class'=>'Marketplace-img img-fluid lazy loaded']) !!}
                                                    @endif
                                                    <a href="{{$row->Marketplace ? $row->Marketplace->getDetailUrl() : '#'}}">{{$row->Marketplace->title ?? ''}}</a>
                                                </td>
                                                <td>{{display_date($row->created_at)}}</td>
                                                <td>{{format_money($row->price)}}</td>
                                                <td>
                                                   <span class="">{{$row->status_text}}</span>
                                                </td>
                                                <td class="order-detail"><a href="{{route('seller.order',['id'=>$row->id])}}" class="btn btn-success">{{__("View")}}</a></td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center">
                                                {{ __("No Items") }}
                                            </td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                                <div class="ls-pagination">
                                    {{$rows->appends(request()->query())->links()}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection