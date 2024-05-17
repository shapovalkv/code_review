<div class="row">
    <div class="col-sm-4">
        <h3 class="form-group-title">{{__("User Plans Options")}}</h3>
        <p class="form-group-desc">{{__('Config user plans page')}}</p>
    </div>
    <div class="col-sm-8">
        <div class="panel">
            <div class="panel-body">
                <div class="form-group">
                    <label>{{__("Page Title")}}</label>
                    <div class="form-controls">
                        <input type="text" name="user_plans_page_title" class="form-control"  value="{{setting_item_with_lang('user_plans_page_title',request()->query('lang')) ?? '' }}">
                    </div>
                </div>
                <div class="form-group">
                    <label>{{__("Page Sub Title")}}</label>
                    <div class="form-controls">
                        <input type="text" name="user_plans_page_sub_title" class="form-control"  value="{{setting_item_with_lang('user_plans_page_sub_title',request()->query('lang')) ?? '' }}">
                    </div>
                </div>
                <div class="form-group">
                    <label>{{__("Sale Of Text")}}</label>
                    <div class="form-controls">
                        <input type="text" name="user_plans_sale_text" class="form-control"  value="{{setting_item_with_lang('user_plans_sale_text',request()->query('lang')) ?? '' }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<hr>

<div class="row">
    <div class="col-sm-4">
        <h3 class="form-group-title">{{__('Content Email Expired User Plan')}}</h3>
        <div class="form-group-desc">{{ __('Content email expiration email send to Customer when plan expired.')}}
            @foreach(\Modules\User\Listeners\SendMailUserPlanExpired::CODE as $item=>$value)
                <div><code>{{$value}}</code></div>
            @endforeach
        </div>
    </div>
    <div class="col-sm-8">
        <div class="panel">
            <div class="panel-body">
                <div class="form-group">
                    <label>{{__("Subject")}}</label>
                    <div class="form-controls">
                        <input type="text" name="subject_email_user_plan_expired" class="form-control"  value="{{setting_item_with_lang('subject_email_user_plan_expired',request()->query('lang')) ?? '' }}">
                    </div>
                </div>
                <div class="form-group" >
                    <label>{{__("Content")}}</label>
                    <div class="form-controls">
                        <textarea name="content_email_user_plan_expired" class="d-none has-ckeditor" cols="30" rows="10">{{setting_item_with_lang('content_email_user_plan_expired',request()->query('lang')) ?? '' }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
