<?php

namespace Modules\Job\Listeners;

use Illuminate\Support\Facades\Mail;
use Modules\Job\Emails\ChangeApplicantsStatusEmail;
use Modules\Job\Events\EmployerChangeApplicantsStatus;
use Modules\User\Models\User;

class SendMailChangeApplicantsStatusListen
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    const CODE = [
        'job_title' => '[job_title]',
        'company_title' => '[company_title]',
        'job_url' => '[job_url]',
        'candidate_first_name' => '[candidate_first_name]',
        'candidate_last_name' => '[candidate_last_name]',
        'applicants_status' => '[applicants_status]',
        'buttonMyAppliedJobs' => '[my_applied_button]'
    ];
    public $row;

    public function handle(EmployerChangeApplicantsStatus $event)
    {
        $row = $event->row;
        $user = User::find($row->candidate_id);
        if (!empty($user) && !empty($user->email)) {
            $data = [
                'job_title' => $row->jobInfo->title ?? '',
                'company_title' => $row->company->name ?? '',
                'job_url' => $row->jobInfo->getDetailUrl() ?? '',
                'candidate_first_name' => $user->first_name ?? '',
                'candidate_last_name' => $user->last_name ?? '',
                'applicants_status' => $row->status ? str_replace('_', ' ', $row->status) : '',
//                    'my_applied_button' => route("user.applied_jobs")
            ];
            if ($user->locale) {
                $old = app()->getLocale();
                app()->setLocale($user->locale);
            }

            $body = $this->replaceContentEmail($data, setting_item_with_lang('content_email_change_applicants_status', app()->getLocale()));
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
                if ($item == "buttonMyAppiledJobs") {
                    $content = str_replace($value, $this->buttonMyAppiledJobs(), $content);
                }

                $content = str_replace($value, @$data[$item], $content);
            }
        }
        return $content;
    }

    public function buttonMyAppiledJobs()
    {
        $link = route('user.applied_jobs');
        $button = '<a style="border-radius: 3px;
                color: #fff;
                display: inline-block;
                text-decoration: none;
                background-color: #3490dc;
                border-top: 10px solid #3490dc;
                border-right: 18px solid #3490dc;
                border-bottom: 10px solid #3490dc;
                border-left: 18px solid #3490dc;" href="' . $link . '">My applieв jobs </a>';
        return $button;
    }

}
