<?php

namespace App\Livewire;

use App\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Modules\Job\Models\Job;
use Modules\User\Models\Chat\Conversation;
use Modules\User\Models\Chat\Participation;
use Modules\User\Models\Role;
use Modules\User\Services\Chat\ConversationService;
use Modules\User\Services\Chat\MessageNotificationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Musonza\Chat\ConfigurationManager;

class ChatList extends Component
{
    protected $listeners = ['chatSelected', 'onLatestMessage', 'onChatDelete', 'clearHistory', 'chatSearch', 'onRemoveChatId', 'recalculateCount'];
    protected ConversationService $conversationService;
    protected MessageNotificationService $messageNotificationService;

    public array $chats = [];
    public $activeChatId = null;
    public $userId = null;
    public $search = '';

    public function __construct()
    {
        parent::__construct();
        $this->conversationService = resolve(ConversationService::class);
        $this->messageNotificationService = resolve(MessageNotificationService::class);
        $this->userId = auth()->id();
    }

    public function reorder($chats): array
    {
        return collect($chats)
            ->sortByDesc('last_message_at')
            ->values()
            ->toArray();
    }

    public function chatSearch(): array
    {
        return $this->getChats();
    }

    public function mount(): array
    {
        return $this->getChats();
    }

    public function getChats(): array
    {
        $user = auth()->user();

        $participants = [$user];

        if (is_employer($user) && $user->staff->count()) {
            $participants = $user->staff->merge([$user])->all();
        }

        $chats =
            $this
                ->conversationService
                ->getConversations(['participants' => $participants, 'private' => 1], ['id', 'data'])
                ->addSelect([
                    'last_message_at' =>
                        fn($query) => $query
                            ->select('created_at')
                            ->from(ConfigurationManager::MESSAGES_TABLE)
                            ->whereColumn(ConfigurationManager::CONVERSATIONS_TABLE . ".id", ConfigurationManager::MESSAGES_TABLE . ".conversation_id")
                            ->latest()
                            ->limit(1)
                ])
                ->when(!empty($search = $this->search), fn($query) => $query->where(function (Builder $query) use ($search) {
                    $query->orWhereHas(Conversation::RELATION_PARTICIPANTS, static function (Builder $query) use ($search) {
                        $query->whereHas(Participation::RELATION_USER, static function (Builder $query) use ($search) {
                            $query->where('first_name', 'LIKE', '%' . $search . '%')
                                ->orWhere('last_name', 'LIKE', '%' . $search . '%')
                                ->orWhereHas(User::RELATION_COMPANY, static function (Builder $query) use ($search) {
                                    $query->where('name', 'LIKE', '%' . $search . '%');
                                });
                        });
                    });
                }))
                ->get()
                ->map(function (Conversation $chat) use ($user) {
                    $chat->participantName = $chat->getParticipationPresentationForUser($user->id);
                    /** @var User|null $otherUser */
                    $otherUser = $chat->getOtherUser($user->id);
                    if (null === $otherUser) {
                        if ($chat->data['participant_presentation']) {
                            foreach ($chat->data['participant_presentation'] as $key => $participants) {
                                if ($key !== $user->id) {
                                    $otherUser = User::query()->find($key);
                                    break;
                                }
                            }
                        }
                    }
                    if ($otherUser) {

                        $chat->isOnline = $otherUser?->isUserOnline();
                        if (is_employer($otherUser) || is_employee($otherUser)) {
                            $chat->userName = $otherUser->parent ? $otherUser->parent->company->name : $otherUser->company->name;
                            $chat->avatar = $otherUser->getAvatarUrl();
                        } else {
                            $chat->userName = is_applied($otherUser->id) || (!is_employer() && !is_employee()) ? $otherUser->getDisplayName() : $otherUser->getShortCutName();
                            $chat->avatar = is_applied($otherUser->id) ? $otherUser->getAvatarUrl() : asset('images/avatar.png');
                        }
                        if (is_candidate($otherUser) && $otherUser->candidate) {
                            $chat->otherUserParticipantPosition = $otherUser->candidate->title;
                        } else {
                            $chat->otherUserParticipantPosition = $otherUser->first_name . ' ' . $otherUser->last_name;
                        }
                    } else {
                        $chat->avatar = asset('images/avatar.png');
                        $chat->isOnline = false;
                        $chat->userName = '';
                        $chat->otherUserParticipantPosition = '';
                    }

                    $chat->activity = $chat->last_message_at ?? null;
                    $chat->unReadMesssagesCount = $this->messageNotificationService->unreadCountByChat($chat->id, $user->id);
                    $chat->unreadMessages = $chat->unReadMesssagesCount;

                    if (!empty($chat->data['job']['id'])) {
                        $chat->topic = 'Job: ' . Job::query()->find($chat->data['job']['id'])?->title;
                    } else {
                        $chat->topic = 'Common chat';
                    }

                    return $chat;
                });

        return $this->chats = $this->reorder($chats);
    }

    public function init()
    {
        if ($this->activeChatId) {
            $this->emit('chatSelected', $this->activeChatId);
        }
    }

    public function recalculateCount()
    {
        if ($this->activeChatId) {
            $this->messageNotificationService->readAllByChat($this->activeChatId, $this->userId);
        }
    }

    public function clearHistory()
    {
        $index = $this->getChatIndex($this->activeChatId);

        if ($index !== false) {
            $this->chats[$index]['last_message_at'] = null;
            $this->chats = $this->reorder($this->chats);
        }
    }

    public function onRemoveChatId()
    {
        return $this->activeChatId = null;
    }

    public function render(): View
    {
        $this->getChats();

        return view('livewire.chat-list', ['chats' => $this->chats]);
    }

    public function chatSelected($chatId)
    {
        $this->activeChatId = (int)$chatId;
    }

    public function getChatIndex($id)
    {
        return collect($this->chats)->search(fn($chat) => $chat['id'] === $id);
    }

    public function onLatestMessage($date)
    {
        $index = $this->getChatIndex($this->activeChatId);

        if ($index !== false) {
            $this->chats[$index]['last_message_at'] = formatDate($date, 'Y-m-d H:i:s');
            $this->chats = $this->reorder($this->chats);
        }
    }

    public function onChatDelete($id)
    {
        $index = $this->getChatIndex($id);

        if ($index !== false) {
            unset($this->chats[$index]);

            $this->chats = $this->reorder($this->chats);

            $this->activeChatId = $id === $this->activeChatId ? null : $this->activeChatId;
        }
    }
}
