<div class="order-box">
    <h3>{{__('Promo Code')}}</h3>
    <form class="form-row align-items-center" action="{{route('checkout.promocode')}}" method="post">
        @csrf
        <div class="input-group">
            <input type="text" value="{{old('promocode')}}" name="promocode" class="form-control" placeholder="{{__('XXXX-XXXX-XXXX')}}" style="height: 45px;line-height: 45px;">
            <div class="input-group-append">
                <button type="submit" class="btn btn-success" style="">Apply</button>
            </div>
        </div>
    </form>
</div>
