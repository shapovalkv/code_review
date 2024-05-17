<?php

namespace App\Http\Chat\Livewire;

use App\Http\Chat\Constants\ConversationConstant;
use App\Http\Chat\Helpers\MessageHelper;
use App\Http\Chat\Livewire\Chat\Traits\AddsMessages;
use App\Http\Chat\Services\Chat\ConversationService;
use App\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Musonza\Chat\Services\MessageService;

class ChatInput extends Component
{
    use WithFileUploads, AddsMessages;

    protected $listeners = ['newMessage', 'inputSelected'];

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

    public function inputSelected($chatId)
    {
        $this->chatId = (int)$chatId;
    }

    public function __construct()
    {
        parent::__construct();
        $this->conversationService = resolve(ConversationService::class);
        $this->messageService = resolve(MessageService::class);
        $this->user = Auth::user();
        $this->messageHelper = resolve(MessageHelper::class);
    }

    public function newMessage()
    {
        $this->validate(ConversationConstant::CHAT_VALIDATION_RULES);

        $chat = $this->conversationService->getById($this->chatId);

        if (true || $this->user->can('write', $chat)) {

            $newMessage = [];

            $message = $this->message;
            $files = $this->currentFiles;

            $this->resetData();

            if ($message) {

                $newMessage = $this
                    ->conversationService
                    ->sendMessage($chat, $message, $chat->participantFromSender($this->user))
                    ->only(array_merge(ConversationConstant::MESSAGE_FIELDS, ['time']));

                $newMessage = $this->messageHelper->prepareMessage($newMessage);

                $this->emit('addNewMessage', $newMessage);

            }

            if ($files) {
                foreach ($files as $file) {

                    $newMessage = $this
                        ->conversationService
                        ->sendFile($chat, $file, $chat->participantFromSender($this->user))
                        ->only(ConversationConstant::MESSAGE_FIELDS);

                    $newMessage = $this->messageHelper->prepareMessage($newMessage);

                    $this->addPreparedMessage($newMessage);
                    $this->emitTo(ConversationConstant::SCREEN_MEDIA, 'newMediaMessage', $newMessage);
                }
            }
        }
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

    public function render()
    {
        return view('livewire.chat-input');
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
}
