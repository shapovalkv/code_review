<?php

namespace App\Livewire;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\RateLimiter;
use Modules\User\Constants\ConversationConstant;
use App\Helpers\MessageHelper;
use App\Http\Chat\Livewire\Throwable;
use App\Livewire\Chat\Traits\AddsMessages;
use Modules\User\Models\Chat\Conversation;
use Modules\User\Services\Chat\ConversationService;
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

    public function newMessage(): void
    {
        $this->validate(ConversationConstant::CHAT_VALIDATION_RULES, ['message.required' => '']);
        $user = $this->user;
        $userIds = [$user->id];

        if(is_employer($user) && $user->staff->count()) {
            $userIds = $user->staff->pluck('id')->toArray();
            $userIds[] = $user->id;
        }

        /** @var Conversation $chat */
        $chat = Conversation::query()
            ->where('id', $this->chatId)
            ->whereHas('participants', static function (Builder $builder) use ($userIds) {
                $builder->whereIn('messageable_id', $userIds)->whereNull('deleted_at');
            })
            ->first();

        if ($chat->activeParticipants->count() > 1) {
            $message = $this->message;

            $this->resetData();

            if ($message) {

                if(is_employer($this->user) && $this->user->staff->count() && ($staff = $this->user->staff->whereIn('id', array_keys($chat->data['participant_presentation']))->first())) {
                    $author = $chat->participantFromSender($staff);
                } else {
                    $author = $chat->participantFromSender($user);
                }

                $newMessage = $this
                    ->conversationService
                    ->sendMessage($chat, $message, $author)
                    ->only(array_merge(ConversationConstant::MESSAGE_FIELDS, ['time']));

                $newMessage = $this->messageHelper->prepareMessage($newMessage);

                $this->emit('addNewMessage', $newMessage);

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
        } catch (\Throwable $e) {
            $this->files = [];

            throw $e;
        }
    }
}
