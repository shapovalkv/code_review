<?php

    namespace Modules\Job\Listeners;

    use App\Notifications\PrivateChannelServices;
    use Modules\Core\Models\Notification;
    use Modules\Job\Events\EmployerChangeApplicantsStatus;
    use Modules\User\Models\User;

    class SendNotifyChangeApplicantsStatusListener
    {

        public function handle(EmployerChangeApplicantsStatus $event)
        {
            $row = $event->row;
            $user = User::find($row->candidate_id);
            if(!empty($user)) {
                $data = [
                    'message_type' => Notification::USERS_NOTIFICATION,
                    'id' => $row->id,
                    'event' => 'EmployerChangeApplicantsStatus',
                    'to' => 'employer',
                    'name' => $user->display_name ?? '',
                    'avatar' => $row->company->getAuthor->avatar_url ?? ($row->jobInfo->user->avatar_url ?? ''),
                    'link' => route("candidate.applied_jobs"),
                    'type' => 'apply_job',
                    'message' => __(
                        ':company_name has updated the status of your application for job :job_name to :status_name.',
                        [
                            'company_name' => $row->company->name ?? '',
                            'job_name' => $row->jobInfo->title ?? '',
                            'status_name' =>  $row->status ? str_replace('_', ' ', $row->status) : '',

                        ]
                    )
                ];

                $user->notify(new PrivateChannelServices($data));
            }
        }
    }
