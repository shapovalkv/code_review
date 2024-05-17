<?php

namespace App\Http\Chat\Livewire;

use App\Http\Chat\Services\Chat\ConversationService;
use App\Http\Chat\Services\Chat\MessageNotificationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Http\Chat\Models\Chat\Conversation;
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

    public function reorder($chats)
    {
        return collect($chats)
            ->sortByDesc('last_message_at')
            ->values()
            ->toArray();
    }

    public function chatSearch()
    {
        return $this->getChats();
    }

    public function mount()
    {
        return $this->getChats();
    }

    public function getChats()
    {
        $user = auth()->user();

        $chats =
            $this
                ->conversationService
                ->getConversations(['participants' => [$user], 'private' => 1], ['id', 'data'])
                ->addSelect([
                    'last_message_at' =>
                        fn($query) => $query
                            ->select('created_at')
                            ->from(ConfigurationManager::MESSAGES_TABLE)
                            ->whereColumn(ConfigurationManager::CONVERSATIONS_TABLE . ".id", ConfigurationManager::MESSAGES_TABLE . ".conversation_id")
                            ->latest()
                            ->limit(1)
                ])
                ->when(!empty($search = $this->search), fn($query) => $query->where(function ($query) use ($search) {
                    $query->where('data', 'LIKE', '%' . $search . '%');
                }))
                ->get()
                ->map(function (Conversation $chat) use ($user) {
                    $chat->participantName = $chat->getParticipationPresentationForUser($user->id);
                    $otherUser = $chat->getOtherUser($user->id);
                    $chat->avatar = $otherUser->getAvatarUrl();
                    $chat->isOnline = $otherUser ? $otherUser->isUserOnline() : null;
                    $chat->activity = $otherUser->isUserOnline() ?: $otherUser->last_activity_at;
                    $chat->unReadMesssagesCount = $this->messageNotificationService->unreadCountByChat($chat->id, $user->id);
                    $chat->profileLink = '#';
                    $chat->userName = $user->name;
                    $chat->topic = $chat->data['topic'] ?? null;
                    $chat->unreadMessages = $chat->unReadMesssagesCount;

                    if ($otherUser->role->code == 'employee') {
                        $chat->otherUserParticipantPosition = $otherUser->candidate->title;
                    } else {
                        $chat->otherUserParticipantPosition = "Owner of \"" . $otherUser->company->name . "\"";
                    };

                    return $chat;
                });

        return  $this->chats = $this->reorder($chats);
    }

    public function init()
    {
        if ($this->activeChatId) {
            $this->emit('chatSelected', $this->activeChatId);
        }
    }

    public function recalculateCount()
    {
        $this->messageNotificationService->readAllByChat($this->activeChatId, $this->userId);
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

    public function render()
    {
        return view('livewire.chat-list', ['chats_count' => Auth::user()->chatParticipation()->count()]);
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
