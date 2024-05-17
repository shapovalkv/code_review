<?php

    namespace Modules\Job\Listeners;

    use App\Http\Chat\Constants\ConversationConstant;
    use App\Http\Chat\Models\Chat\Conversation;
    use App\Http\Chat\Services\Chat\ConversationService;
    use App\Notifications\PrivateChannelServices;
    use Illuminate\Support\Facades\Auth;
    use Modules\Job\Events\EmployerChangeApplicantsStatus;
    use App\User;

    class SendChatMessageChangeApplicantsStatusListener
    {
        protected ConversationService $conversationService;

        public function handle(EmployerChangeApplicantsStatus $event)
        {
            $row = $event->row;
            $rowMessage = $event->message;
            $user = Auth::user();
            $targetUser = User::find($row->candidate_id);
            $this->conversationService = resolve(ConversationService::class);

            if(!empty($user) && !empty($targetUser)) {

                $this->conversationService = resolve(ConversationService::class);

                $message = __(
                    ':company_name has updated the status of your application for job :job_name to :status_name. <br><br>
                    :message',
                    [
                        'company_name' => $row->company->name ?? '',
                        'job_name' => $row->jobInfo->title ?? '',
                        'status_name' => $row->status ? str_replace('_', ' ', $row->status) : '',
                        'message' => $rowMessage ?? '',
                    ]
                );

                $this->conversationService->sendAutomaticMessage($user, $targetUser, $message);
            }
        }
    }
