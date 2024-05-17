<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Modules\User\Constants\ConversationConstant;
use Modules\User\Models\Chat\Message;
use Modules\User\Models\Role;
use Modules\User\Services\Chat\ConversationService;

class SendChatMessageToUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chat:message';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send message to chat for users';
    private ConversationService $conversationService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ConversationService $conversationService)
    {
        parent::__construct();

        $this->conversationService = $conversationService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {

        /** @var User $admin */
        $admin = User::query()->find(876);
//        $admin = User::query()->find(194);

        $users = User::query()->where('role_id', Role::CANDIDATE)->get();

        $this->info('Total candidates: ' . count($users));

        $bar = $this->output->createProgressBar(count($users));

        $bar->start();

        foreach ($users as $user) {
            $key = $user->id + $admin->id;
            $params = [
                'participants' => [$admin, $user],
                'search'       => ['key' => $key],
                'data'         => [
                    'key'                      => $key,
                    'chat_type'                => ConversationConstant::CHAT_TYPE_GENERAL,
                    'participant_presentation' => [
                        $user->id => [
                            $admin->id => $admin->name,
                        ],
                        $admin->id       => [
                            $user->id => $user->name,
                        ],
                    ],
                ],
            ];

            if ($this->conversationService->findConversation([$admin, $user])) {
//                $this->info('Exists ' . $user->name);
                $bar->advance();

                continue;
            }

            $conversation = $this->conversationService->findOrCreate($params);

            /** @var Message $message */
            $message = $conversation->messages()->make();
            $message->send($conversation, view('console-chat-message')->render(), $conversation->participantFromSender($admin), 'notice');

            $bar->advance();

//            $this
//                ->conversationService
//                ->sendMessage($conversation, view('console-chat-message')->render(), $conversation->participantFromSender($admin), 'notice')
//                ->only(array_merge(ConversationConstant::MESSAGE_FIELDS, ['time']));

//            $initiatorParticipant = $conversation->participants->where('messageable_id', $admin->id)->first();
//            $initiatorParticipant->delete();

//            $this->info('Sent to ' . $user->name);
        }

        $bar->finish();

        return Command::SUCCESS;
    }
}
