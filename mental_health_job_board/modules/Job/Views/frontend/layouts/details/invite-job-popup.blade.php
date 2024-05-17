@if(is_employer())
    <div class="model bc-model" id="invite-job">
        <!-- invite Job modal -->
        <div id="invite-job-modal">
            <div class="invite-job-form default-form">
                <div class="form-inner">
                    <h3 class="form-title text-center">{{ __("Invite for this job") }}</h3>

                    <form id="job-invite-form" class="job-invite-form" method="post"
                          action="{{ route('user.applicants.store') }}" enctype="multipart/form-data"
                          data-applied-text="{{ __("Applies") }}">
                        @csrf
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="ls-widget">
                                    <div class="tabs-box">
                                        <div class="widget-content">
                                            <div class="form-group invite mt-4" style="z-index: 10001">
                                                <label>{{__("Select Job")}} <span class="text-danger">*</span></label>
                                                <input type="hidden" name="job_id" value="">
                                                    <?php
                                                    $newApplicant = new \Modules\Job\Models\JobCandidate();
                                                    $job = !empty($row->job_id) ? \Modules\Job\Models\Job::find($row->job_id) : false;
                                                    ?>
                                                <select class="form-control dungdt-select2-field-custom" data-options='<?php echo json_encode([
                                                        'configs' => [
                                                            'ajax' => [
                                                                'url' => "",
                                                                'dataType' => 'json'
                                                            ],
                                                            'allowClear' => true,
                                                            'placeholder' => __('-- Select Job --')
                                                        ]
                                                    ]) ?>' name="job_id">
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="content">{{__("Message")}}</label>
                                                <textarea name="content" class="form-control" id="content"
                                                          rows="5" style="overflow-y: scroll">{{ old('content', $newApplicant->message) }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="candidate_id" value="">
                            <input type="hidden" name="status" value="pending">
                            <input type="hidden" name="company_id"
                                   value="{{ \Illuminate\Support\Facades\Auth::user()->company->id ?? '' }}">
                            <div class="text-center">
                                <button class="theme-btn btn-style-one" type="submit">{{ __("Invite") }}
                                    <span class="spinner-grow spinner-grow-sm icon-loading" role="status"
                                          aria-hidden="true"></span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif
