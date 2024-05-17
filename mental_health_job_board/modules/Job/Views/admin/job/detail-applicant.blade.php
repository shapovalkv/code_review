@extends('admin.layouts.app')

@section('content')
    <form action="{{route('job.admin.applicants.store',['id'=>($row->id) ? $row->id : '-1','lang'=>request()->query('lang')])}}" method="post">
        @csrf
        <div class="container-fluid">
            <div class="d-flex justify-content-between mb20">
                <div class="">
                    <h1 class="title-bar">{{$row->id ? __('Edit: ').$row->title : __('Add new applicant')}}</h1>
                    @if($row->slug)
                        <p class="item-url-demo">{{__("Permalink")}}: {{ url(config('job.job_route_prefix') ) }}/<a href="#" class="open-edit-input" data-name="slug">{{$row->slug}}</a>
                        </p>
                    @endif
                </div>
                <div class="">
                    @if($row->slug)
                        <a class="btn btn-default btn-sm" href="{{ $row->getDetailUrl() }}" target="_blank"><i class="fa fa-eye"></i> {{__("View Job")}}</a>
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
                            <div class="panel-title"><strong>{{__("Candidate")}} <span class="text-danger">*</span></strong></div>
                            <div class="panel-body">
                                <div class="form-group">
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
                                    <label class="control-label">{{__("Cv")}} <span class="text-danger">*</span></label>
                                    <div class="list-cv">
                                        <div class="form-group-item">
                                            <div class="g-items lists_cvs">

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel">
                            <div class="panel-title"><strong>{{__("Job Content")}}</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="control-label">{{__("Content")}}</label>
                                    <div class="">
                                        <textarea name="content" class="d-none has-ckeditor" cols="30"
                                                  rows="10">{{ old('content', $row->message) }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">{{__("Job")}} <span class="text-danger">*</span></label>
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
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="panel">
                            <div class="panel-title"><strong>{{__('Publish')}}</strong></div>
                            <div class="panel-body">
                                @if(is_default_lang())
                                    <div>
                                        <label><input type="radio" name="status"
                                                      checked value="publish"> {{__("Publish")}}</label>
                                    </div>
                                    <div>
                                        <label><input type="radio" name="status" value="pending"> {{__("Pending")}}
                                        </label>
                                    </div>
                                @endif
                                <div class="text-right">
                                    <button class="btn btn-primary" type="submit"><i
                                            class="fa fa-save"></i> {{__('Save Changes')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@php  @endphp
@section ('script.body')
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
                                        var file_type = json.cv[i].media.file_extension == 'doc' || json.cv[i].media.file_extension == 'docx' ? 'fa-file-word-o' : 'fa-file-pdf-o';
                                        var list_cv = "<div class='item'><div class='row'><div class='col-md-1'><input type='radio' class='form-control' name='apply_cv_id' value="+json.cv[i].id+"></div><div class='col-md-8'><i class='fa "+file_type+"'></i> "+json.cv[i].media.file_name +"."+ json.cv[i].media.file_extension+"</div></div></div>";
                                        $('.list-cv .lists_cvs').append(list_cv);
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
