<div>
    <div class="btn-box mb-0 mb-md-3">
        @if($row->isOpen())
            @if(empty(setting_item('job_hide_job_apply')) && $row->apply_type == 'email')
                <a href="mailto:{{ $row->apply_email ?? ($row->company->email ?? '') }}" target="_blank" rel="nofollow"
                   class="theme-btn btn-style-ten">{{ __("Apply For Job") }}</a>
            @elseif(empty(setting_item('job_hide_job_apply')) && $row->apply_type == 'external')
                <a href="{{ $row->apply_link }}" target="_blank" rel="nofollow"
                   class="theme-btn btn-style-ten">{{ __("Apply For Job") }}</a>
            @else
                @if(!auth()->check())
                    <a href="#" class="theme-btn btn-style-ten bc-call-modal login">{{ __("Apply For Job") }}</a>
                @else
                    @if($applied && is_candidate())
                        <a href="javascript:void(0)"
                           class="theme-btn btn-style-ten bc-apply-job-button">{{ __($row->getCandidateJobAppliedStatus()) }}</a>
                    @else
                        @if($candidate && !empty($check_apply_job = $candidate->check_maximum_apply_job()) && !$candidate->user->hasVerifiedEmail())
                            <div class="text-danger apply-out">{{ $check_apply_job['mess'] }}</div>
                        @elseif($candidate && !$candidate->user->hasVerifiedEmail())
                            <a href="#" data-require-text="{{ __('Please Verify your Email') }}"
                               class="theme-btn btn-style-ten bc-apply-job-button @if(!$candidate->user->hasVerifiedEmail()) bc-require-candidate-apply @else bc-call-modal apply-job @endif">{{ __("Apply For Job") }}</a>
                        @else
                            @if(is_candidate())
                                <a href="#"
                                   class="theme-btn btn-style-ten bc-apply-job-button  bc-call-modal apply-job">{{ __("Apply For Job") }}</a>
                            @endif
                        @endif
                    @endif
                    @include('Job::frontend.layouts.details.apply-job-popup')
                @endif
            @endif
        @else
            <div class="text-danger job-expired">{{ __("Job expired!") }}</div>
        @endif
        @if(is_candidate())
            <button class="bookmark-btn service-wishlist @if($row->wishlist) active @endif" data-id="{{$row->id}}"
                    data-type="{{$row->type}}"  data-toggle="tooltip" data-placement="bottom" title="{{__('Bookmark')}}"><i class="flaticon-bookmark"></i></button>
                <a class="bookmark-btn service-wishlist" href="{{route('user.chat.start', ['targetUser' => $row->user->id, 'job' => $row->id])}}" data-toggle="tooltip" data-placement="bottom" title="{{__('Messaging')}}"><i class="fa fa-lg fa-comment-dots"></i></a>
        @endif
    </div>
    <div class="btn-box mt-3 mt-md-0">
        @if($row->isOpen())
            @if($applied && is_candidate() && $row->getCandidateAppliedJob()->candidate_id !== $row->getCandidateAppliedJob()->create_user)
                @if($applied && is_candidate() && $row->getCandidateAppliedJob()->status == \Modules\Job\Models\JobCandidate::PENDING_STATUS)
                    <a href="{{ route('user.invite.changeStatus', ['status' => \Modules\Job\Models\Job::APPROVED, 'id' => $row->getCandidateAppliedJob()->id]) }}"
                       class="mr-3 btn-style-one bc-apply-job-button" style="width: 102px; font-size: 150%"><span
                            class="la la-check"></span></a>
                    <a href="{{ route('user.invite.changeStatus', ['status' => \Modules\Job\Models\Job::REJECTED, 'id' => $row->getCandidateAppliedJob()->id]) }}"
                       class="btn-style-one bc-apply-job-button" style="width: 102px; font-size: 150%"><span
                            class="la la-times-circle"></span></a>
                @endif
            @endif
        @endif
    </div>
</div>
