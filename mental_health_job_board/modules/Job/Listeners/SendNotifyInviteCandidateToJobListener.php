<?php

    namespace Modules\Job\Listeners;

    use App\Notifications\PrivateChannelServices;
    use Modules\Job\Events\CandidateApplyJobSubmit;
    use Modules\Job\Events\EmployerInviteCanditateToJob;

    class SendNotifyInviteCandidateToJobListener
    {

        public function handle(EmployerInviteCanditateToJob $event)
        {
            $row = $event->row;
            $user = $row->user;
            $company = $row->jobInfo->company;
            $data = [
                'id' => $row->id,
                'event'   => 'EmployerInviteCanditateToJob',
                'to'      => 'candidate',
                'name' => $user->display_name ?? '',
                'avatar' => $company->getAvatarUrl() ?? '',
                'link' => route("user.applied_jobs"),
                'type' => 'apply_job',
                'message' => __(':company-name has invited you to the job :job', ['company-name' => $company->name ?? '', 'job' => $row->jobInfo->title ?? ''])
            ];

            $user->notify(new PrivateChannelServices($data));
        }
    }
