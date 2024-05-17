@if(is_candidate() && !empty($candidate))
    <div class="model bc-model" id="apply-job">
        <!-- Apply Job modal -->
        <div id="apply-job-modal">
            <!-- Apply Job Form -->
            <div class="apply-job-form default-form">
                <div class="form-inner">
                    <h3 class="form-title text-center">{{ __("Apply for this job") }}</h3>

                    <form id="job-apply-form" class="job-apply-form" method="post" action="" enctype="multipart/form-data" data-applied-text="{{ __("Applies") }}">
                        @csrf
                        <div class="row">
                            <div class="col-sm-12">
                                @if(!$candidate->cvs->isEmpty())
{{--                                    <div class="select-cv">{{ __("Select a your Resume/ CV") }}</div>--}}
                                    <div class="wrapper-file-action">
                                        @foreach($candidate->cvs as $key => $cv)
                                            @php
                                                $file = (new \Modules\Media\Models\MediaFile())->findById($cv->file_id);
                                            @endphp
                                            @if($file)
                                                <label for="apply-cv-{{ $cv->id }}" class="item-file-cv">
                                                    <input id="apply-cv-{{ $cv->id }}" type="radio" name="apply_cv_id"
                                                           value="{{ $cv->id }}">
                                                    <div class="candidate-detail-cv">
                                                        <span class="icon_type">
                                                            <i class="flaticon-file"></i>
                                                        </span>
                                                        <span class="filename">{{ $file->file_name }}</span>
                                                        <span class="extension">{{ $file->file_extension }}</span>
                                                    </div>
                                                </label>
                                            @endif
                                        @endforeach
                                    </div><!-- /.form-group -->
{{--                                    <div class="select-cv">{{ __("or upload your Resume/ CV") }}</div>--}}
                                @else
{{--                                    <p class="text-center text-info mb-4">{{ __("Please upload your cv before applying for a job") }}</p>--}}
                                @endif

                                <div class="bc-drag-area">
                                    <button type="button" data-text="{{ __("Upload CV (doc, docx, pdf)") }}">{{ __("Upload Resume/ CV (doc, docx, pdf)") }}</button>
                                    <input type="file" name="file_cv" accept=".doc,.docx,.pdf" hidden>
                                    <span class="remove-file">x</span>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group mb-3">
                                    <textarea class="form-control" name="message" placeholder="Message" required="required">{{  __('
Hello  :company,

I hope you are doing well. I am interested in the role you posted:  :jobTitle. Based on my experience, I believe I could be a good fit.
Are you open for a quick chat to discuss the position? I would love to learn more. I look forward to hearing from you.'
                                    , ['company' => $row->company->name ?? '', 'jobTitle' => $row->title ])}}
                                    </textarea>
                                </div>
                            </div><!-- /.form-group -->

                            <div class="col-sm-12">
                                <div class="form-group mb-4">
                                    <div class="">
                                        <input type="checkbox" name="terms_and_conditions" value="on" id="register-terms-and-conditions" required="">
                                        <label for="register-terms-and-conditions">
                                            {!! __("You accept our :opentag Terms and Conditions and Privacy Policy :closetag", ['opentag' => '<a href="'.get_page_url(setting_item('terms_and_conditions_id')).'" target="_blank">','closetag'=>'</a>']) !!}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="job_id" value="{{ $row->id }}">
                        <input type="hidden" name="company_id" value="{{ $row->company->id ?? '' }}">
                        <div class="text-center">
                            <button class="theme-btn btn-style-ten" type="submit">{{ __("Apply for Job") }}
                                <span class="spinner-grow spinner-grow-sm icon-loading" role="status" aria-hidden="true"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif
