<?php

    namespace Modules\Job\Listeners;

    use App\Notifications\PrivateChannelServices;
    use Modules\Job\Events\CandidateApplyJobSubmit;
    use Modules\User\Models\User;

    class SendNotifyApplyJobSubmitListener
    {

        public function handle(CandidateApplyJobSubmit $event)
        {
            $row = $event->row;
            $user = $row->company->getAuthor ?? $row->jobInfo->user;
            $UserCandidate = User::find($row->candidate_id);
            $candidate = $UserCandidate->candidate;
            $employer = $row->company->getAuthor;

            $data = [
                'id' => $row->id,
                'event'   => 'CandidateApplyJobSubmit',
                'to'      => 'employer',
                'name' => $employer->isEmployerApplied($candidate->id) ? $UserCandidate->getDisplayName() : $UserCandidate->getShortCutName(),
                'avatar' => $employer->isEmployerApplied($candidate->id) ? $UserCandidate->avatar_url : asset('images/avatar.png'),
                'link' => route("user.applicants"),
                'type' => 'apply_job',
                'message' => __(':name has applied for your job post titled :job', ['name' => $employer->isEmployerApplied($candidate->id) ? $UserCandidate->getDisplayName() : $UserCandidate->getShortCutName(), 'job' => $row->jobInfo->title ?? ''])
            ];

            $user->notify(new PrivateChannelServices($data));
        }
    }
