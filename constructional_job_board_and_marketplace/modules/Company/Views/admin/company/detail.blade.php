@extends('admin.layouts.app')
@section('content')
    <?php
        $user = \Illuminate\Support\Facades\Auth::user();
    ?>
    <form action="{{route('company.admin.store',['id'=>($row->id) ? $row->id : '-1','lang'=>request()->query('lang')])}}" method="post" class="dungdt-form">
        <div class="container-fluid">
            <div class="d-flex justify-content-between mb20">
                <div class="">
                    <h1 class="title-bar">{{$row->id ? __('Edit Company :name',['name'=>$translation->name]) : __('Add new Company')}}</h1>
                    @if($row->slug)
                        <p class="item-url-demo">{{__("Permalink")}}: {{ url( (request()->query('lang') ? request()->query('lang').'/' : '').config('companies.companies_route_prefix'))  }}/<a href="#" class="open-edit-input" data-name="slug">{{$row->slug}}</a>
                        </p>
                    @endif
                </div>
                <div class="">
                    @if($row->slug)
                        <a class="btn btn-primary btn-sm" href="{{$row->getDetailUrl(request()->query('lang'))}}" target="_blank">{{__("View Company")}}</a>
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
                            <div class="panel-title"><strong>{{ __('Company content')}}</strong></div>
                            <div class="panel-body">
                                @csrf
                                @include('Company::admin/company/form',['row'=> $row])
                            </div>
                        </div>
                        @if(is_default_lang())
                        <div class="panel">
                            <div class="panel-title"><strong>{{__("Company Location")}}</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="control-label">{{__("Location")}}</label>
                                        <div class="form-group-smart-search">
                                            <div class="form-content">
                                                <div class="smart-search">
                                                    <input
                                                        type="text"
                                                        placeholder="{{__("Location")}}"
                                                        name="map_location"
                                                        class="bravo_searchbox form-control"
                                                        autocomplete="off"
                                                        onkeydown="return event.key !== 'Enter';"
                                                        value="{{ old('map_location', $row->location->map_location ?? '') }}"
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <div class="form-group">
                                    <label class="control-label">{{__("The geographic coordinate")}}</label>
                                    <div class="control-map-group">
                                        <div id="map_content"></div>
                                        <div class="g-control">
                                            <div class="form-group">
                                                <label>{{__("Map Latitude")}}:</label>
                                                <input
                                                    type="text"
                                                    name="map_lat"
                                                    class="form-control"
                                                    value="{{old('map_lat', $row->location->map_lat ?? '')}}"
                                                    readonly
                                                    onkeydown="return event.key !== 'Enter';"
                                                >
                                            </div>
                                            <div class="form-group">
                                                <label>{{__("Map Longitude")}}:</label>
                                                <input
                                                    type="text"
                                                    name="map_lng"
                                                    class="form-control"
                                                    value="{{old('map_lng', $row->location->map_lng ?? '')}}"
                                                    readonly
                                                    onkeydown="return event.key !== 'Enter';"
                                                >
                                            </div>
                                            <div class="form-group">
                                                <label>{{__("Map Zoom")}}:</label>
                                                <input
                                                    type="text"
                                                    name="map_zoom"
                                                    class="form-control"
                                                    value="{{old('map_zoom', $row->location->map_zoom ?? "8")}}"
                                                    readonly
                                                    onkeydown="return event.key !== 'Enter';"
                                                >
                                            </div>
                                            <div class="form-group">
                                                <label>{{__("Map State")}}:</label>
                                                <input
                                                    type="text"
                                                    name="map_state"
                                                    class="form-control"
                                                    value="{{old('map_state', $row->location->map_state ?? "")}}"
                                                    readonly
                                                    onkeydown="return event.key !== 'Enter';"
                                                >
                                            </div>
                                            <div class="form-group">
                                                <label>{{__("Map State Long")}}:</label>
                                                <input
                                                    type="text"
                                                    name="map_state_long"
                                                    class="form-control"
                                                    value="{{old('map_state_long', $row->location->map_state_long ?? "")}}"
                                                    readonly
                                                    onkeydown="return event.key !== 'Enter';"
                                                >
                                            </div>
                                            <div class="form-group">
                                                <label>{{__("Map City")}}:</label>
                                                <input
                                                    type="text"
                                                    name="map_city"
                                                    class="form-control"
                                                    value="{{old('map_city', $row->location->map_city ?? "")}}"
                                                    readonly
                                                    onkeydown="return event.key !== 'Enter';"
                                                >
                                            </div>
                                            <div class="form-group">
                                                <label>{{__("Map Address")}}:</label>
                                                <input
                                                    type="text"
                                                    name="map_address"
                                                    class="form-control"
                                                    value="{{old('map_address', $row->location->map_address ?? "")}}"
                                                    readonly
                                                    onkeydown="return event.key !== 'Enter';"
                                                >
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                                        <label><input @if($row->status=='publish') checked @endif type="radio" name="status" value="publish"> {{__("Publish")}}
                                        </label></div>
                                    <div>
                                        <label><input @if($row->status=='draft') checked @endif type="radio" name="status" value="draft"> {{__("Draft")}}
                                        </label></div>
                                @endif
                                <div class="text-right">
                                    <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> {{__('Save Changes')}}</button>
                                </div>
                            </div>
                        </div>

                        @if(is_default_lang())
                            <div class="panel">
                                <div class="panel-title"><strong>{{__('Categories')}}</strong></div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <select id="cat_id" class="form-control" name="category_id">
                                            <?php
                                            $selectedIds = !empty($row->category_id) ? explode(',', $row->category_id) : [];
                                            $traverse = function ($categories, $prefix = '') use (&$traverse, $selectedIds) {
                                                foreach ($categories as $category) {
                                                    $selected = '';
                                                    if (in_array($category->id, $selectedIds))
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
                        @endif

                        <div class="panel">
                            <div class="panel-title"><strong>{{__("Company Tags")}}</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="">
                                        <select id="company_type_id" name="company_skills[]" class="form-control"
                                                multiple="multiple">
                                            <option value="">{{__("-- Please Select --")}}</option>
                                            <?php
                                            foreach ($company_skills as $company_skill) {
                                                $selected = '';
                                                if ($row->skills) {
                                                    foreach ($row->skills as $skill) {
                                                        if ($company_skill->id == $skill->id) {
                                                            $selected = 'selected';
                                                        }
                                                    }
                                                }
                                                printf("<option value='%s' %s>%s</option>", $company_skill->id, $selected, $company_skill->name);
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if(is_admin() && is_default_lang())
                            <div class="panel">
                                <div class="panel-title"><strong>{{__('Featured')}}</strong></div>
                                <div class="panel-body">
                                    <div>
                                        <label><input @if($row->is_featured) checked @endif type="checkbox" name="is_featured" value="1"> {{__("is Featured")}}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="panel">
                                <div class="panel-title"><strong>{{__("Employer")}}</strong></div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <?php
                                        $user = !empty($row->create_user) ? App\User::find($row->owner_id) : false;
                                        \App\Helpers\AdminForm::select2('owner_id', [
                                            'configs' => [
                                                'ajax'        => [
                                                    'url' => url('/admin/module/user/getForSelect2'),
                                                    'dataType' => 'json'
                                                ],
                                                'allowClear'  => true,
                                                'placeholder' => __('-- Select Employer --')
                                            ]
                                        ], !empty($user->id) ? [
                                            $user->id,
                                            $user->getDisplayName() . ' (#' . $user->id . ')'
                                        ] : false)
                                        ?>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(is_default_lang())
                            @include('Company::admin.company.attributes')
                            <div class="panel">
                                <div class="panel-body">
                                    <h3 class="panel-body-title"> {{ __('Logo')}} ({{__('Recommended size image:330x300px')}})</h3>
                                    <div class="form-group">
                                        {!! \Modules\Media\Helpers\FileHelper::fieldUpload('avatar_id',$row->avatar_id) !!}
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="panel">
                            <div class="panel-title"><strong>{{ __('Social Media')}}</strong></div>
                            <div class="panel-body">
                                <?php $socialMediaData = $row->social_media; ?>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="social-skype"><i class="fa fa-skype"></i></span>
                                    </div>
                                    <input type="text" class="form-control" autocomplete="off" name="social_media[skype]" value="{{ $socialMediaData['skype'] ?? '' }}" placeholder="{{__('Skype')}}" aria-label="{{__('Skype')}}" aria-describedby="social-skype">
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="social-facebook"><i class="fa fa-facebook"></i></span>
                                    </div>
                                    <input type="text" class="form-control" autocomplete="off"  name="social_media[facebook]" value="{{ $socialMediaData['facebook'] ?? '' }}" placeholder="{{__('Facebook')}}" aria-label="{{__('Facebook')}}" aria-describedby="social-facebook">
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="social-twitter"><i class="fa fa-twitter"></i></span>
                                    </div>
                                    <input type="text" class="form-control"autocomplete="off" name="social_media[twitter]" value="{{$socialMediaData['twitter'] ?? ''}}" placeholder="{{__('Twitter')}}" aria-label="{{__('Twitter')}}" aria-describedby="social-twitter">
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="social-instagram"><i class="fa fa-instagram"></i></span>
                                    </div>
                                    <input type="text" class="form-control" autocomplete="off" name="social_media[instagram]" value="{{$socialMediaData['instagram'] ?? ''}}" placeholder="{{__('Instagram')}}" aria-label="{{__('Instagram')}}" aria-describedby="social-instagram">
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="social-linkedin"><i class="fa fa-linkedin"></i></span>
                                    </div>
                                    <input type="text" class="form-control" autocomplete="off" name="social_media[linkedin]" value="{{$socialMediaData['linkedin'] ?? ''}}" placeholder="{{__('Linkedin')}}" aria-label="{{__('Linkedin')}}" aria-describedby="social-linkedin">
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="social-google"><i class="fa fa-google"></i></span>
                                    </div>
                                    <input type="text" class="form-control" autocomplete="off" name="social_media[google]" value="{{@$socialMediaData['google'] ?? ''}}" placeholder="{{__('Google')}}" aria-label="{{__('Google')}}" aria-describedby="social-google">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('script.body')
    {!! App\Helpers\MapEngine::scripts() !!}
    <script src="{{url('libs/easepick/easepick.min.js')}}"></script>
    <script>
        new easepick.create({
            element: ".has-easepick",
            css: [
                '{{ asset("libs/easepick/easepick.css") }}',
            ],
            zIndex: 10,
            format: 'MM/DD/YYYY',
            AmpPlugin: {
                dropdown: {
                    months: true,
                    years: true
                },
                darkMode: false,
            },
            plugins: [
                "AmpPlugin"
            ]
        })

        $(document).ready(function() {
            $('#category_id').select2();
        });
        jQuery(function ($) {
            new BravoMapEngine('map_content', {
                disableScripts: true,
                fitBounds: true,
                center: [{{$row->location->map_lat ?? "38.91"}}, {{$row->location->map_lng ?? "-77.03"}}],
                zoom:{{$row->location->map_zoom ?? "8"}},
                ready: function (engineMap) {
                    @if($row->location && $row->location->map_lat && $row->location->map_lng)
                    engineMap.addMarker([{{$row->location->map_lat}}, {{$row->location->map_lng}}], {
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
                        $("input[name=map_state]").val(state);
                        $("input[name=map_state_long]").val(state_long);
                        $("input[name=map_city]").val(city);
                        $("input[name=map_address]").val(address);
                    });
                    engineMap.on('zoom_changed', function (zoom) {
                        $("input[name=map_zoom]").attr("value", zoom);
                    });
                    engineMap.autocomplete($('.bravo_searchbox'),function (dataLatLng) {
                        engineMap.clearMarkers();
                        engineMap.addMarker(dataLatLng, {
                            icon_options: {}
                        });
                        const { city, state, state_long, address } = dataLatLng[3]

                        $("input[name=map_lat]").val(dataLatLng[0]);
                        $("input[name=map_lng]").val(dataLatLng[1]);
                        $("input[name=map_state]").val(state);
                        $("input[name=map_state_long]").val(state_long);
                        $("input[name=map_city]").val(city);
                        $("input[name=map_address]").val(address);
                    });
                }
            });
        })

        $('#company_type_id').select2({
            tags: true,
            placeholder: "Company Tags",
            tokenSeparators: [','],
            createTag: function (params) {
                let term = $.trim(params.term);

                return {
                    id: term,
                    text: term,
                    newTag: true,
                }
            },
        }).on('select2:select', function (e) {
            let skills = $(this);
            let tag = e.params.data;
            if (e.params.data.newTag === true) {
                $.post('{{ route('user.store.skill') }}', {
                    name: tag.text,
                    skill_type: 'company',
                    status: 'publish'
                }).done(function (result) {
                    let option = $(`#company_type_id [value="${tag.text}"]`)
                    option[0].value = result.created_skill_id
                    const data = skills.select2('data').map(item => {
                        if(item.id === tag.text){
                            item.id = result.created_skill_id.toString()
                        }
                        return item
                    })
                    skills.select2('data', data)
                });
            }
        })

        $('.js-company-benefit').on('click', event => {
            const term = $(event.target).text()
            const currentValues = $('#company_type_id').val()

            if (currentValues.includes(term)) {
                return false
            }

            var newOption = new Option(term, term, false, false);
            $('#company_type_id').append(newOption).val([...currentValues, term]).trigger('change');
        })
    </script>
@endsection
