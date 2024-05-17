<?php

    namespace Modules\Job\Listeners;

    use Illuminate\Support\Facades\Mail;
    use Modules\Job\Emails\ApplyJobSubmitEmail;
    use Modules\Job\Events\CandidateApplyJobSubmit;

    class SendMailApplyJobSubmitListener
    {
        /**
         * Create the event listener.
         *
         * @return void
         */
        const CODE = [
            'job_title'    => '[job_title]',
            'job_url'    => '[job_url]',
            'candidate_name'     => '[candidate_name]',
            'candidate_url'     => '[candidate_url]',
            'employer_name'     => '[employer_name]',
            'message'     => '[message]',
            'buttonAllApplicants' => '[all_applicants_url_button]'
        ];
        public $row;

        public function handle(CandidateApplyJobSubmit $event)
        {
            $row = $event->row;
            $user = $row->company->getAuthor ?? $row->jobInfo->user;
            if(!empty($user) && !empty($user->email)) {
                $data = [
                    'job_title' => !empty($row->jobInfo) ? $row->jobInfo->title : null,
                    'job_url' => $row->getDetailUrl() ?? '',
                    'candidate_name' => $row->candidateInfo->getAuthor->getDisplayName() ?? '',
//                    'candidate_url' => $row->candidateInfo->getDetailUrl() ?? '',
                    'employer_name' => $user->display_name ?? '',
                    'message' => $row->message ?? '',
                ];
                if($user->locale){
                    $old = app()->getLocale();
                    app()->setLocale($user->locale);
                }

                $body = $this->replaceContentEmail($data, setting_item_with_lang('content_email_apply_job_submit',app()->getLocale()));
                Mail::to($user->email)->send(new ApplyJobSubmitEmail($body));

                if(!empty($old)){
                    app()->setLocale($old);
                }
            }
        }

        public function replaceContentEmail($data, $content)
        {
            if (!empty($content)) {
                foreach (self::CODE as $item => $value) {
                    if($item == "buttonAllApplicants") {
                        $content = str_replace($value, $this->buttonAllApplicants(), $content);
                    }

                    $content = str_replace($value, @$data[$item], $content);
                }
            }
            return $content;
        }

        public function buttonAllApplicants()
        {
            $link = route('user.applicants');
            $button = '<a style="border-radius: 3px;
                color: #fff;
                display: inline-block;
                text-decoration: none;
                background-color: #3490dc;
                border-top: 10px solid #3490dc;
                border-right: 18px solid #3490dc;
                border-bottom: 10px solid #3490dc;
                border-left: 18px solid #3490dc;" href="' . $link . '">Review candidates</a>';
            return $button;
        }
    }
