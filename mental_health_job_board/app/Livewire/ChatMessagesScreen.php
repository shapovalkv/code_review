<?php

namespace App\Livewire;


use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Modules\User\Constants\ConversationConstant;
use App\Helpers\MessageHelper;
use App\Livewire\Chat\Traits\AddsMessages;
use Modules\User\Services\Chat\ChatMessagesScreenService;
use Modules\User\Services\Chat\ConversationService;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithFileUploads;
use Musonza\Chat\Services\MessageService;
use Throwable;

class ChatMessagesScreen extends Component
{
    use WithFileUploads, AddsMessages;

    protected $listeners = ['messagesLoaded', 'onScreenChanged', 'addNewMessage', 'removeUpload', 'onChatHistoryClear', 'onChatDelete', 'Recalculate', 'chatSelected'];

    protected ConversationService $conversationService;
    protected MessageService $messageService;
    protected MessageHelper $messageHelper;
    protected User $user;

    public $messagesList = [];

    public $files = [];
    public $currentFiles = [];

    public int $filesCountLimit = 5;

    public string $message = "";
    public $chatId = null;
    public ?string $chatTopic = null;
    public ?int $participationId = null;
    public ?string $participantName = null;
    public ?string $avatar = null;
    public ?string $profileLink = null;
    public ?string $role = null;
    public bool $active = true;
    public bool $isShowModalTerms = false;

    protected function resetData()
    {
        $this->filesCountLimit = 5;
        $this->files = [];
        $this->currentFiles = [];
        $this->message = "";
    }

    public function updatedMessage($value)
    {
        $this->emit('updateMessageValue', $value);
    }

    public function __construct()
    {
        parent::__construct();
        $this->conversationService = resolve(ConversationService::class);
        $this->messageService = resolve(MessageService::class);
        $this->user = Auth::user();
        $this->messageHelper = resolve(MessageHelper::class);
    }

    public function chatSelected($chatId)
    {
        $this->chatId = (int)$chatId;
    }

    public function removeFile($index)
    {
        $file = $this->currentFiles[(int)$index] ?? null;

        if ($file) {
            unset($this->currentFiles[$index]);

            $this->currentFiles = array_values($this->currentFiles);
            $file->delete();

            $this->filesCountLimit = min(5, $this->filesCountLimit + 1);
        }
    }

    public function messagesLoaded($data)
    {
        $validator = Validator::make(['data' => $data], [
            'data' => 'required|array',
            'data.chatId' => 'required|integer',
            'data.chatTopic' => 'required|string',
            'data.messages' => 'nullable|array',
            'data.messages.*' => 'required|array',
            'data.messages.*.participation_id' => 'required|integer',
            'data.messages.*.body' => 'required|string',
            'data.messages.*.time' => 'required|string',
            'data.messages.*.created_at' => 'required|date',
            'data.participationId' => 'required|integer',
            'data.participantName' => 'required|string',
            'data.avatar' => 'present|nullable|string',
            'data.role' => 'present|nullable|string',
        ]);

        if (!$validator->fails()) {
            $this->chatId = $data['chatId'];
            $this->chatTopic = $data['chatTopic'] ?? ('Chat #' . $this->chatId);
            $this->participantName = $data['participantName'];
            $this->avatar = $data['avatar'];
            $this->profileLink = $data['profileLink'];
            $this->role = $data['role'];
            $this->message = "";

            $this->messagesList = collect($data['messages'])
                ->groupBy('date_group')
                ->sortKeys()
                ->toArray();

            $this->participationId = $data['participationId'];

            if (
                empty(collect(collect($data['messages']))->firstWhere('sender.name', $this->user->getParticipantDetails($this->user)['name']))
            ) {
                $this->isShowModalTerms = true;
            }
        }
    }

    public function onScreenChanged($active)
    {
        $this->active = (bool)$active;
    }

    public function render(): View
    {
        return view('livewire.chat-messages-screen', ['chatId' => $this->chatId]);
    }

    public function updatedFiles($files)
    {
        try {
            $this->validate(
                array_merge(ConversationConstant::CHAT_VALIDATION_RULES, ['files' => ['sometimes', 'array', 'max:' . $this->filesCountLimit]])
            );

            $newFiles = array_slice($files, 0, $this->filesCountLimit);

            $this->currentFiles = array_merge($this->currentFiles, $newFiles);
            $this->filesCountLimit -= count($newFiles);
        } catch (Throwable $e) {
            $this->files = [];

            throw $e;
        }
    }

    public function onChatHistoryClear(ChatMessagesScreenService $chatMessagesScreenService)
    {
        $chatMessagesScreenService->deleteAllMessages($this->conversationService->getById($this->chatId));
    }

    public function onChatDelete()
    {
        $this->participationId = null;
        $this->chatId = null;
        $this->chatTopic = null;
        $this->participantName = null;
        $this->avatar = null;
        $this->profileLink = null;
        $this->role = null;
    }

    public function addNewMessage($newMessage)
    {
        $this->addPreparedMessage($newMessage);

        if ($newMessage) {
            $this->emit('onLatestMessage', $newMessage['created_at']);
            $this->emit('newMessageAdded');
        }
    }

    public function onMessagesLoaded(string $screen, array $data)
    {
        $this->emitTo($screen, 'messagesLoaded', $data);
    }

    public function updateMessages(ChatMessagesScreenService $chatMessagesScreenService)
    {
        $chat = $this->conversationService->getById($this->chatId);
        $messages = $chatMessagesScreenService->loadMessages($chat);
        $data = $chatMessagesScreenService->getChatData($this);

        $mediaMessages = $messages->get(ConversationConstant::SCREEN_MEDIA, collect())->map(fn($msg) => $this->messageHelper->prepareMessage($msg->toArray()));
        $textMessages = $messages->get(ConversationConstant::SCREEN_MESSAGES, collect())->map(fn($msg) => $this->messageHelper->prepareMessage($msg->toArray()));

        $data['messages'] = $textMessages->merge($mediaMessages)->sortBy('created_at')->values();

        $this->onMessagesLoaded(ConversationConstant::SCREEN_MESSAGES, $data);
        $this->onMessagesLoaded(ConversationConstant::SCREEN_MEDIA, $data);
        $this->emit('updateChatList');
    }
}
