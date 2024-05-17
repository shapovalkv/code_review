@if(is_default_lang())
    <hr>
    <div class="row">
        <div class="col-sm-4">
            <h3 class="form-group-title">{{__('Payout Options')}}</h3>
        </div>
        <div class="col-sm-8">
            <div class="panel">
                <div class="panel-title"><strong>{{__("Payout Options")}}</strong></div>
                <div class="panel-body">
                    <div class="form-group">
                        <label><strong>{{__("Payout Methods")}}</strong></label>
                        <div class="form-controls">
                            <div class="form-group-item">
                                <div class="form-group-item">
                                    <div class="g-items-header">
                                        <div class="row">
                                            <div class="col-md-1">{{__('ID')}}</div>
                                            <div class="col-md-8">{{__("Name")}}</div>
                                            <div class="col-md-2">{{__('Order')}}</div>
                                            <div class="col-md-1"></div>
                                        </div>
                                    </div>
                                    <div class="g-items">
                                        <?php
                                        $items = json_decode(setting_item('vendor_payout_methods'));
                                        if(empty($items) or !is_array($items))
                                            $items = [];
                                        ?>
                                        @foreach($items as $key=>$item)
                                            <div class="item" data-number="{{$key}}">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <input placeholder="{{__('Eg: bank_transfer')}}" type="text" name="vendor_payout_methods[{{$key}}][id]" class="form-control" value="{{$item->id}}" >
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label >{{__("Name")}}</label>
                                                        <input type="text" name="vendor_payout_methods[{{$key}}][name]" class="form-control" value="{{$item->name ?? ''}}">
                                                        <label >{{__("Description")}}</label>
                                                        <textarea  name="vendor_payout_methods[{{$key}}][desc]" class="form-control" cols="30" rows="4">{{$item->desc ?? ''}}</textarea>
                                                        <label >{{__("Minimum to pay")}}</label>
                                                        <input type="text" name="vendor_payout_methods[{{$key}}][min]" class="form-control" value="{{$item->min ?? ''}}">
                                                    </div>

                                                    <div class="col-md-2">
                                                        <input type="number" name="vendor_payout_methods[{{$key}}][order]" class="form-control" value="{{$item->order ?? ''}}" >
                                                    </div>
                                                    <div class="col-md-1">
                                                        <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="text-right">
                                        <span class="btn btn-info btn-sm btn-add-item"><i class="icon ion-ios-add-circle-outline"></i> {{__('Add item')}}</span>
                                    </div>
                                    <div class="g-more hide">
                                        <div class="item" data-number="__number__">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input placeholder="{{__('Eg: bank_transfer')}}" type="text" __name__="vendor_payout_methods[__number__][id]" class="form-control" value="" >
                                                </div>
                                                <div class="col-md-6">
                                                    <label >{{__("Name")}}</label>
                                                    <input type="text" __name__="vendor_payout_methods[__number__][name]" class="form-control" value="">
                                                    <label >{{__("Description")}}</label>
                                                    <textarea  __name__="vendor_payout_methods[__number__][desc]" class="form-control" cols="30" rows="4"></textarea>
                                                    <label >{{__("Minimum to pay")}}</label>
                                                    <input type="text" __name__="vendor_payout_methods[__number__][min]" class="form-control" value="">
                                                </div>

                                                <div class="col-md-2">
                                                    <input type="number" __name__="vendor_payout_methods[__number__][order]" class="form-control" value="" >
                                                </div>
                                                <div class="col-md-1">
                                                    <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>

                    <div class="form-group">
                        <div class="form-controls">
                            <label ><strong>{{__("Disable Payout Module?")}}</strong></label>
                            <div class="form-group">
                                <label> <input type="checkbox" @if(setting_item('disable_payout') == 1) checked @endif name="disable_payout" value="1"> {{__("Yes, please disable it")}}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
