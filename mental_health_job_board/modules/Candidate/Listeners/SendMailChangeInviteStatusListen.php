<?php

namespace Modules\Candidate\Listeners;

use Illuminate\Support\Facades\Mail;
use Modules\Candidate\Emails\ChangeJobInviteStatusEmail;
use Modules\Candidate\Events\CandidateChangeJobInviteStatus;
use Modules\Job\Models\JobCandidate;
use Modules\User\Models\User;

class SendMailChangeInviteStatusListen
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
        'employer_name' => '[employer_name]',
        'invite_status' => '[invite_status]',
        'my_applied_url' => '[my_applied_url]'
    ];
    public $row;

    public function handle(CandidateChangeJobInviteStatus $event)
    {
        $row = $event->row;
        $employer = $row->jobInfo->user;
        $candidate = User::find($row->candidate_id);
        $status = match ($row->status){
            JobCandidate::REJECTED_STATUS => "did not accept",
            JobCandidate::APPROVED_STATUS => "accepted",
            default =>  $row->status,
        };
        if (!empty($employer) && !empty($employer->email)) {
            $data = [
                'job_title' => $row->jobInfo->title ?? '',
                'job_url' => $row->jobInfo->getDetailUrl() ?? '',
                'candidate_name' => $employer->isEmployerApplied($candidate->id) ? $candidate->getDisplayName() : $candidate->getShortCutName(),
                'employer_name' => $employer->display_name ?? '',
                'invite_status' => $status,
                'my_applied_url' => route("candidate.admin.myApplied")
            ];
            if ($employer->locale) {
                $old = app()->getLocale();
                app()->setLocale($employer->locale);
            }

            if (!empty(setting_item('invite_candidate_to_job_email'))) {
                $body = $this->replaceContentEmail($data, setting_item_with_lang('invite_candidate_to_job_email', app()->getLocale()));
            } else {
                $body = $this->replaceContentEmail($data, $this->defaultBody());
            }
            Mail::to($employer->email)->send(new ChangeJobInviteStatusEmail($body));

            if (!empty($old)) {
                app()->setLocale($old);
            }
        }
    }

    public function defaultBody()
    {
        $body = '
            <h1>Hello [employer_name]!</h1>
            <p>Candidate: [candidate_name] has [invite_status] your invitation to apply for your job posting: [job_title]</p>
            <p>Regards,<br>' . setting_item('site_title') . '</p>';
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
