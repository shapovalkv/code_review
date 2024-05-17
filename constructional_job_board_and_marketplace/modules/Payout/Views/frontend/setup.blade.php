
@if($payout_account)
    <h4>{{__('Payout Account')}}</h4>

    <p>
        @foreach($payout_account as $val)
            @if($val->is_main == 1)
                {{$val->account_info}}
            @endif
        @endforeach
    </p>
@else
    <div class="alert bg-warning">{{__('Please setup your payout account')}}</div>
@endif
<style>

    #accordionPayout input[type=radio]{
        margin-top: 0.3rem;
        margin-left: -1.25rem;
    }
    #accordionPayout .btn-collapse:focus, #accordionPayout .btn-collapse:active:focus, #accordionPayout .btn-collapse.active:focus{
        outline:none;
        box-shadow:none;
    }
    #vendor_payout_accounts .close-modal{
        top: 35px;
    }
    #vendor_payout_accounts .card{
        height: unset;
    }
</style>
<div class="">
    <a href="#vendor_payout_accounts" rel="modal:open" class="btn btn-primary btn-sm">{{__("Setup accounts")}}</a>
</div>
<div class="modal bravo-form" id="vendor_payout_accounts">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{__("Setup payout account")}}</h5>
            </div>
            <div class="modal-body ">
                <div class="accordion" id="accordionPayout">
                    @foreach($vendor_payout_methods as $k=>$method)
                        @php ($method_id = $method['id'])
                            @if(count($payout_account) > 0)
                                @foreach($payout_account as $account)
                                    @if($account->payout_method == $method_id)
                                        <div class="card">
                                            <div class="card-header" id="heading_{{$k}}">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-collapse @if($account->is_main == 1) collapsed @endif" data-toggle="collapse" data-target="#collapse_{{$k}}" aria-expanded="true" aria-controls="collapse{{$k}}">
                                                        <input id="{{$method_id}}" @if($account->is_main == 1) checked @endif name="payout_method" value="{{$method_id}}" type="radio" class="form-check-input" required="">
                                                        <label class="form-check-label" for="{{$method_id}}">{{$method['name'] ?? ''}}</label>
                                                    </button>
                                                </h5>
                                            </div>
                                            <div id="collapse_{{$k}}" class="collapse @if($account->is_main == 1) show @endif" aria-labelledby="heading_{{$k}}" data-parent="#accordionPayout">
                                                <div class="card-body">
                                                    <textarea name="account_info[{{$method_id}}]" class="form-control" cols="30" rows="3" placeholder="{{__("Your account info")}}">{{ $account->account_info }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                            <div class="card">
                                <div class="card-header" id="heading_{{$k}}">
                                    <h5 class="mb-0">
                                        <button class="btn btn-collapse " data-toggle="collapse" data-target="#collapse_{{$k}}" aria-expanded="true" aria-controls="collapse{{$k}}">
                                            <input id="{{$method_id}}" name="payout_method" value="{{$method_id}}" type="radio" class="form-check-input" required="">
                                            <label class="form-check-label" for="{{$method_id}}">{{$method['name'] ?? ''}}</label>
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapse_{{$k}}" class="collapse " aria-labelledby="heading_{{$k}}" data-parent="#accordionPayout">
                                    <div class="card-body">
                                        <textarea name="account_info[{{$method_id}}]" class="form-control" cols="30" rows="3" placeholder="{{__("Your account info")}}"></textarea>
                                    </div>
                                </div>
                            </div>
                            @endif
                    @endforeach
                </div>
                <div class="message_box alert d-none"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                <button type="button" class="btn btn-success " onclick="vendorPayout.saveAccounts(this)">{{__('Save changes')}}
                    <i class="fa fa-spinner"></i>
                </button>
            </div>
        </div>
    </div>
</div>
