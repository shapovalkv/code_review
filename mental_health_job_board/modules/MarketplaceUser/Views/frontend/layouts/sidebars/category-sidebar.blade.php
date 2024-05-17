<form method="get" >
    @if(request()->get('_layout'))
        <input type="hidden" name="_layout" value="{{$layout}}" />
    @endif
    @php
        $marketplace_user_sidebar_search_fields = setting_item_array('marketplace_user_sidebar_search_fields');
        $marketplace_user_sidebar_search_fields = array_values(\Illuminate\Support\Arr::sort($marketplace_user_sidebar_search_fields, function ($value) {
            return $value['position'] ?? 0;
        }));
    @endphp
    @if($marketplace_user_sidebar_search_fields)
        @foreach($marketplace_user_sidebar_search_fields as $key => $val)
            @php $val['title'] = $val['title_'.app()->getLocale()] ?? $val['title'] ?? "" @endphp
            @include("MarketplaceUser::frontend.layouts.sidebars.fields." . $val['type'])
        @endforeach
    @endif
    <div class="wrapper-submit flex-middle col-xs-12 col-md-12">
        <button type="submit" class="theme-btn btn-style-one bg-blue">{{ __("Find MarketplaceUsers") }}</button>
    </div>
</form>
