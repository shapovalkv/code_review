<?php

    namespace Modules\Job\Listeners;

    use App\Notifications\PrivateChannelServices;
    use Modules\Core\Models\Notification;
    use Modules\Job\Events\EmployerDeletedApplicantApplies;
    use Modules\User\Models\User;

    class SendNotifyEmployerDeletedApplicantAppliesListener
    {

        public function handle(EmployerDeletedApplicantApplies $event)
        {
            $row = $event->row;
            $user = User::find($row->candidate_id);
            if(!empty($user)) {
                $data = [
                    'message_type' => Notification::USERS_NOTIFICATION,
                    'id' => $row->id,
                    'event' => 'EmployerDeletedApplicantApplies',
                    'to' => 'employer',
                    'name' => $user->display_name ?? '',
                    'avatar' => $row->company->getAuthor->avatar_url ?? ($row->jobInfo->user->avatar_url ?? ''),
                    'link' => route("user.applied_jobs"),
                    'type' => 'apply_job',
                    'message' => __(
                        ':company_name has deleted your application for job :job_name.',
                        [
                            'company_name' => $row->company->name ?? '',
                            'job_name' => $row->jobInfo->title ?? '',
                        ]
                    )
                ];

                $user->notify(new PrivateChannelServices($data));
            }
        }
    }
