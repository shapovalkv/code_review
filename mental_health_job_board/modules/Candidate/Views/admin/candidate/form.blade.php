    @php
        $candidate = $row->candidate;
    @endphp
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>{{__("Current position")}} <span class="text-danger">*</span></label>
                <input type="text" value="{{old('title',@$candidate->title)}}" name="title" placeholder="{{__("Fill-In Space")}}" required class="form-control onChangeAutoSave">
            </div>
        </div>
{{--        <div class="col-md-6">--}}
{{--            <div class="form-group">--}}
{{--                <label>{{__("Website")}}</label>--}}
{{--                <input type="text" value="{{old('website',@$candidate->website)}}" name="website" placeholder="{{__("Website")}}" class="form-control">--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="col-md-6">--}}
{{--            <div class="form-group">--}}
{{--                <label for="gender">{{__("Gender")}}</label>--}}
{{--                <select class="form-control" id="gender" name="gender">--}}
{{--                    <option value="" @if(old('gender',@$candidate->gender) == '') selected @endif >{{ __("Select") }}</option>--}}
{{--                    <option value="male" @if(old('gender',@$candidate->gender) == 'male') selected @endif >{{ __("Male") }}</option>--}}
{{--                    <option value="female" @if(old('gender',@$candidate->gender) == 'female') selected @endif >{{ __("Female") }}</option>--}}
{{--                </select>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="col-md-6">--}}
{{--            <div class="form-group">--}}
{{--                <label>{{__("Expected Salary")}}</label>--}}
{{--                <div class="input-group">--}}
{{--                    <input type="text" value="{{ old('expected_salary',@$candidate->expected_salary) }}" placeholder="{{__("Expected Salary")}}" name="expected_salary" class="form-control">--}}
{{--                    <div class="input-group-append">--}}
{{--                        <select class="form-control" name="salary_type">--}}
{{--                            <option value="hourly" @if(old('salary_type',@$candidate->salary_type) == 'hourly') selected @endif > {{ currency_symbol().__("/hourly") }} </option>--}}
{{--                            <option value="daily" @if(old('salary_type',@$candidate->salary_type) == 'daily') selected @endif >{{ currency_symbol().__("/daily") }}</option>--}}
{{--                            <option value="weekly" @if(old('salary_type',@$candidate->salary_type) == 'weekly') selected @endif >{{ currency_symbol().__("/weekly") }}</option>--}}
{{--                            <option value="monthly" @if(old('salary_type',@$candidate->salary_type) == 'monthly') selected @endif >{{ currency_symbol().__("/monthly") }}</option>--}}
{{--                            <option value="yearly" @if(old('salary_type',@$candidate->salary_type) == 'yearly') selected @endif >{{ currency_symbol().__("/yearly") }}</option>--}}
{{--                        </select>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
        <div class="col-md-6">
            <div class="form-group">
                <label>{{__("Experience")}}<span class="text-danger">*</span></label>
                <div class="input-group">
                    <input required type="text" class="form-control onChangeAutoSave" placeholder="{{ __("Experience") }}" name="experience_year" value="{{ old('experience_year',@$candidate->experience_year) }}">
                    <div class="input-group-append">
                        <span class="input-group-text" style="font-size: 14px;">{{ __("year(s)") }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="education_level">{{__("Education Level")}}</label>
                <select class="form-control onChangeAutoSave" id="education_level" name="education_level">
                    <option value="" @if(old('education_level',@$candidate->education_level) == '') selected @endif >{{ __("Select") }}</option>
                    <option value="High School Diploma" @if(old('education_level',@$candidate->education_level) == 'High School Diploma') selected @endif >{{ __("High School Diploma") }}</option>
                    <option value="Associates" @if(old('education_level',@$candidate->education_level) == 'Associates') selected @endif >{{ __("Associates") }}</option>
                    <option value="Bachelor's Degree" @if(old('education_level',@$candidate->education_level) == 'Bachelor\'s Degree') selected @endif >{{ __("Bachelors Degree") }}</option>
                    <option value="Master's Degree" @if(old('education_level',@$candidate->education_level) == 'Master\'s Degree') selected @endif >{{ __("Masters Degree") }}</option>
                    <option value="Doctorate" @if(old('education_level',@$candidate->education_level) == 'Doctorate') selected @endif >{{ __("Doctorate") }}</option>
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>{{__("Language")}}</label>
                <select id="languages" name="languages[]" class="form-control onChangeAutoSave" multiple="multiple">
                    <option value="">{{__("-- Please Select --")}}</option>
                    <?php
                    foreach ($languages as $language) {
                        $selected = '';
                        if (!empty($candidate->languages)){
                            $lang = explode(",",$candidate->languages);
                            $lang = array_map('trim', $lang);
                            if(in_array($language,$lang))
                            {
                                $selected = 'selected';
                            }
                        }
                        printf("<option value='%s' %s>%s</option>", $language, $selected, $language);
                    }
                    ?>
                </select>
            </div>
        </div>

{{--        <div class="col-md-12">--}}
{{--            <div class="form-group">--}}
{{--                <label class="control-label">{{__("Video Url")}}</label>--}}
{{--                <p><i>{{__("Insert a video, which shows anything about you")}}</i></p>--}}
{{--                <input type="text" name="video" class="form-control" value="{{old('video',@$candidate->video)}}" placeholder="{{__("Youtube link video")}}">--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        @if(is_default_lang())--}}
{{--            <div class="col-md-12">--}}
{{--                <div class="form-group">--}}
{{--                    <label>{{__("Video Cover Image")}}</label>--}}
{{--                    <div class="form-group">--}}
{{--                        {!! \Modules\Media\Helpers\FileHelper::fieldUpload('video_cover_id',@$candidate->video_cover_id) !!}--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        @endif--}}

{{--        <div class="col-md-12">--}}
{{--            <div class="form-group">--}}
{{--                <label class="control-label">{{__("Gallery")}} ({{__('Recommended size image:1080 x 1920px')}})</label>--}}
{{--                @php--}}
{{--                    $gallery_id = @$candidate->gallery ?? old('gallery');--}}
{{--                @endphp--}}
{{--                {!! \Modules\Media\Helpers\FileHelper::fieldGalleryUpload('gallery', $gallery_id) !!}--}}
{{--            </div>--}}
{{--        </div>--}}
    </div>



