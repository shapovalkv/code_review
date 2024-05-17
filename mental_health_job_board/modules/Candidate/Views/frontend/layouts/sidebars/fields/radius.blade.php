<!-- Filter Block -->
<div class="filter-block">
    <h4><span id="amount">{{ $val['title'] }}: Start at {{ request()->get('radius') }} Miles</span></h4>
    <div class="range-slider-one radius">
        <input type="hidden" name="radius" value="{{ request()->get('radius') ?? 25 }}">
        <div id="radius-slider"></div>
    </div>
</div>
