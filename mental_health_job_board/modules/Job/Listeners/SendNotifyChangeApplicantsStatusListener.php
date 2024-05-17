<?php

    namespace Modules\Job\Listeners;

    use App\Notifications\PrivateChannelServices;
    use Modules\Job\Events\EmployerChangeApplicantsStatus;
    use Modules\Job\Models\JobCandidate;
    use Modules\User\Models\User;

    class SendNotifyChangeApplicantsStatusListener
    {

        public function handle(EmployerChangeApplicantsStatus $event)
        {
            $row = $event->row;
            $user = User::find($row->candidate_id);
            $status = match ($row->status){
                JobCandidate::REJECTED_STATUS => "did not accept",
                JobCandidate::APPROVED_STATUS => "accepted",
                default =>  $row->status,
            };
            if(!empty($user)) {
                $data = [
                    'id' => $row->id,
                    'event' => 'EmployerChangeApplicantsStatus',
                    'to' => 'employer',
                    'name' => $user->display_name ?? '',
                    'avatar' => $row->company->getAuthor->avatar_url ?? ($row->jobInfo->user->avatar_url ?? ''),
                    'link' => route("user.applied_jobs"),
                    'type' => 'apply_job',
                    'message' => __('Employer :status your application to job :job', ['status' => $status, 'job' => $row->jobInfo->title ?? ''])
                ];

                $user->notify(new PrivateChannelServices($data));
            }
        }
    }
