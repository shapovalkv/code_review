<?php

    namespace Modules\Candidate\Listeners;

    use App\Notifications\PrivateChannelServices;
    use Modules\Candidate\Events\CandidateChangeJobInviteStatus;
    use Modules\Job\Models\JobCandidate;
    use Modules\User\Models\User;

    class SendNotifyChangeInviteStatusListener
    {

        public function handle(CandidateChangeJobInviteStatus $event)
        {
            $row = $event->row;
            $user = User::find($row->initiator_id);
            $UserCandidate = User::find($row->candidate_id);
            $candidate = $UserCandidate->candidate;
            $employer = $row->company->getAuthor;

            if(!empty($user)) {
                $status = match ($row->status){
                    JobCandidate::REJECTED_STATUS => "did not accept",
                    JobCandidate::APPROVED_STATUS => "accepted",
                    default =>  $row->status,
                };
                $data = [
                    'id' => $row->id,
                    'event' => 'CandidateChangeJobInviteStatus',
                    'to' => 'employer',
                    'name' => $employer->isEmployerApplied($candidate->id) ? $UserCandidate->getDisplayName() : $UserCandidate->getShortCutName(),
                    'avatar' => $employer->isEmployerApplied($candidate->id) ? $UserCandidate->avatar_url : asset('images/avatar.png'),
                    'link' => route("user.applicants"),
                    'type' => 'apply_job',
                    'message' => __('Candidate: :name :status your invitation to apply for your job posting: :job', [
                        'name' => $employer->isEmployerApplied($candidate->id) ? $UserCandidate->getDisplayName() : $UserCandidate->getShortCutName(),
                        'status' => $status,
                        'job' => $row->jobInfo->title ?? ''])
                ];

                $user->notify(new PrivateChannelServices($data));
            }
        }
    }
