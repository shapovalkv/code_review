@extends('layouts.user')

@php
    $manifestDir = 'dist/frontend';
@endphp

@section('content')
    @php
        $languages = \Modules\Language\Models\Language::getActive();
    @endphp
    <form
        id="mainForm"
        method="post"
        action="{{ route('seller.equipment.store', ['id'=>($row->id) ? $row->id : '-1','lang'=>request()->query('lang')] ) }}"
        class="default-form post-form"
    >
        @csrf
        <input type="hidden" name="id" value="{{$row->id}}">
        <div class="upper-title-box">
            <div class="row">
                <div class="col-md-9">
                    <h3>{{$row->id ? __('Edit: ').$row->title : __('Add new equipment')}}</h3>
                    <div class="text">
                        @if($row->slug)
                            <p class="item-url-demo">{{__("Permalink")}}: {{ url('equipment' ) }}/<a href="#" class="open-edit-input" data-name="slug">{{$row->slug}}</a>
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @include('admin.message')

        @if($row->id)
            @include('Language::admin.navigation')
        @endif

        <div class="row">
            <div class="col-lg-9">
                <div class="post-form__left-col">
                <!-- Ls widget -->
                    <div class="ls-widget mb-0 mb-md-4 mb-lg-0">
                        <div class="tabs-box">
                            <div class="widget-title"><h4>{{ __("Equipment content") }}</h4></div>
                            <div class="widget-content">
                                <div class="form-group">
                                    <label>{{__("Equipment title")}} <span class="text-danger">*</span></label>
                                    <input
                                        type="text"
                                        name="title"
                                        value="{{old('title',$translation->title)}}"
                                        required
                                        placeholder="{{__("Name of the equipment")}}"
                                        class="form-control js-required-input"
                                    >
                                </div>

                                <div class="form-group">
                                    <label>{{__("Price")}} <span class="text-danger">*</span></label>
                                    <input
                                        type="number"
                                        name="price"
                                        min="1"
                                        max="10000000"
                                        class="form-control js-required-input"
                                        required
                                        value="{{$row->price}}"
                                        placeholder="{{__('Price')}}"
                                    >
                                </div>

                                <div class="form-group">
                                    <label>{{ __("Equipment description") }}</label>
                                    <textarea name="content" class="d-none has-ckeditor" cols="30" rows="10">{{ $translation->content }}</textarea>
                                </div>

                                @if(is_default_lang())
                                    <div class="form-group">
                                        <label>{{__("Search Tags")}}</label>
                                        <div class="">
                                            <input type="text" data-role="tagsinput" value="{{$row->tag}}" placeholder="{{ __('Enter tag')}}" name="tag" class="form-control tag-input">
                                            <div class="show_tags">
                                                @if(!empty($tags))
                                                    @foreach($tags as $tag)
                                                        <span class="tag_item">{{$tag->name}}<span data-role="remove"></span>
                                                            <input type="hidden" name="tag_ids[]" value="{{$tag->id}}">
                                                        </span>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <p class="text-right mb-0"><small>{{__("10 tags maximum")}}</small></p>
                                    </div>
                                @endif

                                @if(is_default_lang())
                                    <!-- Ls widget -->
                                    <div class="ls-widget location">
                                        <div class="tabs-box">
                                            <div class="widget-title">
                                                <h4>{{ __("Location") }}<span class="text-danger">*</span></h4>
                                            </div>

                                            <div class="widget-content">
                                                <div class="form-group">
                                                    <label class="control-label">{{__("Location")}}</label>
                                                    <input
                                                        type="text"
                                                        placeholder="{{__("location")}}"
                                                        class="bravo_searchbox form-control js-required-input"
                                                        required
                                                        name="map_location_visible"
                                                        autocomplete="off"
                                                        onkeydown="return event.key !== 'Enter';"
                                                        value="{{ old('map_location', $row->location->map_location ?? '') }}"
                                                    >
                                                    <input
                                                        type="hidden"
                                                        name="map_location"
                                                        class="form-control js-hidden-location"
                                                        autocomplete="off"
                                                        onkeydown="return event.key !== 'Enter';"
                                                        value="{{ old('map_location', $row->location->map_location ?? '') }}"
                                                        @keydown.enter.prevent
                                                    >
                                                </div>
                                                <div class="form-group m-0">
                                                    <label class="control-label">{{__("The geographic coordinate")}}</label>
                                                    <div class="control-map-group">
                                                        <div id="map_content"></div>
                                                        <input type="hidden" name="map_lat" value="{{old('map_lat', $row->location->map_lat ?? "") }}">
                                                        <input type="hidden" name="map_lng" value="{{old('map_lng', $row->location->map_lng ?? "")}}">
                                                        <input type="hidden" name="map_zoom" value="{{old('map_zoom', $row->location->map_zoom ?? "8")}}">
                                                        <input type="hidden" name="map_state" value="{{old('map_state', $row->location->map_state ?? "")}}">
                                                        <input type="hidden" name="map_state_long" value="{{old('map_state_long', $row->location->map_state_long ?? "")}}">
                                                        <input type="hidden" name="map_city" value="{{old('map_city', $row->location->map_city ?? "")}}">
                                                        <input type="hidden" name="map_address" value="{{old('map_address', $row->location->map_address ?? "")}}">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="gallary">
                                    <h4>{{__("Gallery")}} </h4>
                                    <div class="form-group">
                                        <label class="control-label">({{__('Recommended size image:1080 x 1920px')}})</label>
                                        @php
                                            $gallery_id = $row->gallery ?? old('gallery');
                                        @endphp
                                        {!! \Modules\Media\Helpers\FileHelper::fieldGalleryUpload('gallery', $gallery_id) !!}
                                    </div>

                                    <div class="form-group m-md-0">
                                        <label class="control-label">{{__("Video Url")}}</label>
                                        <input
                                            type="text"
                                            name="video_url"
                                            class="form-control js-input-video-url"
                                            value="{{old('video',$row->video_url)}}"
                                            placeholder="{{__("Youtube link video")}}"
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @php
                        $packages = old('packages',$translation->packages);
                    @endphp

