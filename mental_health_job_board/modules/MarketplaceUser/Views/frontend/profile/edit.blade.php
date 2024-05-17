@extends('layouts.user')
@section('head')
    <style>
        #permanentlyDeleteAccount .close-modal{
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
        </div>
        @include('admin.message')
        <form action="{{ route('user.marketplace_user.store') }}" method="post" class="default-form">
            @csrf
            <div class="row">
                <div class="col-lg-9">
                    <div class="ls-widget mb-4">
                        <div class="tabs-box">
                            <div class="widget-title"><h4>{{ __("Marketplace User Info") }}</h4></div>
                            <div class="widget-content">

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{__("First name")}} <span class="text-danger">*</span></label>
                                                <input type="text" value="{{old('first_name',$user->first_name)}}" name="first_name" placeholder="{{__("First name")}}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{__("Last name")}} <span class="text-danger">*</span></label>
                                                <input type="text" required value="{{old('last_name',$user->last_name)}}" name="last_name" placeholder="{{__("Last name")}}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{__("Email")}}<span class="text-danger">*</span></label>
                                                <input type="text" readonly value="{{old('email',$user->email)}}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{__("Telephone Number")}}<span class="text-danger">*</span></label>
                                                <input type="text" required value="{{old('phone',$user->phone)}}"  name="phone" placeholder="{{__("Telephone Number")}}" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4 d-none d-md-block">
                        <button class="theme-btn btn-style-seven" type="submit"><i class="fa fa-save" style="padding-right: 5px"></i> {{__('Save Changes')}}</button>
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
                                    <a href="{{$url}}" target="_blank" class="btn btn-style-ten text-light"><i
                                            class="la la-eye"></i> {{__("View profile")}}</a>
                                </div>
                            @endif
                            {{ session('hide_profile_v2') }}
                        </div>
                    @endif
                    <div class="mb-4">
                        <button class="theme-btn btn-style-seven" type="submit"><i class="fa fa-save" style="padding-right: 5px"></i> {{__('Save Changes')}}</button>
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
                        <a rel="modal:open" class="btn btn-danger" href="#permanentlyDeleteAccount">{{__('Delete your account')}}</a>
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
    <script src="{{ asset('libs/select2/js/select2.min.js') }}" ></script>
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

        document.addEventListener("DOMContentLoaded", function() {
            const phoneInputs = document.querySelectorAll('input[name="phone"]');
            Inputmask({
                mask: '(###) ###-####',
                repeat: 1,
                greedy: false
            }).mask(phoneInputs);
        });

        $('#hide_profile_toggle').on('change', function() {
            var isChecked = $(this).prop('checked');
            $('#hide_profile_text').empty().append(
                $('<div>').text(isChecked ?
                    'Great! After you press Save Changes button you will have your Profile published which means Employers will be able to find your profile in search!'
                    : 'Your Profile is now hidden and employers can’t find it. If you are looking for a job, please turn the toggle on and press Save Changes button.')
                    .addClass(isChecked ? '' : 'alert alert-danger')
            );
        });

        @if(is_marketplace_user() || !empty($marketplace_user_create))
        $(document).ready(function() {
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
    </script>
@endsection
