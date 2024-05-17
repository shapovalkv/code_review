<?php

    namespace Modules\Job\Listeners;

    use Illuminate\Support\Facades\Mail;
    use Modules\Job\Emails\InviteCandidateToJobEmail;
    use Modules\Job\Events\EmployerInviteCanditateToJob;

    class SendMailInviteCandidateToJobListener
    {
        /**
         * Create the event listener.
         *
         * @return void
         */
        const CODE = [
            'job_title' => '[job_title]',
            'job_url' => '[job_url]',
            'candidate_name' => '[candidate_name]',
            'candidate_url' => '[candidate_url]',
            'employer_name' => '[employer_name]',
            'company_name' => '[company_name]',
            'message' => '[message]',
            'all_applicants_url' => '[all_applicants_url]'
        ];
        public $row;

        public function handle(EmployerInviteCanditateToJob $event)
        {
            $row = $event->row;
            $user = $row->user;
            if(!empty($user) && !empty($user->email)) {
                $data = [
                    'job_title' => $row->jobInfo->title ?? '',
                    'job_url' => $row->jobInfo->getDetailUrl() ?? '',
                    'candidate_name' => $row->candidateInfo->user->getDisplayName() ?? '',
                    'candidate_url' => $row->candidateInfo->getDetailUrl() ?? '',
                    'employer_name' => $row->jobInfo->user->display_name ?? '',
                    'company_name' => $row->jobInfo->company->name ?? '',
                    'message' => $row->message ?? '',
                    'all_applicants_url' => route("user.applicants")
                ];
                if($user->locale){
                    $old = app()->getLocale();
                    app()->setLocale($user->locale);
                }

                if (!empty(setting_item('invite_candidate_to_job_email'))) {
                    $body = $this->replaceContentEmail($data, setting_item_with_lang('invite_candidate_to_job_email', app()->getLocale()));
                } else {
                    $body = $this->replaceContentEmail($data, $this->defaultBody());
                }
                Mail::to($user->email)->send(new InviteCandidateToJobEmail($body));

                if(!empty($old)){
                    app()->setLocale($old);
                }
            }
        }

        public function defaultBody()
        {
            $body = '
            <h1>Hello [candidate_name]!</h1>
            <p>[company_name] has invited you to apply to: [job_title]</p>
            <p>Message: [message]</p>';
            return $body;
        }

        public function replaceContentEmail($data, $content)
        {
            if (!empty($content)) {
                foreach (self::CODE as $item => $value) {
                    $content = str_replace($value, @$data[$item], $content);
                }
            }
            return $content;
        }

    }