{{--                    @include('Core::frontend/seo-meta/seo-meta')--}}
                </div>
            </div>

            <div class="col-lg-3 post-form__right-col-wrap">
                <div class="post-form__right-col">
                    <div class="row">
                        @if(is_default_lang())
                            <div class="col-md-6 col-lg-12">
                                <div class="ls-widget mb-0">
                                    <div class="tabs-box">
                                        <div class="widget-title"><h4>{{__("Category")}} <span class="text-danger">*</span></h4></div>
                                        <div class="widget-content">
                                        <div class="form-group mb-0">
                                            <select name="cat_id" required class="form-control select js-required-input">
                                                <option value="">{{__("-- Select a Category--")}}</option>
                                                <?php
                                                $items = \Modules\Equipment\Models\EquipmentCategory::query()->whereNull('parent_id')->get();
                                                ?>
                                                @foreach($items as $item)
                                                    <option @if(old('cat_id',$row->cat_id) == $item->id) selected @endif value="{{$item->id}}">{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="col-md-6 col-lg-12">
                            <div class="ls-widget ls-widget--border-top m-md-0 p-md-0 border-md-0 mb-0 rounded-0">
                                <div class="tabs-box">
                                    <div class="widget-title"><h4>{{ __("Feature Image") }}</h4></div>
                                    <div class="widget-content">
                                        <div class="form-group mb-0">
                                            {!! \Modules\Media\Helpers\FileHelper::fieldUpload('image_id',$row->image_id) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @foreach ($attributes as $attribute)
                            <div class="ls-widget">
                                <div class="widget-title"><strong>{{__('Attribute: :name',['name'=>$attribute->name])}}</strong></div>
                                <div class="widget-content">
                                    <div class="form-group terms-scrollable">
                                        @foreach($attribute->terms as $term)
                                            <label class="term-item">
                                                <input @if(!empty($selected_terms) and $selected_terms->contains($term->id)) checked @endif type="checkbox" name="terms[]" value="{{$term->id}}">
                                                <span class="term-name">{{$term->name}}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col px-md-0 post-form__send-btn-col">
                <div class="post-form__send-btn-wrap">
                    <button id="submitFormBtn" class="post-form__send-btn f-btn primary-btn theme-btn btn-style-one">
                        {{__('post an equipment')}}

                        <svg width="28" height="8" viewBox="0 0 28 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M28 4L23 8V0L28 4Z" fill="white"/>
                            <path d="M23 3.58011H0V4.41989H23V3.58011Z" fill="white"/>
                        </svg>
                    </button>

                    @if($row->slug)
                        <a
                            class="post-form__send-btn f-btn secondary-btn theme-btn btn-style-onee"
                            href="{{$row->getDetailUrl(request()->query('lang'))}}"
                            target="_blank"
                        >{{__("View equipment")}}</a>
                    @endif
                </div>
            </div>
        </div>
    </form>
@endsection

@section('footer')
    {!! App\Helpers\MapEngine::scripts() !!}
    <script src="{{ mix('js/bootstrap5.js', $manifestDir) }}"></script>
    <script type="text/javascript" src="{{url('module/core/js/form-validation-engine.js?_ver='.config('app.version'))}}"></script>

    <script>
        jQuery(function ($) {
            "use strict"
            var on_load = true;
            $('[name=cat_id]').on('change',function (){
                $('[name="cat2_id"] option').show().hide();
                $('[name="cat2_id"] [data-parent="'+$(this).val()+'"]').show();
                if(!on_load){
                    $('[name="cat2_id"] option:eq(0)').prop('selected', true);
                    $('[name="cat3_id"] option:eq(0)').prop('selected', true);
                }
                $('[name="cat2_id"]').trigger("change");
                on_load = false;
            }).trigger('change')

            $('[name=cat2_id]').on('change',function (){
                $('[name="cat3_id"] option').show().hide();
                $('[name="cat3_id"] [data-parent="'+$(this).val()+'"]').show();
            }).trigger('change');

            $('.open-edit-input').on('click', function (e) {
                e.preventDefault();
                $(this).replaceWith('<input type="text" name="' + $(this).data('name') + '" value="' + $(this).html() + '">');
            });
            // map init
            jQuery(function ($) {
                new BravoMapEngine('map_content', {
                    disableScripts: true,
                    fitBounds: true,
                    center: [{{old('map_lat', $row->location->map_lat ?? $row->map_lat) ?? "38.896714696640004"}}, {{old('map_lng', $row->location->map_lng ?? $row->map_lng) ?? "-77.04821945173418"}}],
                    zoom: {{old('map_zoom', $row->location->map_zoom ?? $row->map_zoom) ?? "8"}},
                    ready: function (engineMap) {
                        @if(old('map_lat', $row->location->map_lat ?? $row->map_lat) && old('map_lng', $row->location->map_lng ?? $row->map_lng))
                        engineMap.addMarker([{{old('map_lat', $row->location->map_lat ?? $row->map_lat)}}, {{old('map_lng', $row->location->map_lng ?? $row->map_lng)}}], {
                            icon_options: {}
                        });
                        @endif
                        engineMap.on('click', function (dataLatLng) {
                            engineMap.clearMarkers();
                            engineMap.addMarker(dataLatLng, {
                                icon_options: {}
                            });
                            const { city, state, state_long, address } = dataLatLng[3]

                            $("input[name=map_lat]").val(dataLatLng[0]);
                            $("input[name=map_lng]").val(dataLatLng[1]);
                            $("input[name=map_location]").val(`${address} ${city} ${state}`.trim());
                            $("input[name=map_location_visible]").val(`${address} ${city} ${state}`.trim());
                            $("input[name=map_state]").val(state);
                            $("input[name=map_state_long]").val(state_long);
                            $("input[name=map_city]").val(city);
                            $("input[name=map_address]").val(address);
                        });
                        engineMap.on('zoom_changed', function (zoom) {
                            $("input[name=map_zoom]").attr("value", zoom);
                        });
                        engineMap.searchBox($('#customPlaceAddress'), function (dataLatLng) {
                            engineMap.clearMarkers();
                            engineMap.addMarker(dataLatLng, {
                                icon_options: {}
                            });
                            $("input[name=map_lat]").attr("value", dataLatLng[0]);
                            $("input[name=map_lng]").attr("value", dataLatLng[1]);
                        });
                        engineMap.autocomplete($('.bravo_searchbox'),function (dataLatLng) {
                            engineMap.clearMarkers();
                            engineMap.addMarker(dataLatLng, {
                                icon_options: {}
                            });
                            const { city, state, state_long, address } = dataLatLng[3]

                            $("input[name=map_lat]").val(dataLatLng[0]);
                            $("input[name=map_lng]").val(dataLatLng[1]);
                            $("input[name=map_location]").val($("input[name=map_location_visible]").val());
                            $("input[name=map_state]").val(state);
                            $("input[name=map_state_long]").val(state_long);
                            $("input[name=map_city]").val(city);
                            $("input[name=map_address]").val(address);
                        });
                    }
                });
            })

            $('.bravo_searchbox').on('input', () => {
                $('.js-hidden-location').val(null)
            })

            // Tag input
            $('.tag-input').on('keypress',function (e) {
                if(e.keyCode == 13){
                    var val = $(this).val();
                    if(val){
                        var html = '<span class="tag_item">' + val +
                            '       <span data-role="remove"></span>\n' +
                            '          <input type="hidden" name="tag_name[]" value="'+val+'">\n' +
                            '       </span>';
                        $(this).parent().find('.show_tags').append(html);
                        $(this).val('');
                    }
                    e.preventDefault();
                    return false;
                }
            });

            $(document).on('click','[data-role=remove]',function () {
                $(this).closest('.tag_item').remove();
            });

            $(".form-group-item").each(function () {
                let container = $(this);
                $(this).on('click','.btn-remove-item',function () {
                    $(this).closest(".item").remove();
                });

                $(this).on('press','input,select',function () {
                    let value = $(this).val();
                    $(this).attr("value",value);
                });
            });
            $(".form-group-item .btn-add-item").on('click',function () {
                var p = $(this).closest(".form-group-item").find(".g-items");

                let number = $(this).closest(".form-group-item").find(".g-items .item:last-child").data("number");
                if(number === undefined) number = 0;
                else number++;
                let extra_html = $(this).closest(".form-group-item").find(".g-more").html();
                extra_html = extra_html.replace(/__name__=/gi, "name=");
                extra_html = extra_html.replace(/__number__/gi, number);
                p.append(extra_html);

                if(extra_html.indexOf('dungdt-select2-field-lazy') >0 ){

                    p.find('.dungdt-select2-field-lazy').each(function () {
                        var configs = $(this).data('options');
                        $(this).select2(configs);
                    });
                }
            });

            window.initValidationForm()
        })
    </script>
@endsection
