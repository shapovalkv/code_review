@extends('admin.layouts.app')

@section('content')
    <form
        action="{{route('marketplace.admin.store',['id'=>($row->id) ? $row->id : '-1','lang'=>request()->query('lang')])}}"
        method="post">
        @csrf
        <div class="container-fluid">
            <div class="d-flex justify-content-between mb20">
                <div class="">
                    <h1 class="title-bar">{{$row->id ? __('Edit: ').$row->title : __('Add new Announcement')}}</h1>
                    @if($row->slug)
                        <p class="item-url-demo">{{__("Permalink")}}: {{ url('Marketplace' ) }}/<a href="#"
                                                                                                 class="open-edit-input"
                                                                                                 data-name="slug">{{$row->slug}}</a>
                        </p>
                    @endif
                </div>
                <div class="">
                    @if($row->slug)
                        <a class="btn btn-primary btn-sm" href="{{$row->getDetailUrl(request()->query('lang'))}}"
                           target="_blank">{{__("View Marketplace")}}</a>
                    @endif
                </div>
            </div>
            @include('admin.message')
            @if($row->id)
                @include('Language::admin.navigation')
            @endif
            <div class="lang-content-box">
                <div class="row">
                    <div class="col-md-9">
                        <div class="panel">
                            <div class="panel-title"><strong>{{__("Overview")}}</strong></div>
                            <div class="panel-body">
                                @include('Marketplace::admin.marketplace.overview')
                            </div>
                        </div>
                        <div class="panel">
                            <div class="panel-title"><strong>{{__("Description")}}</strong></div>
                            <div class="panel-body">
                                @include('Marketplace::admin.marketplace.description')
                            </div>
                        </div>
                        <div class="panel">
                            <div class="panel-title"><strong>{{__("Marketplace location")}}</strong></div>
                            <div class="panel-body">
                                @include('Marketplace::admin.marketplace.location')
                            </div>
                        </div>
{{--                        <div class="panel">--}}
{{--                            <div class="panel-title"><strong>{{__("Requirements")}}</strong></div>--}}
{{--                            <div class="panel-body">--}}
{{--                                <p>{{__('Add questions to help buyers provide you with exactly what you need to start working on their order.')}}</p>--}}
{{--                                @include('Marketplace::admin.marketplace.requirements')--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        @if(is_default_lang())
                            <div class="panel">
                                <div class="panel-title"><strong>{{__("Gallery")}}</strong></div>
                                <div class="panel-body">
                                    <p>{{__('Showcase Your Services In A Marketplace Gallery')}}</p>
                                    @include('Marketplace::admin.marketplace.gallery')
                                </div>
                            </div>
                        @endif

                        @include('Core::admin/seo-meta/seo-meta')
                    </div>
                    <div class="col-md-3">
                        <div class="panel">
                            <div class="panel-title"><strong>{{__('Publish')}}</strong></div>
                            <div class="panel-body">
                                @if(is_default_lang())
                                    <div>
                                        <label><input @if($row->status=='publish') checked @endif type="radio"
                                                      name="status" value="publish"> {{__("Publish")}}
                                        </label></div>
                                    <div>
                                        <label><input @if($row->status=='draft') checked @endif type="radio"
                                                      name="status" value="draft"> {{__("Draft")}}
                                        </label></div>

                                    @if(!empty($marketplace_manage_others))
                                        <hr>
                                        <div class="form-group">
                                            <label>
                                                <input type="checkbox" name="is_featured" @if($row->is_featured) checked
                                                       @endif value="1"> {{__("Enable featured")}}
                                            </label>
                                        </div>
                                    @endif
                                @endif
                                <div class="text-right">
                                    <button class="btn btn-primary" type="submit"><i
                                            class="fa fa-save"></i> {{__('Save Changes')}}</button>
                                </div>
                            </div>
                        </div>

                        <div class="panel">
                            <div class="panel-title"><strong>{{__("Category")}}</strong></div>
                            <div class="panel-body">
                                    <div class="col-md-10">
                                        <select @if(!is_default_lang()) readonly @endif name="cat_id" required
                                                class="form-control">
                                            <option value=""> {{ __('-- Please Select --')}}</option>
                                            <?php
                                            $traverse = function ($categories, $prefix = '') use (&$traverse, $row) {
                                                foreach ($categories as $category) {
                                                    if ($category->id == $row->id) {
                                                        continue;
                                                    }
                                                    $selected = '';
                                                    if ($row->cat_id == $category->id)
                                                        $selected = 'selected';
                                                    printf("<option value='%s' %s>%s</option>", $category->id, $selected, $prefix . ' ' . $category->name);
                                                    $traverse($category->children, $prefix . '-');
                                                }
                                            };
                                            $traverse($categories);
                                            ?>
                                        </select>
                                </div>
                            </div>
                        </div>

                        <div class="panel">
                            <div class="panel-title"><strong>{{__("Training Location")}}</strong></div>
                            <div class="panel-body" id="locationStatus">
                                @if(is_admin())
                                        <?php $announcement_status = json_decode($row->announcement_status, true); ?>
                                    <div class="form-group">
                                        <label>
                                            <input type="checkbox" name="announcement_status[online]" value="1"
                                                   @if(!empty($announcement_status) && key_exists('online', $announcement_status)) checked @endif>
                                            {{ __("Online") }}
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label>
                                            <input type="checkbox" name="announcement_status[in_person]"
                                                   value="1"
                                                   @if(!empty($announcement_status) && key_exists('in_person', $announcement_status)) checked @endif>
                                            {{ __("In Person") }}
                                        </label>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if(is_admin())
                            <div class="panel">
                                <div class="panel-title"><strong>{{__("Company / Marketplace")}}</strong></div>
                                <div class="panel-body">
                                    <div class="form-group">
                                            <?php
                                            $author = !empty($row->author_id) ? \App\User::find($row->author_id) : false;
                                            \App\Helpers\AdminForm::select2('author_id', [
                                                'configs' => [
                                                    'ajax' => [
                                                        'url' => route('user.admin.getForSelect2ByRole') . '?' . http_build_query(['ids'=> [\Modules\User\Models\Role::EMPLOYER, \Modules\User\Models\Role::MARKETPLACE]]),
                                                        'dataType' => 'json'
                                                    ],
                                                    'allowClear' => true,
                                                    'placeholder' => __('-- Select User --')
                                                ]
                                            ], !empty($author->id) ? [
                                                $author->id,
                                                $author->name
                                            ] : false,
                                                true
                                            )
                                            ?>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @include('Marketplace::admin.marketplace.attributes')
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section ('script.body')
    {!! App\Helpers\MapEngine::scripts() !!}
    <script>
        const locationStatusСheckboxes = document.querySelectorAll('#locationStatus input[type="checkbox"]');

        // $('#locationStatus input[type="checkbox"]').prop('required', true);

 $(document).ready(function() {
  const isLocationChecked = Array.from(locationStatusСheckboxes).find(checkbox => checkbox.checked);
            if (isLocationChecked) {
                $('#locationStatus input[type="checkbox"]').prop('required', false);
            }
 })


        locationStatusСheckboxes.forEach(function(locationStatusСheckbox) {
            locationStatusСheckbox.addEventListener('change', function() {
                if (this.checked) {
                    $('#locationStatus input[type="checkbox"]').prop('required', false);
                    locationStatusСheckboxes.forEach(function(otherCheckbox) {
                        if (otherCheckbox !== locationStatusСheckbox) {
                            otherCheckbox.checked = false;
                        }
                    });
                } /*else {
                    $('#locationStatus input[type="checkbox"]').prop('required', true);
                }*/
            });
        });

        jQuery(function ($) {
            let mapLat = {{ !empty($row) ? ($row->map_lat ?? "34.0522") : "34.0522" }};
            let mapLng = {{ !empty($row) ? ($row->map_lng ?? "-118.244") : "-118.244" }};
            let mapZoom = {{ !empty($row) ? ($row->map_zoom ?? "12") : "12" }};


            new BravoMapEngine('map_content', {
                disableScripts: true,
                fitBounds: true,
                center: [mapLat, mapLng],
                zoom: mapZoom,
                ready: function (engineMap) {
                    // mapEngine = engineMap
                    engineMap.addMarker([mapLat, mapLng], {
                        icon_options: {}
                    });
                    engineMap.on('click', function (dataLatLng) {
                        engineMap.clearMarkers();
                        engineMap.addMarker(dataLatLng, {
                            icon_options: {}
                        });
                        $("input[name=map_lat]").attr("value", dataLatLng[0]);
                        $("input[name=map_lng]").attr("value", dataLatLng[1]);
                    });
                    engineMap.on('zoom_changed', function (zoom) {
                        $("input[name=map_zoom]").attr("value", zoom);
                    });
                    engineMap.searchBox($('#customPlaceAddress'),function (dataLatLng) {
                        engineMap.clearMarkers();
                        engineMap.addMarker(dataLatLng, {
                            icon_options: {}
                        });
                        $("input[name=map_lat]").attr("value", dataLatLng[0]);
                        $("input[name=map_lng]").attr("value", dataLatLng[1]);
                    });
                    engineMap.searchBox($('.bravo_searchbox'),function (dataLatLng) {
                        engineMap.clearMarkers();
                        engineMap.addMarker(dataLatLng, {
                            icon_options: {}
                        });
                        $("input[name=map_lat]").attr("value", dataLatLng[0]);
                        $("input[name=map_lng]").attr("value", dataLatLng[1]);
                    });

                    $('[name="location_id"]').on('change', function (e) {
                        const dataLatLng = [
                            $(this).attr('data-map_lat'),
                            $(this).attr('data-map_lng')
                        ]
                        const zoom = $(this).attr('data-map_zoom')

                        engineMap.clearMarkers();
                        engineMap.addMarker(dataLatLng, {
                            icon_options: {}
                        });
                        engineMap.map.setView(dataLatLng, zoom)

                        $("input[name=map_lat]").attr("value", dataLatLng[0])
                        $("input[name=map_lng]").attr("value", dataLatLng[1])
                    });
                }
            });

            $('#job_type_id').select2();
        })
    </script>
@endsection
