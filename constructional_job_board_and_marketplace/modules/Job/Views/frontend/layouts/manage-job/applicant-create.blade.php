@extends('layouts.user')

@php
    $manifestDir = 'dist/frontend';
@endphp

@section('content')

    <form action="{{ route('user.applicants.store') }}" class="default-form add-applicant-form" method="post">
        @csrf
        <div class="row">
            <div class="col-md-9">
                <div class="upper-title-box">
                    <h3>{{ __("Create new applicant") }}</h3>
                </div>
            </div>

        </div>
        @include('admin.message')
        <div class="row">
            <div class="col-lg-9">
                <!-- Ls widget -->
                <div class="ls-widget">
                    <div class="tabs-box">
                        <div class="widget-title"><h4>{{ __("Candidate Info") }}</h4></div>
                        <div class="widget-content">
                            <div class="form-group">
                                <label>{{ __("Candidate") }} <span class="text-danger">*</span></label>
                                <input type="hidden" name="candidate_id" value="">
                                <?php
                                $candidate = !empty($row->candidate_id) ? \Modules\Candidate\Models\Candidate::find($row->candidate_id) : false;
                                \App\Helpers\AdminForm::select2('candidate_id', [
                                    'configs' => [
                                        'ajax' => [
                                            'url' => route('candidate.admin.getForSelect2'),
                                            'dataType' => 'json'
                                        ],
                                        'allowClear' => true,
                                        'placeholder' => __('-- Select Candidate --')
                                    ]
                                ], !empty($candidate->id) ? [
                                    $candidate->id,
                                    $candidate->name . ' (#' . $candidate->id . ')'
                                ] : false)
                                ?>
                            </div>
                            <div class="form-group group-cv" style="display: none;">
                                <label>{{__("Cv")}} <span class="text-danger">*</span></label>
                                <div class="list-cv">
                                    <div class="form-group-item">
                                        <div class="g-items lists_cvs">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="ls-widget">
                    <div class="tabs-box">
                        <div class="widget-title"><h4>{{ __("Job Info") }}</h4></div>
                        <div class="widget-content">
                            <div class="form-group">
                                <label>{{__("Select Job")}} <span class="text-danger">*</span></label>
                                <input type="hidden" name="job_id" value="">
                                <?php
                                $job = !empty($row->job_id) ? \Modules\Job\Models\Job::find($row->job_id) : false;
                                \App\Helpers\AdminForm::select2('job_id', [
                                    'configs' => [
                                        'ajax' => [
                                            'url' => route('job.admin.getForSelect2').'?expiration_date=1',
                                            'dataType' => 'json'
                                        ],
                                        'allowClear' => true,
                                        'placeholder' => __('-- Select Job --')
                                    ]
                                ], !empty($job->id) ? [
                                    $job->id,
                                    $job->name . ' (#' . $job->id . ')'
                                ] : false)
                                ?>
                            </div>

                            <div class="form-group">
                                <label for="content">{{__("Message")}}</label>
                                <textarea name="content" class="form-control" id="content" rows="5">{{ old('content', $row->message) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="ls-widget">
                    <div class="widget-title"><h4>{{ __("Status") }}</h4></div>
                    <div class="widget-content">
                        <div class="form-group">
                            <div class="form-check add-applicant-form__form-check">
                                <input
                                    @if($row->status=='pending') checked @endif
                                    type="radio"
                                    class='form-check-input '
                                    name="status"
                                    value="pending"
                                    id="pending"
                                >
                                <label class="form-check-label" for="pending">
                                    {{__("Pending")}}
                                </label>
                            </div>
                            <div class="form-check add-applicant-form__form-check">
                                <input
                                    @if($row->status=='approved') checked @endif
                                    type="radio"
                                    class='form-check-input '
                                    name="status"
                                    value="approved"
                                    id="approved"
                                >
                                <label class="form-check-label" for="approved">
                                    {{__("Approved")}}
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="text-right">
                                <button class="f-btn primary-btn theme-btn w-100" type="submit">{{__('Save Changes')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </form>
@endsection

@section('footer')
    <script src="{{ mix('js/bootstrap5.js', $manifestDir) }}"></script>

    <script>
        jQuery(function ($) {
            "use strict"
            $('select[name="candidate_id"]').on('change',function (){
                var candidate_id = $(this).val();

                $('.list-cv .lists_cvs').children().remove();
                $.ajax({
                    url:'/admin/module/job/all-applicants/get-cv?id='+candidate_id,
                    method:'get',
                    success:function (json) {
                        if(json.status == 1)
                        {
                            if(json.cv)
                            {
                                for(var i= 0; i< json.cv.length; i++)
                                {
                                    if(json.cv[i].media)
                                    {
                                        var file_type = json.cv[i].media.file_extension == 'doc' || json.cv[i].media.file_extension == 'docx' ? 'ri-file-word-2-line' : 'ri-file-pdf-line';

                                        const cvItem = `
                                            <label class="item">
                                                 <span class="lists_cvs__item-radio form-check">
                                                     <input type='radio' class='form-check-input' name='apply_cv_id' value="${json.cv[i].id}">
                                                 </span>
                                                 <span class="lists_cvs__item-title">
                                                    <i class="ri-file-${file_type}-2-line"></i>
                                                     ${json.cv[i].media.file_name}.${json.cv[i].media.file_extension}
                                                </span>
                                            </label>

                                        `
                                        $('.list-cv .lists_cvs').append(cvItem);
                                    }
                                }
                            }
                            if(json.cv.length > 0)
                            {
                                $('.group-cv').show();
                            }
                        }
                    },
                    error:function (e) {

                    }
                });
            }).trigger('change')
        })
    </script>
@endsection
