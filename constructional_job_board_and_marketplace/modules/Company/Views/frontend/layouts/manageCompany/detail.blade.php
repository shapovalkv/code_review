@extends('layouts.user')

@php
    $manifestDir = 'dist/frontend';
    $languages = \Modules\Language\Models\Language::getActive();
@endphp

@section('head')
    <title>Company settings</title>
@endsection

@section('content')
        <form
            id="edit-company"
            data-page-with-vue
            method="post"
            action="{{ route('user.company.update' ) }}"
            class="default-form post-form manage-page"
        >
            @csrf
            <div class="manage-page-header manage-page-header--setting" style="display: none">

                <div class="manage-page-header__tabs manage-page-header__tabs--setting">
                    <ul class="manage-page-header__tabs-list nav nav-pills flex-nowrap nav nav-tabs" role="tablist">
                        <li class="manage-page-header__tabs-item manage-page-header__tabs-item--setting nav-item" role="presentation">
                            <button
                                class="manage-page-header__tabs-btn nav-link active"
                                id="company-tab"
                                data-toggle="tab"
                                data-target="#company"
                                type="button"
                                role="tab"
                                aria-controls="company"
                                aria-selected="true"
                            >Company information</button>
                        </li>

                        <li class="manage-page-header__tabs-item manage-page-header__tabs-item--setting nav-item" role="presentation">
                            <button
                                class="manage-page-header__tabs-btn nav-link"
                                id="billing-company-tab"
                                data-toggle="tab"
                                data-target="#billing-company"
                                type="button"
                                role="tab"
                                aria-controls="billing-company"
                                aria-selected="false"
                            >Billing information</button>
                        </li>
                    </ul>
                </div>
            </div>

            @include('admin.message')

            @if ($message = Session::get('complete_registration'))
                <div class="alert alert-info alert-block">
                    <button type="button" class="close ri-close-line" data-dismiss="alert"></button>
                    <span>{!! clean($message) !!}</span>
                </div>
            @endif

            @if($row->id)
                @include('Language::admin.navigation')
            @endif

            <div class="tab-content" id="companySettingTabContent">
                <div class="tab-pane fade show active" id="company" role="tabpanel" aria-labelledby="company-tab">
                    <div class="row">
                        <div class="col-md-12 col-lg-8 col-xl-9">
                            <!-- Ls widget -->
                            <div class="post-form__left-col">
                                <div class="ls-widget mb-0 mb-md-4 mb-lg-0">
                                    <div class="tabs-box">
                                        <div class="widget-title"><h4>{{ __("Company Information") }}</h4></div>

                                        <div class="widget-content">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>{{__("Company title")}}<span class="text-danger">*</span></label>
                                                        <input
                                                            required
                                                            type="text"
                                                            value="{{old('name',$translation->name)}}"
                                                            name="name"
                                                            placeholder="{{__("Company name")}}"
                                                            class="form-control js-required-input"
                                                        >
                                                    </div>
                                                </div>
                                                @if(is_default_lang())
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ __('E-mail')}}<span class="text-danger">*</span></label>
                                                            <input
                                                                type="email"
                                                                required
                                                                value="{{old('email',$row->email)}}"
                                                                placeholder="{{ __('Email')}}"
                                                                name="email"
                                                                class="form-control js-required-input"
                                                            >
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ __('Phone Number')}}<span class="text-danger">*</span></label>

                                                        <div class="input-group">
                                                            <span class="input-group-text">+1</span>
                                                            <input
                                                                id="phone"
                                                                type="text"
                                                                value="{{old('phone',$row->phone)}}"
                                                                name="phone"
                                                                placeholder="{{__("Phone Number")}}"
                                                                class="form-control js-required-input"
                                                                required
                                                            >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{__("Website")}}</label>
                                                        <input
                                                            type="text"
                                                            value="{{old('website',$row->website)}}"
                                                            name="website"
                                                            placeholder="{{__("Website")}}"
                                                            class="form-control"
                                                        >
                                                    </div>
                                                </div>
                                                @if(is_default_lang())
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ __('Est. Since')}}</label>
                                                            <input
                                                                type="text"
                                                                value="{{ old('founded_in',$row->founded_in ? date(get_date_format(),strtotime($row->founded_in)) :'') }}"
                                                                placeholder="{{ __('Est. Since')}}"
                                                                name="founded_in"
                                                                class="form-control has-easepick input-group date"
                                                            >
                                                        </div>
                                                    </div>
                                                @endif
{{--                                                <div class="col-md-6">--}}
{{--                                                    <div class="form-group">--}}
{{--                                                        <label>{{ __('Address')}}<span class="text-danger">*</span></label>--}}
{{--                                                        <input--}}
{{--                                                            type="text"--}}
{{--                                                            value="{{old('address',$row->address)}}"--}}
{{--                                                            placeholder="{{ __('Address')}}"--}}
{{--                                                            name="address"--}}
{{--                                                            class="form-control js-required-input"--}}
{{--                                                            required--}}
{{--                                                        >--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                                <div class="col-md-6">--}}
{{--                                                    <div class="form-group">--}}
{{--                                                        <label>{{__("City")}}<span class="text-danger">*</span></label>--}}
{{--                                                        <input--}}
{{--                                                            required--}}
{{--                                                            type="text"--}}
{{--                                                            value="{{old('city',$row->city)}}"--}}
{{--                                                            name="city"--}}
{{--                                                            placeholder="{{__("City")}}"--}}
{{--                                                            class="form-control js-required-input"--}}
{{--                                                        >--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                                <div class="col-md-6">--}}
{{--                                                    <div class="form-group">--}}
{{--                                                        <label>{{__("State")}}<span class="text-danger">*</span></label>--}}
{{--                                                        <input--}}
{{--                                                            type="text"--}}
{{--                                                            value="{{old('state',$row->state)}}"--}}
{{--                                                            name="state"--}}
{{--                                                            placeholder="{{__("State")}}"--}}
{{--                                                            class="form-control js-required-input"--}}
{{--                                                            required--}}
{{--                                                        >--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                                <div class="col-md-6">--}}
{{--                                                    <div class="form-group">--}}
{{--                                                        <label class="">{{__("Country")}}<span class="text-danger">*</span></label>--}}
{{--                                                        <select required name="country" class="form-control" id="country-sms-testing">--}}
{{--                                                            <option value="">{{__('-- Select --')}}</option>--}}
{{--                                                            @foreach(get_country_lists() as $id=>$name)--}}
{{--                                                                <option @if($row->country==$id) selected @endif value="{{$id}}">{{$name}}</option>--}}
{{--                                                            @endforeach--}}
{{--                                                        </select>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                                <div class="col-md-6">--}}
{{--                                                    <div class="form-group">--}}
{{--                                                        <label>{{__("Zip Code")}}<span class="text-danger">*</span></label>--}}
{{--                                                        <input--}}
{{--                                                            type="text"--}}
{{--                                                            value="{{old('zip_code',$row->zip_code)}}"--}}
{{--                                                            name="zip_code"--}}
{{--                                                            placeholder="{{__("Zip Code")}}"--}}
{{--                                                            class="form-control js-required-input"--}}
{{--                                                            required--}}
{{--                                                        >--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                                @if(is_default_lang())--}}
{{--                                                    <div class="col-md-6">--}}
{{--                                                        <div class="form-group">--}}
{{--                                                            <div class="custom-control custom-checkbox">--}}
{{--                                                                <input--}}
{{--                                                                    class="custom-control-input"--}}
{{--                                                                    id="allow_search"--}}
{{--                                                                    type="checkbox"--}}
{{--                                                                    name="allow_search"--}}
{{--                                                                    @if($row->allow_search) checked @endif--}}
{{--                                                                    value="1"--}}
{{--                                                                >--}}
{{--                                                                <label class="custom-control-label" for="allow_search">{{ __("Allow In Search & Listing") }}</label>--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                @endif--}}
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label">{{ __('Company description')}}</label>
                                                        <div class="">
                                                            <textarea
                                                                name="about"
                                                                class="d-none has-ckeditor"
                                                                cols="30"
                                                                rows="10"
                                                            >{{old('about',$translation->about)}}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="ls-widget location">
                                    <div class="tabs-box">
                                        <div class="widget-title">
                                            <h4>{{ __("Company location") }}<span class="text-danger">*</span></h4>
                                        </div>

                                        <div class="widget-content">
                                            <div class="form-group">
                                                <label class="control-label">{{__("Location")}}</label>
                                                <input
                                                    type="text"
                                                    placeholder="{{__("Company location")}}"
                                                    name="map_location_visible"
                                                    class="bravo_searchbox form-control js-required-input"
                                                    autocomplete="off"
                                                    onkeydown="return event.key !== 'Enter';"
                                                    value="{{ old('map_location', $row->location->map_location ?? '') }}"
                                                    required
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

                                <div class="tabs-box">
                                    <div class="gallary">
                                        <h4>
                                            {{__("Gallery")}}

                                            <div class="subtitle">({{__('Recommended size image:1080 x 1920px')}})</div>
                                        </h4>
                                        <div class="form-group">
                                            @php
                                                $gallery_id = $row->gallery ?? old('gallery', $row->gallery);
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

