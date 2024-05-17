@extends('layouts.user')
@section('head')
    <style>
        #permanentlyDeleteAccount .close-modal {
            top: 35px;
        }
    </style>
@endsection
@section('content')
    <div class="bravo_user_profile p-0">
        <div class="d-flex justify-content-between mb20">
            <div class="upper-title-box">
                <h3 class="title">{{__("My Profile")}}</h3>
            </div>
            <div class="title-actions">
                {{--                <a href="{{route('user.upgrade_company')}}" class="btn btn-warning text-light">{{__("Become a Company")}}</a>--}}
                @if($url = $row->getDetailUrl())
                    <a href="{{$url}}" target="_blank" class="btn btn-style-ten text-light ml-3"><i
                            class="la la-eye"></i> {{__("View profile")}}</a>
                @endif
            </div>
        </div>
        @include('admin.message')
        <form action="{{ route('user.candidate.store') }}" method="post" class="default-form">
            @csrf
            <div class="row">
                <div class="col-lg-9">
                    <div class="ls-widget mb-4">
                        <div class="tabs-box">
                            <div class="widget-title"><h4>{{ __("Candidate Info") }}</h4></div>
                            <div class="widget-content">

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{__("First name")}} <span class="text-danger">*</span></label>
                                                <input type="text" value="{{old('first_name',$user->first_name)}}"
                                                       name="first_name" placeholder="{{__("First name")}}"
                                                       class="form-control onChangeAutoSave">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{__("Last name")}} <span class="text-danger">*</span></label>
                                                <input type="text" required
                                                       value="{{old('last_name',$user->last_name)}}" name="last_name"
                                                       placeholder="{{__("Last name")}}"
                                                       class="form-control onChangeAutoSave">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{__("Email")}}<span class="text-danger">*</span></label>
                                                <input type="text" readonly value="{{old('email',$user->email)}}"
                                                       class="form-control onChangeAutoSave">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{__("Telephone Number")}}<span
                                                        class="text-danger">*</span></label>
                                                <input type="text" required value="{{old('phone',$user->phone)}}"
                                                       name="phone" placeholder="{{__("Telephone Number")}}"
                                                       class="form-control onChangeAutoSave">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                @include('Candidate::admin.candidate.form',['row'=>$user])
                            </div>
                        </div>
                    </div>
                    <div class="ls-widget mb-4">
                        <div class="tabs-box">
                            <div class="widget-title"><h4>{{ __("About") }}<span class="text-danger">*</span></h4></div>
                            <div class="widget-content">
                                {!! __('You can write about your years of experience, industry, or skills. People also talk about their achievements or previous job experiences. This is the only summary about you shared with an employer until you accept a job invitation or apply for a job.') !!}
                            </div>
                            <div class="widget-content">
                                <textarea required name="bio" rows="5"
                                          class="form-control onChangeAutoSave">{{ strip_tags(old('bio',$user->bio)) }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="ls-widget mb-4">
                        <div class="tabs-box">
                            <div class="widget-title"><h4>{{ __("Location Info") }}</h4></div>
                            <div class="widget-content">
                                @include('Candidate::admin.candidate.location',['row'=>$user])
                            </div>
                        </div>
                    </div>
                    <div class="ls-widget mb-4 card-sub_information">
                        <div class="tabs-box">
                            <div class="widget-title">
                                <strong>{{ __("Education - Experience - Training and specialties") }}</strong></div>
                            <div class="widget-content">
                                @include('Candidate::admin.candidate.sub_information',['row'=>$user])
                            </div>
                        </div>
                    </div>

                    {{--                    @include('Core::frontend.seo-meta.seo-meta')--}}

                    <div class="mb-4 d-none d-md-block">
                        <button class="theme-btn btn-style-seven" type="submit"><i class="fa fa-save"
                                                                                 style="padding-right: 5px"></i> {{__('Save Changes')}}
                        </button>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="ls-widget mb-4 ">
                        <div class="tabs-box">
                            <div class="widget-title"><strong>{{ __('Avatar')}}</strong></div>
                            <div class="widget-content">
                                <div class="form-group">
                                    {!! \Modules\Media\Helpers\FileHelper::fieldUpload('avatar_id',old('avatar_id',$user->avatar_id)) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    @if (session('hide_profile') && session('hide_profile_v2'))
                        <div class="alert alert-danger">
                            {{ session('hide_profile') }}
                            @if($url = $row->getDetailUrl())
                                <div class="d-flex justify-content-center">
                                    <a href="{{$url}}" target="_blank" class="btn btn-info text-light"><i
                                            class="la la-eye"></i> {{__("Preview Profile")}}</a>
                                </div>
                            @endif
                            {{ session('hide_profile_v2') }}
                        </div>
                    @endif
                    <div class="ls-widget mb-4 ">
                        <div class="tabs-box">
                            @if($row->allow_search == 'publish')
                                {{--                                    Great! After you press Save Changes button you will have your Profile published--}}
                                {{--                                    which means Employers will be able to find your profile in search!--}}
                            @else
                                <div class="widget-title mb-0" id="hide_profile_text">
                                    <div class="alert alert-danger">
                                        Your Profile is now hidden and employers can’t find it. If you are looking for a
                                        job, please turn the toggle on and press Save Changes button.
                                    </div>
                                </div>
                            @endif
                            <div class="widget-title"><strong>{{ __('Publish or Hide Profile')}}</strong></div>
                            <div class="widget-content">

                                <div class="form-group">
                                    <label class="toggle-switch">
                                        <input type="checkbox"
                                               style="display:none"
                                               name="allow_search"
                                               value="publish"
                                               id="hide_profile_toggle"
                                               @if (old('allow_search', @$row->allow_search == 'publish')) checked @endif
                                        >
                                        <span class="toggle-slider"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ls-widget mb-4">
                        <div class="tabs-box">
                            <div class="widget-title"><strong>{{__('Categories')}}</strong></div>
                            <div class="widget-content">
                                <div class="form-group">
                                    <select id="categories" class="form-control onChangeAutoSave" name="categories[]"
                                            multiple="multiple">
                                        <option value="">{{__("-- Please Select --")}}</option>
                                        <?php
                                        foreach ($categories as $oneCategories) {
                                            $selected = '';
                                            if (!empty($row->categories)) {

                                                foreach ($row->categories as $category) {
                                                    if ($oneCategories->id == $category->id) {
                                                        $selected = 'selected';
                                                    }
                                                }
                                            }
                                            $trans = $oneCategories->translateOrOrigin(app()->getLocale());
                                            printf("<option value='%s' %s>%s</option>", $oneCategories->id, $selected, $oneCategories->name);
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <button class="theme-btn btn-style-seven" type="submit"><i class="fa fa-save"
                                                                                 style="padding-right: 5px"></i> {{__('Save Changes')}}
                        </button>
                    </div>
                </div>
            </div>
        </form>
        <hr>

    </div>
    @if(!empty(setting_item('user_enable_permanently_delete')) and !is_admin())
        <div class="row">
            <div class="col-lg-9">
                <div class="ls-widget">
                    <div class="widget-title">
                        <h4 class="text-danger">
                            {{__("Delete account")}}
                        </h4>
                    </div>
                    <div class="widget-content">
                        <div class="mb-4 mt-2">
                            {!! clean(setting_item_with_lang('user_permanently_delete_content','',__('Your account will be permanently deleted. Once you delete your account, there is no going back. Please be certain.'))) !!}
                        </div>
                        <a rel="modal:open" class="btn btn-danger"
                           href="#permanentlyDeleteAccount">{{__('Delete your account')}}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal bravo-form" id="permanentlyDeleteAccount">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{__('Confirm permanently delete account')}}</h5>
                    </div>
                    <div class="modal-body ">
                        <div class="my-3">
                            {!! clean(setting_item_with_lang('user_permanently_delete_content_confirm')) !!}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="#close-modal" rel="modal:close" class="btn btn-secondary">{{__('Close')}}</a>
                        <a href="{{route('user.permanently.delete')}}" class="btn btn-danger">{{__('Confirm')}}</a>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
@section('footer')
    {!! App\Helpers\MapEngine::scripts() !!}
    <script type="text/javascript" src="{{ asset('libs/daterange/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('libs/daterange/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('libs/select2/js/select2.min.js') }}"></script>
    <script>
        $('.has-datepicker').daterangepicker({
            singleDatePicker: true,
            showCalendar: false,
            autoUpdateInput: false,
            sameDate: true,
            autoApply: true,
            disabledPast: true,
            enableLoading: true,
            showEventTooltip: true,
            classNotAvailable: ['disabled', 'off'],
            disableHightLight: true,
            locale: {
                format: superio.date_format
            }
        }).on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format(superio.date_format));
        });
        var alertElement = document.querySelector('.alert.alert-danger');
        if (alertElement) {
            alertElement.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }

        document.addEventListener("DOMContentLoaded", function () {
            const phoneInputs = document.querySelectorAll('input[name="phone"]');
            Inputmask({
                mask: '(###) ###-####',
                repeat: 1,
                greedy: false
            }).mask(phoneInputs);
        });

        document.addEventListener("DOMContentLoaded", function () {
            const dateInputs = document.querySelectorAll('.mask-mm-yyyy');
            Inputmask({
                mask: '##/####',
                repeat: 1,
                greedy: false
            }).mask(dateInputs);
        });

        $(document).on("focus", ".mask-mm-yyyy", function () {
            Inputmask({
                mask: '##/####',
                repeat: 1,
                greedy: false
            }).mask($(this));
        });

        $('#hide_profile_toggle').on('change', function () {
            var isChecked = $(this).prop('checked');
            $('#hide_profile_text').empty().append(
                $('<div>').text(isChecked ?
                    // 'Great! After you press Save Changes button you will have your Profile published which means Employers will be able to find your profile in search!'
                    ''
                    : 'Your Profile is now hidden and employers can’t find it. If you are looking for a job, please turn the toggle on and press Save Changes button.')
                    .addClass(isChecked ? '' : 'alert alert-danger')
            );
        });

        let delay = 0;
        let offset = 150;

        $('button[type="submit"]').on('click', function() {
            console.log($(this).find(':invalid'));
            $('html, body').animate({scrollTop: $($(this).find(':invalid').first()).offset().top - offset }, delay);
        })

        @if(is_candidate() || !empty($candidate_create))
        $(document).ready(function () {
            $('#categories').select2();
            $('#skills').select2();
            $('#languages').select2();
        });

        let mapLat = {{ !empty($row) ? ($row->map_lat ?? "34.0522") : "34.0522" }};
        let mapLng = {{ !empty($row) ? ($row->map_lng ?? "-118.244") : "-118.244" }};
        let mapZoom = {{ !empty($row) ? ($row->map_zoom ?? "12") : "12" }};

        jQuery(function ($) {
            new BravoMapEngine('map_content', {
                disableScripts: true,
                fitBounds: true,
                center: [mapLat, mapLng],
                zoom: mapZoom,
                ready: function (engineMap) {
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
                    engineMap.searchBox($('#customPlaceAddress'), function (dataLatLng) {
                        engineMap.clearMarkers();
                        engineMap.addMarker(dataLatLng, {
                            icon_options: {}
                        });
                        $("input[name=map_lat]").attr("value", dataLatLng[0]);
                        $("input[name=map_lng]").attr("value", dataLatLng[1]);
                    });
                    engineMap.searchBox($('.bravo_searchbox'), function (dataLatLng) {
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
                        const zoom = $(this).find(':selected').attr('data-map_zoom')

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

        })
        @endif

        $(function () {
            $('.onChangeAutoSave').on('change', function () {
                onChangeAutoSave($(this))
            });
        });

        function onChangeAutoSave(element) {
            let data = {},
                name = element.attr('name');

            data[name] = element.val()

            saveCompanyAttribute(data);
        }

        function onChangeGeneratedAutoSave(element) {
            let data = {},
                name = element.attr('name');

            if (name.indexOf('education') >= 0 || name.indexOf('experience') >= 0 || name.indexOf('award') >= 0) {
                let id = element.closest('.item').data('number'),
                    key,
                    dataKeys;
                if (name.indexOf('education') >= 0) {
                    key = 'education';
                    dataKeys = ['from', 'to', 'reward', 'diploma'];
                } else if (name.indexOf('experience') >= 0) {
                    key = 'experience';
                    dataKeys = ['from', 'to', 'location', 'position', 'description'];
                } else if (name.indexOf('award') >= 0) {
                    key = 'award';
                    dataKeys = ['from', 'reward'];
                }

                data[key] = {};
                data[key][id] = {};
                dataKeys.forEach(function (k) {
                    data[key][id][k] = $('[name="' + key + '[' + id + '][' + k + ']"]').val();
                })

                for (let k in data[key][id]) {
                    console.log(data[key][id][k]);
                    if (data[key][id][k] === '') {
                        return;
                    }
                }
            } else {
                data[name] = element.val()
            }

            saveCompanyAttribute(data);
        }

        function onChangeMapAutoSave() {
            setTimeout(function () {
                let data = {
                    map_lat: $('[name="map_lat"]').val(),
                    map_lng: $('[name="map_lng"]').val(),
                    map_zoom: $('[name="map_zoom"]').val(),
                    location_id: $('[name="location_id"]').val(),
                };
                saveCompanyAttribute(data);
            }, 1000)
        }

        function saveCompanyAttribute(data) {
            $.ajax({
                url: '{{route('candidates.api.update')}}',
                type: 'post',
                data: data,
                dataType: 'json',
                async: false,
                cache: false,
                timeout: 30000,
                success: function (response) {
                    return response;
                }
            });
        }
    </script>
@endsection
