<?php
$vendor_payout_methods = setting_item_array('vendor_payout_methods');
?>
@if($payout_account)
    <h4>{{__('Payout Account')}}</h4>

    <p>
        @foreach($payout_account->account_info as $val)
            {{$val}}
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
</style>
<div class="">
    <a href="#"  data-target="#vendor_payout_accounts" data-toggle="modal" class="btn btn-primary btn-sm">{{__("Setup accounts")}}</a>
</div>
<div class="modal bravo-form fade" tabindex="-1" role="dialog" id="vendor_payout_accounts" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{__("Setup payout account")}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body ">
                <div class="accordion" id="accordionPayout">
                    @foreach($vendor_payout_methods as $k=>$method)
                        @php ($method_id = $method['id'])
                        <div class="card">
                            <div class="card-header" id="heading_{{$k}}">
                                <h5 class="mb-0">
                                    <button class="btn btn-collapse @if($payout_account and $payout_account->payout_method != $method_id) collapsed @endif" data-toggle="collapse" data-target="#collapse_{{$k}}" aria-expanded="true" aria-controls="collapse{{$k}}">
                                        <input id="{{$method_id}}" @if($payout_account and $payout_account->payout_method == $method_id) checked @endif name="payout_method" value="{{$method_id}}" type="radio" class="form-check-input" required="">
                                        <label class="form-check-label" for="{{$method_id}}">{{$method['name'] ?? ''}}</label>
                                    </button>
                                </h5>
                            </div>
                            <div id="collapse_{{$k}}" class="collapse @if($payout_account and $payout_account->payout_method == $method_id) show @endif" aria-labelledby="heading_{{$k}}" data-parent="#accordionPayout">
                                <div class="card-body">
                                    <textarea name="account_info[{{$method_id}}]" class="form-control" cols="30" rows="3" placeholder="{{__("Your account info")}}">{{$payout_account->account_info[0] ?? ''}}</textarea>
                                </div>
                            </div>
                        </div>
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
