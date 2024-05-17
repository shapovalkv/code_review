<div class="ls-widget mb-md-0">
    <div class="tabs-box">
        <div class="widget-title"><h4>{{ __("Your Order") }}</h4></div>

        <div class="order-box">
            <table class="default-table">
                <thead>
                    <tr>
                        <th>{{__('Product')}}</th>
                        <th width="20%">{{__('Subtotal')}}</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($items as $cartItem)
                <tr class="cart-item">
                    <td class="product-name">
                        {{$cartItem->name}}
                        x {{$cartItem->qty}}
                        @if(!empty($cartItem->meta['package']))
                            <div class="mt-3">{{__('Package: ')}} {{package_key_to_name($cartItem->meta['package'])}} ({{format_money($cartItem->price)}})</div>
                        @endif
                        @if(!empty($cartItem->meta['extra_prices']))
                            <div class="mt-3"><strong>{{__("Extra Prices:")}}</strong></div>
                            <ul class="list-unstyled mt-2">
                                @foreach($cartItem->meta['extra_prices'] as $extra_price)
                                <li>{{$extra_price['name'] ?? '0'}} : {{format_money($extra_price['price'] ?? 0)}}</li>
                                @endforeach
                            </ul>
                        @endif
                    </td>
                    <td class="product-total">{{format_money($cartItem->subtotal)}}</td>
                </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr class="order-total">
                    <td>{{__('Total')}}</td>
                    <td><span class="amount">{{format_money(\Modules\Order\Helpers\CartManager::total())}}</span></td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
