<!-- Filter Block -->
<div class="filter-block">
    <h4>{{ $val['title'] }}</h4>
    <div class="form-group">
        <input type="text" name="zipcode" value="{{ request()->input('zipcode') }}" placeholder="{{ __("Zip code") }}">
        <span class="icon flaticon-pin"></span>
    </div>
</div>