{{--                                @include('Core::frontend/seo-meta/seo-meta')--}}
                            </div>
                        </div>

                        <div class="col-md-12 col-lg-4 col-xl-3 post-form__right-col-wrap mt-md-5 mt-lg-0">
                            <div class="post-form__right-col">
                                <div class="row">
                                <div class="col-md-6 col-lg-12">
                                    <div class="ls-widget mb-4">
                                        <div class="tabs-box bravo_user_profile__avatar-wrap">
                                            <div class="widget-title mb-3 d-md-block">
                                                <h5>{{ __('Logo')}}</h5>

                                                <div class="subtitle mt-2">{{__('Recommended size image:330x300px')}}</div>
                                            </div>
                                                <div class="form-group mb-0">
                                                    {!! \Modules\Media\Helpers\FileHelper::fieldUpload('avatar_id',$row->avatar_id) !!}
                                                </div>
                                        </div>
                                    </div>
                                </div>

{{--                                <div class="col-md-6 col-lg-12 ">--}}
{{--                                    <div class="ls-widget ls-widget--border-top rounded-0 mb-0 m-md-0 p-md-0 border-md-0">--}}
{{--                                        <div class="tabs-box">--}}
{{--                                            <div class="widget-title"><h5>{{ __("Publish") }}</h5></div>--}}

