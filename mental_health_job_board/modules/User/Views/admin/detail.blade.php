@extends('admin.layouts.app')

@section('content')
    <form action="{{url('admin/module/user/store/'.($row->id ?? -1))}}" method="post" class="needs-validation"
          novalidate>
        @csrf
        <div class="container-fluid">
            <div class="d-flex justify-content-between mb20">
                <div class="">
                    <h1 class="title-bar">{{$row->id ? 'Edit User: '.$row->getDisplayName() : 'Add new user'}}</h1>
                    @if(!empty($row->candidate))
                        <p class="item-url-demo">{{__("Permalink")}}
                            : {{ url(config('candidate.candidate_route_prefix') ) }}/<a href="#" class="open-edit-input"
                                                                                        data-name="slug">{{$row->candidate->slug}}</a>
                        </p>
                    @endif
                </div>

                @if($row->company)
                    <div class="flex">
                    <a class="btn btn-primary btn-sm" href="{{ route('company.admin.edit', ['id' => $row->company->id]) }}"><i class="fa fa-pencil"></i> {{ __("Edit Company") }}</a>
                    <a class="btn btn-success btn-sm" href="{{ $row->company->getDetailUrl(request()->query('lang')) }}" target="_blank"><i class="fa fa-eye"></i> {{ __("View Company") }}</a>
                    </div>
                @endif
                @if($row->candidate)
                    <div class="flex">
                        <a class="btn btn-success btn-sm" href="{{ route('candidate.detail', ['candidate' => $row->candidate->id]) }}" target="_blank"><i class="fa fa-eye"></i> {{ __("View Candidate") }}</a>
                    </div>
                @endif
            </div>
            @include('admin.message')
            <div class="row">
                <div class="col-md-9">
                    <div class="panel">
                        <div class="panel-title"><strong>{{ __('User Info')}}</strong></div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('E-mail')}}</label>
                                        <input type="email" required value="{{old('email',$row->email)}}"
                                               placeholder="{{ __('Email')}}" name="email" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{__("First name")}}</label>
                                        <input type="text" required value="{{old('first_name',$row->first_name)}}"
                                               name="first_name" placeholder="{{__("First name")}}"
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{__("Last name")}}</label>
                                        <input type="text" required value="{{old('last_name',$row->last_name)}}"
                                               name="last_name" placeholder="{{__("Last name")}}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('Phone Number')}}</label>
                                        <input type="text" value="{{old('phone',$row->phone)}}"
                                               placeholder="{{ __('Phone')}}" name="phone" class="form-control"
                                               required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('Birthday')}}</label>
                                        <input type="text" readonly style="background: white"
                                               value="{{ old('birthday',$row->birthday ? date("Y/m/d",strtotime($row->birthday)) :'') }}"
                                               placeholder="{{ __('Birthday')}}" name="birthday"
                                               class="form-control has-datepicker input-group date">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label">{{ __('Biographical')}}</label>
                                <div class="">
                                    <textarea name="bio" class="d-none has-ckeditor" cols="30"
                                              rows="10">{{old('bio',$row->bio)}}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($row->hasRole('candidate') || !empty($candidate_create))
                        <div class="panel">
                            <div class="panel-title"><strong>{{ __('Candidate Info')}}</strong></div>
                            <div class="panel-body">
                                @include('Candidate::admin/candidate/form',['row'=> $row])
                            </div>
                        </div>

                        <div class="panel">
                            <div class="panel-title"><strong>{{ __('Location Info')}}</strong></div>
                            <div class="panel-body">
                                @include('Candidate::admin/candidate/location',['row'=> $row, 'locations' => $candidate_location])
                            </div>
                        </div>

                        <div class="panel">
                            <div class="panel-title"><strong>{{ __('Education - Experience - Award')}}</strong></div>
                            <div class="panel-body">
                                @include('Candidate::admin/candidate/sub_information',['row'=> $row])
                            </div>
                        </div>
                        @if(!empty($row->candidate))
                            @include('Core::admin/seo-meta/seo-meta', ['row' => $row->candidate])
                        @endif
                    @endif
                </div>

                <div class="col-md-3">
                    <div class="panel">
                        <div class="panel-title"><strong>{{ __('Publish')}}</strong></div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label>{{__('Status')}}</label>
                                <select required class="custom-select" name="status">
                                    <option value="">{{ __('-- Select --')}}</option>
                                    <option @if(old('status',$row->status) =='publish') selected
                                            @endif value="publish">{{ __('Publish')}}</option>
                                    <option @if(old('status',$row->status) =='blocked') selected
                                            @endif value="blocked">{{ __('Blocked')}}</option>
                                </select>
                            </div>
                            @if(is_admin())
                                <div class="form-group">
                                    <label>{{__('Role')}}</label>
                                    <select required class="form-control" name="role_id">
                                        <option value="">{{ __('-- Select --')}}</option>
                                        @foreach($roles as $role)
                                            <option value="{{$role->id}}"
                                                    @if(old('role_id',$row->role_id) == $role->id) selected
                                                    @elseif(old('role_id')  == $role->id ) selected @endif >{{ucfirst($role->name)}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            @if($row->hasRole('candidate') || !empty($candidate_create))
                                <div class="form-group">
                                    <label>{{__('Allow Search')}}</label>
                                    <select required class="custom-select" name="allow_search">
                                        <option value="">{{ __('-- Select --')}}</option>
                                        <option @if(old('allow_search',@$row->candidate->allow_search) =='publish') selected
                                                @endif value="publish">{{ __('Publish')}}</option>
                                        <option @if(old('allow_search',@$row->candidate->allow_search) =='hide') selected
                                                @endif value="hide">{{ __('Hide')}}</option>
                                    </select>
                                </div>
                            @endif

                            <hr>
                            <div class="d-flex justify-content-between">
                                <span></span>
                                <button class="btn btn-primary" type="submit">{{ __('Save Change')}}</button>
                            </div>
                        </div>
                    </div>

                    @if(is_default_lang() && isset($row->id))
                        <div class="panel">
                            <div class="panel-title"><strong>{{__('Packages')}}</strong></div>
                            <div class="panel-body">
                                <div class="alert alert-success success-msg" role="alert" style="display:none">
                                    Plan changed
                                </div>
                                <div class="alert alert-danger error-msg" role="alert" style="display:none">
                                    Error while changing plan
                                </div>
                                @foreach($row->userPlans()->where('status', \Modules\User\Models\UserPlan::NOT_USED)->get() as $plan)
                                    <div class="row package-{{$plan->id}}">
                                        <div class="col-6">
                                            <strong class="current-plan">{{$plan->plan_data['title']}} </strong>
                                        </div>
                                        <div class="col-6 text-right">
                                            <button data-url="{{route('user.admin.cancelPlan', $plan->id)}}"
                                                    class="btn btn-danger cancel-package" data-id="{{$plan->id}}"
                                                    type="button">Cancel
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                                <hr>
                                <div class="form-group">
                                    <label for="plan_id"
                                           class="col-form-label text-md-right">{{ __('Add new plan:') }}</label>
                                    <select id="package_id" class="form-control" name="plan_id">
                                        @php
                                            $plans = $row->role->allowedPackages()->orderBy('price', 'desc')->get();
                                        @endphp
                                        @foreach($plans as $plan)
                                            <option value="{{$plan->id}}">{{$plan->title}}</option>
                                        @endforeach
                                    </select>
                                    <div class="text-right mt-2">
                                        <button data-url="{{route('user.admin.applyPlan', $row->id)}}"
                                                class="btn btn-primary apply-package" type="button">Apply
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="panel">
                        <div class="panel-title"><strong>{{ __('Avatar')}}</strong></div>
                        <div class="panel-body">
                            <div class="form-group">
                                {!! \Modules\Media\Helpers\FileHelper::fieldUpload('avatar_id',old('avatar_id',$row->avatar_id)) !!}
                            </div>
                        </div>
                    </div>
                    @if($row->hasRole('candidate') || !empty($candidate_create))
                        <div class="panel">
                            <div class="panel-title"><strong>{{__('Categories')}}</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <select id="categories" class="form-control" name="categories[]"
                                            multiple="multiple">
                                        <option value="">{{__("-- Please Select --")}}</option>
                                            <?php
                                            foreach ($categories as $oneCategories) {
                                                $selected = '';
                                                if (!empty($row->candidate->categories)) {

                                                    foreach ($row->candidate->categories as $category) {
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
                        {{--                    <div class="panel">--}}
                        {{--                        <div class="panel-title"><strong>{{__("Skills")}}</strong></div>--}}
                        {{--                        <div class="panel-body">--}}
                        {{--                            <div class="form-group">--}}
                        {{--                                <div class="">--}}
                        {{--                                    <select id="skills" name="skills[]" class="form-control" multiple="multiple">--}}
                        {{--                                        <option value="">{{__("-- Please Select --")}}</option>--}}
                        {{--                                        <?php--}}
                        {{--                                        foreach ($skills as $oneSkill) {--}}
                        {{--                                            $selected = '';--}}
                        {{--                                            if (!empty($row->candidate->skills)){--}}
                        {{--                                                foreach ($row->candidate->skills as $skill){--}}
                        {{--                                                    if($oneSkill->id == $skill->id){--}}
                        {{--                                                        $selected = 'selected';--}}
                        {{--                                                    }--}}
                        {{--                                                }--}}
                        {{--                                            }--}}
                        {{--                                            $trans = $oneSkill->translateOrOrigin(app()->getLocale());--}}
                        {{--                                            printf("<option value='%s' %s>%s</option>", $oneSkill->id, $selected, $trans->name);--}}
                        {{--                                        }--}}
                        {{--                                        ?>--}}
                        {{--                                    </select>--}}
                        {{--                                </div>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                        {{--                    </div>--}}

                        <div class="panel">
                            <div class="panel-title"><strong>{{ __('Social Media')}}</strong></div>
                            <div class="panel-body">
                                    <?php $socialMediaData = !empty($row->candidate) ? $row->candidate->social_media : []; ?>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="social-skype"><i
                                                    class="fa fa-skype"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="social_media[skype]"
                                           value="{{@$socialMediaData['skype']}}" placeholder="{{__('Skype')}}"
                                           aria-label="{{__('Skype')}}" aria-describedby="social-skype">
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="social-facebook"><i
                                                    class="fa fa-facebook"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="social_media[facebook]"
                                           value="{{@$socialMediaData['facebook']}}" placeholder="{{__('Facebook')}}"
                                           aria-label="{{__('Facebook')}}" aria-describedby="social-facebook">
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="social-twitter"><i
                                                    class="fa-brands fa-x-twitter"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="social_media[twitter]"
                                           value="{{@$socialMediaData['twitter']}}" placeholder="{{__('Twitter')}}"
                                           aria-label="{{__('Twitter')}}" aria-describedby="social-twitter">
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="social-instagram"><i
                                                    class="fa fa-instagram"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="social_media[instagram]"
                                           value="{{@$socialMediaData['instagram']}}" placeholder="{{__('Instagram')}}"
                                           aria-label="{{__('Instagram')}}" aria-describedby="social-instagram">
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="social-pinterest"><i
                                                    class="fa fa-pinterest"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="social_media[pinterest]"
                                           value="{{@$socialMediaData['pinterest']}}" placeholder="{{__('Pinterest')}}"
                                           aria-label="{{__('Pinterest')}}" aria-describedby="social-pinterest">
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="social-dribbble"><i
                                                    class="fa fa-dribbble"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="social_media[dribbble]"
                                           value="{{@$socialMediaData['dribbble']}}" placeholder="{{__('Dribbble')}}"
                                           aria-label="{{__('Dribbble')}}" aria-describedby="social-dribbble">
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="social-google"><i
                                                    class="fa fa-google"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="social_media[google]"
                                           value="{{@$socialMediaData['google']}}" placeholder="{{__('Google')}}"
                                           aria-label="{{__('Google')}}" aria-describedby="social-google">
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="social-google"><i class="fa fa-linkedin"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="social_media[linkedin]"
                                           value="{{@$socialMediaData['linkedin']}}" placeholder="{{__('Linkedin')}}"
                                           aria-label="{{__('Linkedin')}}" aria-describedby="social-linkedin">
                                </div>
                            </div>
                        </div>

                        <div class="panel">
                            <div class="panel-title"><strong>{{ __('CV Uploaded')}}</strong></div>
                            <div class="panel-body">
                                <div class="form-group-item">
                                    <div class="g-items-header">
                                        <div class="row">
                                            <div class="col-md-2">{{__("Default")}}</div>
                                            <div class="col-md-8">{{__("Name")}}</div>
                                            <div class="col-md-2"></div>
                                        </div>
                                    </div>
                                    {!! \Modules\Media\Helpers\FileHelper::fieldFileUpload('cvs', @$cvs, 'cvs') !!}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <hr>
            <div class="d-flex justify-content-between">
                <span></span>
                <button class="btn btn-primary" type="submit">{{ __('Save Change')}}</button>
            </div>
        </div>
    </form>

@endsection
@section ('script.body')
    {!! App\Helpers\MapEngine::scripts() !!}
    <script type="text/javascript" src="{{ asset('libs/inputmask/inputmask.js') }}"></script>
    <script>
        @if($row->hasRole('candidate') || !empty($candidate_create))
        $(document).ready(function () {
            $('#categories').select2();
            $('#skills').select2();
            $('#languages').select2();
        });

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

        jQuery(function ($) {
            let mapLat = {{ !empty($row->candidate) ? ($row->candidate->map_lat ?? "34.0522") : "34.0522" }};
            let mapLng = {{ !empty($row->candidate) ? ($row->candidate->map_lng ?? "-118.244") : "-118.244" }};
            let mapZoom = {{ !empty($row->candidate) ? ($row->candidate->map_zoom ?? "10") : "10" }};


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
        })
        @endif
    </script>
@endsection
