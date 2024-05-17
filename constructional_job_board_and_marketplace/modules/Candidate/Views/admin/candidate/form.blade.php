@php
    $candidate = $row->candidate;
@endphp
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>{{__("Title")}} <span class="text-danger">*</span></label>
            <input
                type="text"
                required
                value="{{old('title',@$candidate->title)}}"
                name="title"
                placeholder="{{__("Title")}}"
                class="form-control js-required-input"
            >
        </div>
    </div>
{{--    <div class="col-md-6">--}}
{{--        <div class="form-group">--}}
{{--            <label>{{__("Website")}}</label>--}}
{{--            <input--}}
{{--                type="text"--}}
{{--                value="{{old('website',@$candidate->website)}}"--}}
{{--                name="website"--}}
{{--                placeholder="{{__("Website")}}"--}}
{{--                class="form-control"--}}
{{--            >--}}
{{--        </div>--}}
{{--    </div>--}}
    <div class="col-md-6">
        <div class="form-group">
            <label>{{__("Expected Salary")}} <span class="text-danger">*</span></label>
            <div class="input-group">
                <input
                    type="text"
                    required
                    value="{{ old('expected_salary_min', $candidate->expected_salary_min) }}"
                    placeholder="{{__("Min")}}"
                    name="expected_salary_min"
                    class="form-control no-focus js-required-input"
                >
                <input
                    type="text"
                    value="{{ old('expected_salary_max', $candidate->expected_salary_max) }}"
                    placeholder="{{__("Max")}}"
                    name="expected_salary_max"
                    class="form-control no-focus"
                >
                <div class="input-group-append">
                    <select
                        class="form-control no-focus"
                        name="salary_type"
                    >
                        <option value="hourly" @if(old('salary_type', @$candidate->salary_type) == 'hourly') selected @endif > {{ __("/hourly") }} </option>
                        <option value="daily" @if(old('salary_type', @$candidate->salary_type) == 'daily') selected @endif >{{ __("/daily") }}</option>
                        <option value="weekly" @if(old('salary_type', @$candidate->salary_type) == 'weekly') selected @endif >{{ __("/weekly") }}</option>
                        <option value="monthly" @if(old('salary_type', @$candidate->salary_type) == 'monthly') selected @endif >{{ __("/monthly") }}</option>
                        <option value="yearly" @if(old('salary_type', @$candidate->salary_type) == 'yearly') selected @endif >{{ __("/yearly") }}</option>
                    </select>
                </div>
            </div>

            <div class="form-text">
                Please specify at least minimal salary
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>{{__("Experience")}}<span class="text-danger">*</span></label>
            <div class="input-group">
                <input
                    type="number"
                    inputmode="decimal"
                    required
                    class="form-control js-required-input"
                    placeholder="{{ __("Experience") }}"
                    name="experience_year"
                    max="50"
                    min="0"
                    value="{{ old('experience_year',@$candidate->experience_year) }}"
                >
                <div class="input-group-append">
                    <span class="input-group-text" style="font-size: 14px;">{{ __("year(s)") }}</span>
                </div>
            </div>
            <div class="form-text">
                Experience value must be between 0 and 50 years
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="education_level">{{__("Education Level")}}</label>
            <select class="form-control" id="education_level" name="education_level">
                <option value=""
                        @if(old('education_level',@$candidate->education_level) == '') selected @endif >{{ __("Select") }}</option>
                <option value="certificate"
                        @if(old('education_level',@$candidate->education_level) == 'certificate') selected @endif >{{ __("Certificate") }}</option>
                <option value="diploma"
                        @if(old('education_level',@$candidate->education_level) == 'diploma') selected @endif >{{ __("Diploma") }}</option>
                <option value="associate"
                        @if(old('education_level',@$candidate->education_level) == 'associate') selected @endif >{{ __("Associate") }}</option>
                <option value="bachelor"
                        @if(old('education_level',@$candidate->education_level) == 'bachelor') selected @endif >{{ __("Bachelor") }}</option>
                <option value="master"
                        @if(old('education_level',@$candidate->education_level) == 'master') selected @endif >{{ __("Master") }}</option>
                <option value="professional"
                        @if(old('education_level',@$candidate->education_level) == 'professional') selected @endif >{{ __("Professional") }}</option>
            </select>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>{{__("Type of Experience")}}<span class="text-danger">*</span></label>
            <div class="input-group-append">
                <select
                    id="seniority_level"
                    class="form-control js-required-input"
                    name="seniority_level[]"
                    required
                    multiple="multiple"
                >
                    <option value="newbie"
                            @if(old('seniority_level', str_contains($candidate->seniority_level, 'newbie')) == 'newbie') selected @endif > {{ __("Newbie / Journeyman") }} </option>
                    <option value="commercial"
                            @if(old('seniority_level', str_contains($candidate->seniority_level, 'commercial')) == 'commercial') selected @endif >{{ __("Commercial") }}</option>
                    <option value="residential"
                            @if(old('seniority_level', str_contains($candidate->seniority_level, 'residential')) == 'residential') selected @endif >{{ __("Residential") }}</option>
                </select>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>{{__("Language")}}</label>
            <input type="text" value="{{old('languages',@$candidate->languages)}}" name="languages"
                   placeholder="{{__("Choose language")}}" class="form-control">
        </div>
    </div>

    <div class="col-md-12">
        <div class="gallary">
            <h4>
                {{__("Gallery")}}

                <div class="subtitle">({{__('Recommended size image:1080 x 1920px')}})</div>
            </h4>
            <div class="form-group">
                @php
                    $gallery_id = @$candidate->gallery ?? old('gallery');
                @endphp
                {!! \Modules\Media\Helpers\FileHelper::fieldGalleryUpload('gallery', $gallery_id) !!}
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label class="control-label">{{__("Video URL")}}</label>
            <input
                type="text"
                name="video"
                class="form-control js-input-video-url"
                value="{{old('video',@$candidate->video)}}"
                placeholder="{{__("Video URL")}}"
            >
        </div>
    </div>

</div>



