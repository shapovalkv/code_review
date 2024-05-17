<div class="order-box">
    <h3>{{__('Your Order')}}</h3>
    <table>
        <thead>
        <tr>
            <th><strong>{{__('Product')}}</strong></th>
            <th width="20%"><strong>{{__('Subtotal')}}</strong></th>
        </tr>
        </thead>
        <tbody>
        @foreach($items as $cartItem)
            @php
                    /** @var \Modules\Order\Models\OrderItem $cartItem */
                    /** @var \Modules\User\Models\Promocode $promocode */
                   $promocode = \Modules\User\Models\Promocode::query()->find($cartItem->promocode_id);
                   if ($cartItem->promocode_id) {
                       if((int)$cartItem->subtotal === 0) {
                            $originPrice = (int)($cartItem->meta['annual'] ?? 0) === 1 ? $cartItem->model()->first()->annual_price : $cartItem->model()->first()->price;
                       } else {
                            $originPrice = $promocode->is_percent ? $cartItem->subtotal / (1 - $promocode->value / 100) : $cartItem->subtotal + $promocode->value;
                       }
                   } else {
                       $originPrice = $cartItem->subtotal;
                   }
            @endphp
            <tr class="cart-item">
                <td class="product-name">
                    {{$cartItem->name}}
                    {{--                    x {{$cartItem->qty}}--}}
                    @if(!empty($cartItem->meta['package']))
                        <div class="mt-3">{{__('Package: ')}} {{package_key_to_name($cartItem->meta['package'])}}
                            ({{format_money($cartItem->price)}})
                        </div>
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
                <td class="product-total">{{format_money($originPrice)}}</td>
            </tr>
            @if($cartItem->promocode_id)
                <tr>
                    <td class="product-name">
                        {{$promocode->title}}
                    </td>
                    <td class="product-tota">-{{format_money(($promocode->is_percent ? $originPrice / 100 * $promocode->value : $promocode->value))}} @if($promocode->is_percent)(<small>{{round($promocode->value)}}%)</small>@endif</td>
                </tr>
            @endif
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
