<div class="sort-by">
    <form class="bc-form-order" action="{{ route("companies.index") }}" method="get">
        <input type="hidden" name="s" value="{{ Request::query("s") }}">
        <input type="hidden" name="location_id" value="{{ Request::query('location_id') }}">
        @if(Request::query('terms'))
            @foreach(Request::query('terms') as $term)
                <input type="hidden" name="terms[]" value="{{ $term }}">
            @endforeach
        @endif
        <input type="hidden" name="from_date" value="{{ Request::query('from_date') }}">
        <input type="hidden" name="to_date" value="{{ Request::query('to_date') }}">
        @if(Request::query('_layout'))
            <input type="hidden" name="_layout" value="{{ Request::query('_layout') }}">
        @endif
        <select class="chosen-select" name="orderby" onchange="this.form.submit()">
            <option value="default" @if(request()->get('orderby') == 'default') selected @endif>{{__('Sort by (Default)')}}</option>
            <option value="newest" @if(request()->get('orderby') == 'newest') selected @endif>{{__('Newest')}}</option>
            <option value="oldest" @if(request()->get('orderby') == 'oldest') selected @endif>{{__('Oldest')}}</option>
        </select>
        <select class="chosen-select" name="limit" onchange="this.form.submit()">
            <option value="10"
                    @if(request()->get('limit') == 10 || (empty(request()->get('limit')) && $list_search == 10)) selected @endif >{{ __("Show 10") }}</option>
            <option value="20"
                    @if(request()->get('limit') == 20 || (empty(request()->get('limit')) && $list_search == 20)) selected @endif >{{ __("Show 20") }}</option>
            <option value="30"
                    @if(request()->get('limit') == 30 || (empty(request()->get('limit')) && $list_search == 30)) selected @endif >{{ __("Show 30") }}</option>
            <option value="40"
                    @if(request()->get('limit') == 40 || (empty(request()->get('limit')) && $list_search == 40)) selected @endif >{{ __("Show 40") }}</option>
            <option value="50"
                    @if(request()->get('limit') == 50 || (empty(request()->get('limit')) && $list_search == 50)) selected @endif >{{ __("Show 50") }}</option>
            <option value="60"
                    @if(request()->get('limit') == 60 || (empty(request()->get('limit')) && $list_search == 60)) selected @endif >{{ __("Show 60") }}</option>
        </select>
    </form>
</div>
