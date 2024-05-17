@if(!empty($equipment_related) && count($equipment_related) > 0)
    <div class="related-jobs">
        <div class="title-box">
            <h3>{{ __("Recommended For You") }}</h3>
        </div>
        <div class="row">
            @foreach($equipment_related as $row)
                <div class="col-md-4">
                    @include("equipment::frontend.search.loop")
                </div>
            @endforeach
        </div>
    </div>
@endif
