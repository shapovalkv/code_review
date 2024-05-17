<?php

    namespace Modules\Job\Listeners;

    use Illuminate\Support\Facades\Mail;
    use Modules\Job\Emails\ChangeApplicantsStatusEmail;
    use Modules\Job\Events\EmployerChangeApplicantsStatus;
    use Modules\Job\Models\JobCandidate;
    use Modules\User\Models\User;

    class SendMailChangeApplicantsStatusListen
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
            'applicants_status'         => '[applicants_status]',
            'my_applied_url'         => '[my_applied_url]',
            'employer_name' => '[employer_name]'
        ];
        public $row;

        public function handle(EmployerChangeApplicantsStatus $event)
        {
            $row = $event->row;
            $employer = $row->jobInfo->company->getAuthor;
            $user = User::find($row->candidate_id);
            $status = match ($row->status){
                JobCandidate::REJECTED_STATUS => "did not accept",
                JobCandidate::APPROVED_STATUS => "accepted",
                default =>  $row->status,
            };
            if(!empty($user) && !empty($user->email)) {
                $data = [
                    'job_title' => $row->jobInfo->title ?? '',
                    'job_url' => $row->jobInfo->getDetailUrl() ?? '',
                    'candidate_name' => $user->display_name ?? '',
                    'employer_name' => $employer->name ?? '',
                    'applicants_status' => $status,
                    'my_applied_url' => route("candidate.admin.myApplied")
                ];
                if($user->locale){
                    $old = app()->getLocale();
                    app()->setLocale($user->locale);
                }

                $body = $this->replaceContentEmail($data, setting_item_with_lang('content_email_change_applicants_status',app()->getLocale()));
                Mail::to($user->email)->send(new ChangeApplicantsStatusEmail($body));

            if (!empty($old)) {
                app()->setLocale($old);
            }
        }
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
