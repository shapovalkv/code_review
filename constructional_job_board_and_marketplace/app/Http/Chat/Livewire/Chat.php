<?php

namespace App\Http\Chat\Livewire;

use App\Http\Chat\Services\Chat\ChatMessagesScreenService;
use App\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Http\Chat\Constants\ConversationConstant;
use App\Http\Chat\Helpers\MessageHelper;
use App\Http\Chat\Models\Chat\Conversation;
use App\Http\Chat\Services\Chat\ConversationService;
use App\Http\Chat\Services\Chat\MessageNotificationService;
use Musonza\Chat\Services\MessageService;

class Chat extends Component
{
    use AuthorizesRequests;

    protected $listeners = ['chatSelected', 'onScreenChange', 'clearHistory', 'deleteChat'];

    protected ConversationService $conversationService;
    protected MessageService $messageService;
    protected User $user;
    protected MessageHelper $messageHelper;
    protected MessageNotificationService $messageNotificationService;

    public array $messageList = [];

    public $files = [];

    public ?int $participationId = null;
    public ?int $chatId = null;

    public ?string $chatTopic = null;
    public ?string $participantName = null;
    public ?string $avatar = null;
    public ?string $profileLink = null;
    public ?string $role = null;

    public function __construct()
    {
        parent::__construct();
        $this->conversationService = resolve(ConversationService::class);
        $this->messageService = resolve(MessageService::class);
        $this->user = Auth::user();
        $this->messageHelper = resolve(MessageHelper::class);
        $this->messageNotificationService = resolve(MessageNotificationService::class);
    }

    public function chatSelected(ChatMessagesScreenService $chatMessagesScreenService, $id = null)
    {
        $chat = $this->conversationService->getById($id);

//        if ($this->user->can('read', $chat)) {
        $messages = $chatMessagesScreenService->loadMessages($chat);

        $this->participationId = $chat->participantFromSender($this->user)->id;
        $this->chatId = $chat->id;
        $this->chatTopic = $chat['data']['topic'] ?? null;
        $this->participantName = $chat->getParticipationPresentationForUser($this->user->id);
        $this->avatar = $chat->getOtherUser($this->user->id)->getAvatarUrl();

        $mediaMessages = $messages->get(ConversationConstant::SCREEN_MEDIA, collect())->map(fn($msg) => $this->messageHelper->prepareMessage($msg->toArray()));
        $textMessages = $messages->get(ConversationConstant::SCREEN_MESSAGES, collect())->map(fn($msg) => $this->messageHelper->prepareMessage($msg->toArray()));

        $data = $chatMessagesScreenService->getChatData($this);

        $this->onMessagesLoaded(
            ConversationConstant::SCREEN_MESSAGES,
            array_merge($data, ['messages' => $textMessages->merge($mediaMessages)->sortBy('created_at')->values()])
        );

        $this->onMessagesLoaded(
            ConversationConstant::SCREEN_MEDIA,
            array_merge($data, ['messages' => $mediaMessages])
        );


        $this->messageNotificationService->readAllByChat($chat->id, $this->user->id);

        $url = route('user.chat.index', ['conversation' => $chat->id]);
        $this->emit('chatReady', $url);
//        }
    }

    public function onMessagesLoaded(string $screen, array $data)
    {
        $this->emitTo($screen, 'messagesLoaded', $data);
    }

    public function render()
    {
        return view('livewire.chat');
    }

    public function onScreenChange(string $screenName)
    {
        foreach (array_keys(ConversationConstant::SCREENS) as $key) {
            $this->emitTo($key, 'onScreenChanged', $screenName === $key);
        }
    }

    public function clearHistory()
    {
        $chat = $this->conversationService->getById($this->chatId);

        $this->authorize('clearHistory', $chat);

        if ($this->conversationService->clearHistory($chat)) {
            $this->onChatHistoryClear();
        }
    }

    public function onChatHistoryClear(ChatMessagesScreenService $chatMessagesScreenService)
    {
        $data = $chatMessagesScreenService->getChatData($this);

        $this->onMessagesLoaded(ConversationConstant::SCREEN_MESSAGES, array_merge($data, ['messages' => []]));
        $this->onMessagesLoaded(ConversationConstant::SCREEN_MEDIA, array_merge($data, ['messages' => []]));

        $this->emit('onChatHistoryClear');
    }

    public function deleteChat()
    {
        $chat = $this->conversationService->getById($this->chatId);

        $this->authorize('delete', $chat);

        if ($this->conversationService->deleteChat($chat)) {
            $this->onChatHistoryClear();
            $this->emit('onChatDelete', $this->chatId);

            $this->participationId = null;
            $this->chatId = null;
            $this->chatTopic = null;
            $this->participantName = null;
            $this->avatar = null;
            $this->role = null;
        }
    }
}
