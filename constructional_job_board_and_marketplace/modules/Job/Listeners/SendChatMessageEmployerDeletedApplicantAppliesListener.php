<?php

    namespace Modules\Job\Listeners;

    use App\Http\Chat\Services\Chat\ConversationService;
    use Illuminate\Support\Facades\Auth;
    use App\User;
    use Modules\Job\Events\EmployerDeletedApplicantApplies;

    class SendChatMessageEmployerDeletedApplicantAppliesListener
    {
        protected ConversationService $conversationService;

        public function handle(EmployerDeletedApplicantApplies $event)
        {
            $row = $event->row;
            $user = Auth::user();
            $targetUser = User::find($row->candidate_id);
            $this->conversationService = resolve(ConversationService::class);

            if(!empty($user) && !empty($targetUser)) {

                $this->conversationService = resolve(ConversationService::class);

                $message = __(
                    ':company_name has deleted your application for job :job_name.',
                    [
                        'company_name' => $row->company->name ?? '',
                        'job_name' => $row->jobInfo->title ?? '',
                    ]
                );

                $this->conversationService->sendAutomaticMessage($user, $targetUser, $message);
            }
        }
    }