{{--                                            <div class="form-group mb-0">--}}
{{--                                            @if(is_default_lang())--}}
{{--                                                <div class="form-check add-applicant-form__form-check d-flex align-items-center">--}}
{{--                                                    <input--}}
{{--                                                        @if($row->status=='publish') checked @endif--}}
{{--                                                        type="radio"--}}
{{--                                                        class='form-check-input '--}}
{{--                                                        name="status"--}}
{{--                                                        value="publish"--}}
{{--                                                        id="publish"--}}
{{--                                                    >--}}
{{--                                                    <label class="form-check-label mb-0 ml-2" for="publish">--}}
{{--                                                        {{__("Publish")}}--}}
{{--                                                    </label>--}}
{{--                                                </div>--}}

{{--                                                <div class="form-check add-applicant-form__form-check d-flex align-items-center">--}}
{{--                                                    <input--}}
{{--                                                        @if($row->status=='draft') checked @endif--}}
{{--                                                        type="radio"--}}
{{--                                                        class='form-check-input '--}}
{{--                                                        name="status"--}}
{{--                                                        value="draft"--}}
{{--                                                        id="draft"--}}
{{--                                                    >--}}
{{--                                                    <label class="form-check-label mb-0 ml-2" for="draft">--}}
{{--                                                        {{__("Draft")}}--}}
{{--                                                    </label>--}}
{{--                                                </div>--}}
{{--                                            @endif--}}
{{--                                        </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

                                @if(is_default_lang())
                                    <div class="col-md-6 col-lg-12">

                                        <div class="ls-widget ls-widget--border-top rounded-0 mb-0 m-md-0 p-md-0 border-md-0">
                                            <div class="tabs-box">
                                                <div class="widget-title"><h5>{{ __("Categories") }}<span class="text-danger">*</span></h5></div>
                                                <div class="form-group mb-0">
                                                    <select required id="cat_id" class="form-control js-required-input" name="category_id">
                                                        <option value="">{{__("-- Please select category --")}}</option>
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
                                    </div>
                                @endif

                                <div class="col-md-6 col-lg-12">
                                    <div class="ls-widget ls-widget--border-top mb-0 rounded-0">
                                        <div class="tabs-box">
                                            <div class="widget-title justify-content-between flex-wrap flex-row">
                                                <h5>{{ __("Company tags") }}</h5>

                                                <div class="subtitle">Up to 10</div>
                                            </div>
                                            <div class="widget-content">
                                                <div class="form-group mb-0">
                                                    <div class="">
                                                        <select
                                                            id="company_type_id"
                                                            name="company_skills[]"
                                                            class="form-control select"
                                                            multiple="multiple"
                                                        >
                                                            <option value="">{{__("-- Please Select --")}}</option>
                                                            <?php
                                                            foreach ($company_skills as $companySkill) {
                                                                $selected = '';
                                                                if ($row->skills) {
                                                                    foreach ($row->skills as $skill) {
                                                                        if ($companySkill->id == $skill->id) {
                                                                            $selected = 'selected';
                                                                        }
                                                                    }
                                                                }
                                                                printf("<option value='%s' %s>%s</option>", $companySkill->id, $selected, $companySkill->name);
                                                            }
                                                            ?>
                                                        </select>

                                                        <div class="popular-variants">
                                                            <div class="popular-variants__item js-company-benefit">Bonuses</div>
                                                            <div class="popular-variants__item js-company-benefit">Free lunch</div>
                                                            <div class="popular-variants__item js-company-benefit">Competitive salary</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if(is_default_lang())
                                    @foreach ($attributes as $attribute)
                                        <div class="col-md-6 col-lg-12">
                                            <div class="ls-widget ls-widget--border-top mb-0 rounded-0">
                                                <div class="tabs-box">
                                                    <div class="widget-title"><h5>{{__(':name',['name'=>$attribute->name])}}</h5></div>

                                                    <div class="form-group mb-0">
                                                            @foreach($attribute->terms as $term)
                                                                <div class="form-check add-applicant-form__form-check d-flex align-items-center">
                                                                <input
                                                                    @if(!empty($selected_terms) and $selected_terms->contains($term->id)) checked @endif
                                                                    type="radio"
                                                                    class='form-check-input '
                                                                    name="terms[]"
                                                                    value="{{$term->id}}"
                                                                    id="terms{{$term->id}}"
                                                                >
                                                                <label class="form-check-label mb-0 ml-2" for="terms{{$term->id}}">
                                                                    {{$term->name}}
                                                                </label>
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                                @if(is_default_lang())
                                    <div class="col-md-6 col-lg-12">
                                        <div class="ls-widget ls-widget--border-top mb-0 rounded-0">
                                            <div class="tabs-box">
                                                <div class="widget-title">
                                                    <h5>{{ __("Social Media") }}</h5>
                                                    <div class="subtitle mt-2">
                                                        {{__('Please paste full link, i.e https://socialmedia.com/yourpage')}}
                                                    </div>
                                                </div>
                                                <?php $socialMediaData = $row->social_media; ?>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-append social-append-section">
                                                        <span class="input-group-text" id="social-skype">
                                                            <i class="ri-skype-line"></i>
                                                        </span>
                                                    </div>
                                                    <input type="text" class="form-control" autocomplete="off"
                                                           name="social_media[skype]"
                                                           value="{{ $socialMediaData['skype'] ?? '' }}"
                                                           placeholder="{{__('Skype')}}" aria-label="{{__('Skype')}}"
                                                           aria-describedby="social-skype">
                                                </div>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-append social-append-section">
                                                        <span class="input-group-text" id="social-facebook">
                                                            <i class="ri-facebook-circle-line"></i>
                                                        </span>
                                                    </div>
                                                    <input type="text" class="form-control" autocomplete="off"
                                                           name="social_media[facebook]"
                                                           value="{{ $socialMediaData['facebook'] ?? '' }}"
                                                           placeholder="{{__('Facebook')}}"
                                                           aria-label="{{__('Facebook')}}"
                                                           aria-describedby="social-facebook">
                                                </div>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-append social-append-section">
                                                        <span class="input-group-text" id="social-twitter">
                                                            <i class="ri-twitter-line"></i>
                                                        </span>
                                                    </div>
                                                    <input type="text" class="form-control" autocomplete="off"
                                                           name="social_media[twitter]"
                                                           value="{{$socialMediaData['twitter'] ?? ''}}"
                                                           placeholder="{{__('Twitter')}}"
                                                           aria-label="{{__('Twitter')}}"
                                                           aria-describedby="social-twitter">
                                                </div>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-append social-append-section">
                                                        <span class="input-group-text" id="social-instagram">
                                                            <i class="ri-instagram-line"></i>
                                                        </span>
                                                    </div>
                                                    <input type="text" class="form-control" autocomplete="off"
                                                           name="social_media[instagram]"
                                                           value="{{$socialMediaData['instagram'] ?? ''}}"
                                                           placeholder="{{__('Instagram')}}"
                                                           aria-label="{{__('Instagram')}}"
                                                           aria-describedby="social-instagram">
                                                </div>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-append social-append-section">
                                                        <span class="input-group-text" id="social-linkedin">
                                                            <i class="ri-linkedin-line"></i>
                                                        </span>
                                                    </div>
                                                    <input type="text" class="form-control" autocomplete="off"
                                                           name="social_media[linkedin]"
                                                           value="{{$socialMediaData['linkedin'] ?? ''}}"
                                                           placeholder="{{__('Linkedin')}}"
                                                           aria-label="{{__('Linkedin')}}"
                                                           aria-describedby="social-linkedin">
                                                </div>
                                                <div class="input-group mb-3">
                                                        <div class="input-group-append social-append-section">
                                                            <span class="input-group-text" id="social-google">
                                                                <i class="ri-google-line"></i>
                                                            </span>
                                                        </div>
                                                        <input type="text" class="form-control" autocomplete="off"
                                                               name="social_media[google]"
                                                               value="{{@$socialMediaData['google'] ?? ''}}"
                                                               placeholder="{{__('Google')}}" aria-label="{{__('Google')}}"
                                                               aria-describedby="social-google">
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="billing-company" role="tabpanel" aria-labelledby="billing-company-tab">
                    <div class="billing-section">
                        <h2 class="billing-section__title">billing Information</h2>
                        <div class="billing-section__subtitle">We will add a 3% charge for any credit card payment.</div>

                        <div class="billing-section__pay-wrap">
                            <div class="billing-section__pay-inputs">
                                <div class="form-group">
                                    <label>{{__("Name on card")}}</label>
                                    {{-- TODO add old value --}}
                                    {{--                                    value="{{old('name',$translation->name)}}"--}}
                                    <input
                                        type="text"
                                        value=""
                                        name="card_name"
                                        v-model="cardName"
                                        placeholder="{{__("Name on card")}}"
                                        class="form-control"
                                    >
                                </div>

                                <div class="form-group">
                                    <label>{{__("Card number")}}</label>
                                    {{-- TODO add old value --}}
                                    {{--                                    value="{{old('name',$translation->name)}}"--}}
                                    <input
                                        type="text"
                                        value=""
                                        name="card_number"
                                        v-model="cardNumber"
                                        placeholder="_ _ _ _ – _ _ _ _ – _ _ _ _ – _ _ _ _"
                                        v-maska="'#### - #### - #### - ####'"
                                        class="form-control"
                                    >
                                </div>

                                <div class="billing-section__sort-input-wrap">
                                    <div class="form-group">
                                        <label>{{__("Expiration date")}}</label>
                                        {{-- TODO add old value --}}
                                        {{--                                    value="{{old('name',$translation->name)}}"--}}
                                        <input
                                            type="text"
                                            value=""
                                            name="card_date"
                                            v-model="cardDate"
                                            placeholder="MM / YY"
                                            v-maska="'## / ##'"
                                            class="form-control"
                                        >
                                    </div>

                                    <div class="form-group">
                                        <label>{{__("CVV")}}</label>
                                        {{-- TODO add old value --}}
                                        {{--                                    value="{{old('name',$translation->name)}}"--}}
                                        <div class="billing-section__input-wrap">
                                            <input
                                                type="text"
                                                value=""
                                                name="card_cvv"
                                                placeholder="_ _ _"
                                                v-maska="'###'"
                                                class="form-control"
                                            >

                                            <i
                                                class="ri-information-line modal-pay-product__input-popover"
                                                v-b-popover.hover.top="'Secret code on the back of the card'"
                                            ></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="billing-section__card">
                                <div class="billing-section__card-number">@{{ outputCardNumber }}</div>

                                <div class="billing-section__card-date-wrap">
                                    <div class="billing-section__card-date-title">Valid thru</div>
                                    <div class="billing-section__card-date-value">@{{ outputCardDate }}</div>
                                </div>

                                <div class="billing-section__card-name">@{{ outputCardName }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col px-md-0 post-form__send-btn-col">
                <div class="post-form__send-btn-wrap">
                    <button id="submitFormBtn" class="post-form__send-btn f-btn primary-btn theme-btn btn-style-one">
                        {{__('Save Changes')}}

                        <svg width="28" height="8" viewBox="0 0 28 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M28 4L23 8V0L28 4Z" fill="white"/>
                            <path d="M23 3.58011H0V4.41989H23V3.58011Z" fill="white"/>
                        </svg>
                    </button>

                    @if($row->slug)
                        <a
                            class="post-form__send-btn f-btn secondary-btn theme-btn btn-style-one"
                            href="{{$row->getDetailUrl(request()->query('lang'))}}"
                            target="_blank"
                        >{{__("View Company")}}</a>
                    @endif
                </div>
            </div>

        </form>
@endsection

@section('footer')
    {!! App\Helpers\MapEngine::scripts() !!}
    <script src="{{ mix('js/editCompany.js', $manifestDir) }}"></script>
    <script src="{{ asset('js/condition.js') }}"></script>

    <script type="text/javascript" src="{{ asset('libs/daterange/moment.min.js') }}"></script>
    <script src="{{ asset('libs/select2/js/select2.min.js') }}" ></script>
    <script type="text/javascript" src="{{url('module/core/js/form-validation-engine.js?_ver='.config('app.version'))}}"></script>
    <script src="{{url('libs/easepick/easepick.min.js')}}"></script>

    <script>
        const date = "{{ old('founded_in',$row->founded_in ? date(get_date_format(),strtotime($row->founded_in)) :'') }}"
        const prepareDate = date ? moment(date).format('MM/DD/YYYY') :  moment().format('MM/DD/YYYY')

        const picker = new easepick.create({
            element: ".has-easepick",
            css: [
                '{{ asset("libs/easepick/easepick.css") }}',
            ],
            zIndex: 10,
            date: prepareDate,
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

        Maska.create('[name="phone"]', { mask: '### ### ####' });
    </script>
    <script>
        let mapLat = {{ !empty($row->location->map_lat) ? ($row->location->map_lat ?? "38.896714696640004") : "38.896714696640004" }};
        let mapLng = {{ !empty($row->location->map_lng) ? ($row->location->map_lng ?? "-77.04821945173418") : "-77.04821945173418" }};
        let mapZoom = {{ !empty($row->location->map_zoom) ? ($row->location->map_zoom ?? "8") : "8" }};

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

        });

        $('.bravo_searchbox').on('input', () => {
            $('.js-hidden-location').val(null)
        })

        jQuery(function ($) {
            "use strict"
            $('.open-edit-input').on('click', function (e) {
                e.preventDefault();
                $(this).replaceWith('<input type="text" name="' + $(this).data('name') + '" value="' + $(this).html() + '">');
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

        window.initValidationForm(
            '#submitFormBtn',
            '.js-required-input',
            '.js-input-video-url',
            '#edit-company',
        )
    </script>
@endsection
