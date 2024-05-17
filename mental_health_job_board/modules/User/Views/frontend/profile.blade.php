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
{{--            @if(is_candidate())--}}
{{--                <div class="title-actions">--}}
{{--                    <a href="{{route('user.upgrade_company')}}" class="btn btn-warning text-light">{{__("Become a Company")}}</a>--}}
{{--                </div>--}}
{{--            @endif--}}
        </div>
        @include('admin.message')
        <form action="{{ url('admin/module/user/store/'.($row->id ?? -1)) }}" method="post" class="default-form">
            @csrf
            <input type="hidden" name="status" value="{{ $row->status }}">
            <input type="hidden" name="need_update_pw" value="{{ $row->need_update_pw }}">
            <div class="row">
                <div class="col-lg-9">
                    <div class="ls-widget mb-4">
                        <div class="tabs-box">
                            <div class="widget-title"><h4>{{ __("User Info") }}</h4></div>
                            <div class="widget-content">
                                <div class="form-group">
                                    <label>{{__("E-mail")}} <span class="text-danger">*</span></label>
                                    <input type="text" name="email" required value="{{old('email',$row->email)}}" placeholder="{{__("E-mail")}}" class="form-control">
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{__("First name")}} <span class="text-danger">*</span></label>
                                                <input type="text" value="{{old('first_name',$row->first_name)}}" name="first_name" placeholder="{{__("First name")}}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{__("Last name")}} <span class="text-danger">*</span></label>
                                                <input type="text" required value="{{old('last_name',$row->last_name)}}" name="last_name" placeholder="{{__("Last name")}}" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>{{__("Phone Number")}} <span class="text-danger">*</span></label>
                                    <input type="text" required value="{{old('phone',$row->phone)}}" name="phone" placeholder="{{__("Phone Number")}}" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>{{__("Birthday")}}</label>
                                    <input type="text" value="{{ old('birthday',$row->birthday? display_date($row->birthday) :'') }}" name="birthday" placeholder="{{__("Birthday")}}" class="form-control has-datepicker" autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <label>{{__("Biographical")}}</label>
                                    <textarea name="bio" rows="5" class="form-control">{{ strip_tags(old('bio',$row->bio)) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(is_candidate())
                        <div class="ls-widget mb-4">
                            <div class="tabs-box">
                                <div class="widget-title"><h4>{{ __("Candidate Info") }}</h4></div>
                                <div class="widget-content">
                                    @include('Candidate::admin.candidate.form')
                                </div>
                            </div>
                        </div>
                        <div class="ls-widget mb-4">
                            <div class="tabs-box">
                                <div class="widget-title"><h4>{{ __("Location Info") }}</h4></div>
                                <div class="widget-content">
                                    @include('Candidate::admin.candidate.location')
                                </div>
                            </div>
                        </div>
                        <div class="ls-widget mb-4 card-sub_information">
                            <div class="tabs-box">
                                <div class="widget-title"><strong>{{ __("Education - Experience - Award") }}</strong></div>
                                <div class="widget-content">
                                    @include('Candidate::admin.candidate.sub_information')
                                </div>
                            </div>
                        </div>

                        @if(is_admin() and $row->candidate)
                            @include('Core::frontend.seo-meta.seo-meta',['row' => ($row->candidate ?? $candidate ?? null)])
                        @endif

                    @endif
                    <div class="mb-4 d-none d-md-block">
                        <button class="theme-btn btn-style-one" type="submit"><i class="fa fa-save" style="padding-right: 5px"></i> {{__('Save Changes')}}</button>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="ls-widget mb-4 ">
                        <div class="tabs-box">
                            <div class="widget-title"><strong>{{ __('Avatar')}}</strong></div>
                            <div class="widget-content">
                                <div class="form-group">
                                    {!! \Modules\Media\Helpers\FileHelper::fieldUpload('avatar_id',old('avatar_id',$row->avatar_id)) !!}
                                </div>
                            </div>
                        </div>
                    </div>


                    @if(is_candidate())
                    <div class="ls-widget mb-4 ">
                        <div class="tabs-box">
                            <div class="widget-title"><strong>{{ __('Hide Profile or Publish')}}</strong></div>
                            <div class="widget-content">
                                <div class="form-group">
                                    <select required class="custom-select" name="allow_search">
                                        <option @if(old('allow_search',@$row->candidate->allow_search) =='publish') selected @endif value="publish">{{ __('Publish')}}</option>
                                        <option @if(old('allow_search',@$row->candidate->allow_search) =='hide') selected @endif value="hide">{{ __('Hide')}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if(is_candidate())
                        <div class="ls-widget mb-4">
                            <div class="tabs-box">
                                <div class="widget-title"><strong>{{__('Categories')}}</strong></div>
                                <div class="widget-content">
                                    <div class="form-group">
                                        <select id="categories" class="form-control" name="categories[]" multiple="multiple">
                                            <option value="">{{__("-- Please Select --")}}</option>
                                            <?php
                                            foreach ($categories as $oneCategories) {
                                                $selected = '';
                                                if (!empty($row->candidate->categories)){

                                                    foreach ($row->candidate->categories as $category){
                                                        if($oneCategories->id == $category->id){
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

{{--                        <div class="ls-widget mb-4">--}}
{{--                            <div class="tabs-box">--}}
{{--                                <div class="widget-title"><strong>{{__("Skills")}}</strong></div>--}}
{{--                                <div class="widget-content">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <div class="">--}}
{{--                                            <select id="skills" name="skills[]" class="form-control" multiple="multiple">--}}
{{--                                                <option value="">{{__("-- Please Select --")}}</option>--}}
{{--                                                <?php--}}
{{--                                                foreach ($skills as $oneSkill) {--}}
{{--                                                    $selected = '';--}}
{{--                                                    if (!empty($row->candidate->skills)){--}}
{{--                                                        foreach ($row->candidate->skills as $skill){--}}
{{--                                                            if($oneSkill->id == $skill->id){--}}
{{--                                                                $selected = 'selected';--}}
{{--                                                            }--}}
{{--                                                        }--}}
{{--                                                    }--}}
{{--                                                    $trans = $oneSkill->translateOrOrigin(app()->getLocale());--}}
{{--                                                    printf("<option value='%s' %s>%s</option>", $oneSkill->id, $selected, $trans->name);--}}
{{--                                                }--}}
{{--                                                ?>--}}
{{--                                            </select>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}

                        <div class="ls-widget mb-4 card-social">
                            <div class="tabs-box">
                                <div class="widget-title"><strong>{{ __('Social Media')}}</strong></div>
                                <div class="widget-content">
                                    <?php $socialMediaData = !empty($row->candidate) ? $row->candidate->social_media : []; ?>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="social-skype"><i class="fab fa-skype"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="social_media[skype]" value="{{@$socialMediaData['skype']}}" placeholder="{{__('Skype')}}" aria-label="{{__('Skype')}}" aria-describedby="social-skype">
                                    </div>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="social-facebook"><i class="fab fa-facebook"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="social_media[facebook]" value="{{@$socialMediaData['facebook']}}" placeholder="{{__('Facebook')}}" aria-label="{{__('Facebook')}}" aria-describedby="social-facebook">
                                    </div>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="social-twitter"><i class="fa-brands fa-x-twitter"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="social_media[twitter]" value="{{@$socialMediaData['twitter']}}" placeholder="{{__('Twitter')}}" aria-label="{{__('Twitter')}}" aria-describedby="social-twitter">
                                    </div>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="social-instagram"><i class="fab fa-instagram"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="social_media[instagram]" value="{{@$socialMediaData['instagram']}}" placeholder="{{__('Instagram')}}" aria-label="{{__('Instagram')}}" aria-describedby="social-instagram">
                                    </div>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="social-pinterest"><i class="fab fa-pinterest"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="social_media[pinterest]" value="{{@$socialMediaData['pinterest']}}" placeholder="{{__('Pinterest')}}" aria-label="{{__('Pinterest')}}" aria-describedby="social-pinterest">
                                    </div>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="social-dribbble"><i class="fab fa-dribbble"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="social_media[dribbble]" value="{{@$socialMediaData['dribbble']}}" placeholder="{{__('Dribbble')}}" aria-label="{{__('Dribbble')}}" aria-describedby="social-dribbble">
                                    </div>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="social-google"><i class="fab fa-google"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="social_media[google]" value="{{@$socialMediaData['google']}}" placeholder="{{__('Google')}}" aria-label="{{__('Google')}}" aria-describedby="social-google">
                                    </div>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="social-google"><i class="fab fa-linkedin"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="social_media[linkedin]" value="{{@$socialMediaData['linkedin']}}" placeholder="{{__('Linkedin')}}" aria-label="{{__('Linkedin')}}" aria-describedby="social-linkedin">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="mb-4">
                        <button class="theme-btn btn-style-one" type="submit"><i class="fa fa-save" style="padding-right: 5px"></i> {{__('Save Changes')}}</button>
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
    </script>
    <script>
        @if(is_candidate() || !empty($candidate_create))
        $(document).ready(function() {
            $('#categories').select2();
            $('#skills').select2();
            $('#languages').select2();
        });

        let mapLat = {{ !empty($row->candidate) ? ($row->candidate->map_lat ?? "34.0522") : "34.0522" }};
        let mapLng = {{ !empty($row->candidate) ? ($row->candidate->map_lng ?? "-118.244") : "-118.244" }};
        let mapZoom = {{ !empty($row->candidate) ? ($row->candidate->map_zoom ?? "10") : "10" }};

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
                }
            });

        })
        @endif
    </script>
@endsection
