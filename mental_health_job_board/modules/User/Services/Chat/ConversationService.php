<?php

namespace Modules\User\Services\Chat;

use App\Events\NewMessageToChat;
use App\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Modules\User\Models\Chat\Conversation;
use Modules\User\Models\Chat\Message;
use Modules\User\Models\Interfaces\IsMessageable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Livewire\TemporaryUploadedFile;
use Modules\User\Constants\ConversationConstant;
use Modules\User\Helpers\MessageHelper;
use Musonza\Chat\ConfigurationManager;
use Musonza\Chat\Models\Participation;
use Musonza\Chat\Services\ConversationService as Base;
use Ramsey\Uuid\Uuid;

class ConversationService extends Base
{
    public function __construct(Conversation $conversation)
    {
        parent::__construct($conversation);

        $this->conversation = $conversation;
    }

    public function sendPrivateMessage(IsMessageable $from, string $body, array $params = [], string $type = 'text')
    {
        return DB::transaction(function () use ($from, $params, $body, $type) {
            $conversation = $this->findOrCreate($params);
            $participantFrom = $conversation->participantFromSender($from);

            return $this->sendMessage($conversation, $body, $participantFrom, $type);
        });
    }

    public function findOrCreate(array $params = [])
    {
        $participants = $params['participants'] ?? [];

        return $this->findConversation($participants, $params['search'] ?? [])
            ?: $this->createPrivateConversation($participants, $params['data'] ?? []);
    }

    public function findConversation(array $participants = [], array $data = [])
    {
        return $this->getConversations([
            'participants' => $participants,
            'data'         => $data,
            'private'      => 1,
        ])->first();
    }

    public function createPrivateConversation(array $participants = [], array $data = [])
    {
        return $this->conversation->start([
            'participants'   => $participants,
            'direct_message' => false,
            'data'           => $data
        ]);
    }

    public function getConversations(array $params = [], array $columns = ['*']): Builder
    {
        $participants = $params['participants'] ?? [];

        return $this
            ->conversation
            ->select($columns)
            ->when(
                !empty($participants),
                fn($query) => $query->where(function ($query) use ($participants) {

                    $query->whereIn('id',
                        fn($query) => $query
                            ->select('conversation_id')
                            ->from(ConfigurationManager::PARTICIPATION_TABLE)
                            ->where(static function ($query) use ($participants) {
                                /** @var User $participant */
                                foreach ($participants as $participant) {
                                    $query->orWhere(static function ($query) use ($participant) {
                                        $query->where('messageable_id', $participant->getKey())
                                            ->where('messageable_type', $participant->getMorphClass());
                                    });
                                }
                            })
                            ->whereNull('deleted_at')
                    );
                })
            )
            ->when(isset($params['private']), fn($query) => $query->where('private', $params['private']))
            ->when(isset($params['direct_message']), fn($query) => $query->where('direct_message', $params['direct_message']))
            ->when(!empty($params['data']), fn($query) => $query->whereJsonContains('data', $params['data']));
    }

    public function sendFile(Conversation $conversation, TemporaryUploadedFile $file, Participation $participation)
    {
        return DB::transaction(function () use ($conversation, $file, $participation) {

            $name = $file->getClientOriginalName();
            $id = Uuid::uuid4()->toString();
            $extension = $file->guessExtension();
            $nameToStore = "$id.$extension";
            $messageHelper = resolve(MessageHelper::class);

            $path = $file->storeAs(
                $messageHelper->getFilePath($conversation->id), $nameToStore
            );

            $type = $messageHelper->typeByExtension($extension);

            $message = $this->sendMessage($conversation, "$path|$name", $participation, $type);

            return $message;
        });
    }

    public function sendMessage(Conversation $conversation, string $body, Participation $participation, string $type = "text"): Model
    {
        /** @var Message $message */
        $message = $conversation->messages()->make();

        RateLimiter::attempt('chat:' . $conversation->id . ':' . $participation->messageable_id, 1, function() use ($conversation, $participation) {
            event(new NewMessageToChat($conversation, $participation));
        }, 3600);

        return $message->send($conversation, $body, $participation, $type);
    }

    public function clearHistory(Conversation $conversation)
    {
        $messageHelper = resolve(MessageHelper::class);

        return DB::transaction(function () use ($messageHelper, $conversation) {
            $messages = $conversation->messages;

            foreach ($messages as $message) {
                if ($messageHelper->isDownloadable($message->type)) {
                    File::delete($messageHelper->getFileStoragePath($message->body));
                }

                $message->delete();
            }

            return true;
        });
    }

    public function deleteChat(Conversation $conversation, User $initiator)
    {
        return DB::transaction(function () use ($conversation, $initiator) {
            /** @var \Modules\User\Models\Chat\Participation $initiatorParticipant */
            $initiatorParticipant = $conversation->participants->where('messageable_id', $initiator->id)->first();
            $initiatorParticipant->delete();

            if ($conversation->activeParticipants->where('messageable_id', '!=', $initiator->id)->count() === 0) {
                return $this->clearHistory($conversation) && $conversation->delete();
            } else {
                $this->sendMessage(
                    $conversation,
                    $initiator->name . ' deleted the chat',
                    $initiatorParticipant,
                    'notice'
                );

                return true;
            }
        });
    }

    public function sendAutomaticMessage($user, $targetUser, $message)
    {
        $key = $targetUser->id + $user->id;
        $params = [
            'participants' => [$user, $targetUser],
            'search'       => ['key' => $key],
            'data'         => [
                'key'                      => $key,
                'chat_type'                => ConversationConstant::CHAT_TYPE_GENERAL,
                'participant_presentation' => [
                    $user->id       => [
                        $targetUser->id => $targetUser->name,
                    ],
                    $targetUser->id => [
                        $user->id => $user->name,
                    ],
                ],
            ],
        ];

        $chat = $this->findOrCreate($params);
        $this->sendMessage($chat, $message, $chat->participantFromSender($user));
    }
}
