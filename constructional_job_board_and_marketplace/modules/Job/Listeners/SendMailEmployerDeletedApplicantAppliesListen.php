<?php

namespace Modules\Job\Listeners;

use Illuminate\Support\Facades\Mail;
use Modules\Job\Emails\ChangeApplicantsStatusEmail;
use Modules\Job\Emails\EmployerDeletedApplicantAppliesEmail;
use Modules\Job\Events\EmployerDeletedApplicantApplies;
use Modules\User\Models\User;

class SendMailEmployerDeletedApplicantAppliesListen
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
    ];
    public $row;

    public function handle(EmployerDeletedApplicantApplies $event)
    {
        $row = $event->row;
        $user = User::find($row->company->owner_id ?? $row->author_id);
        if(!empty($user) && !empty($user->email)) {
            $data = [
                'job_title' => $row->jobInfo->title ?? '',
                'company_title' => $row->company->name ?? '',
                'job_url' => $row->jobInfo->getDetailUrl() ?? '',
                'candidate_first_name' => $user->first_name ?? '',
                'candidate_last_name' => $user->last_name ?? '',
            ];
            if($user->locale){
                $old = app()->getLocale();
                app()->setLocale($user->locale);
            }

            if (!empty(setting_item('content_email_delete_applicant_applies'))) {
                $body = $this->replaceContentEmail($data, setting_item_with_lang('content_email_delete_applicant_applies', app()->getLocale()));
            } else {
                $body = $this->replaceContentEmail($data, $this->defaultBody());
            }
            Mail::to($user->email)->send(new EmployerDeletedApplicantAppliesEmail($body));

            if(!empty($old)){
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

    public function defaultBody()
    {
        $body = '
            <h1>Hello [candidate_first_name] [candidate_last_name]!</h1>
            <p>[company_title] has deleted your application for job [job_title].</p>
            <p>Regards,<br>' . setting_item('site_title') . '</p>';
        return $body;
    }

}
