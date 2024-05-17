<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Modules\Job\Models\Job;
use Modules\User\Constants\ConversationConstant;
use App\Helpers\MessageHelper;
use App\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Modules\User\Models\Chat\Conversation;
use Modules\User\Services\Chat\ChatMessagesScreenService;
use Modules\User\Services\Chat\ConversationService;
use Modules\User\Services\Chat\MessageNotificationService;
use Musonza\Chat\Services\MessageService;

class Chat extends Component
{
    use AuthorizesRequests;

    protected $listeners = ['chatSelected', 'onScreenChange', 'clearHistory', 'deleteChat', 'onScreenChanged'];

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
    public ?string $jobLink = null;
    public ?string $role = null;
    public bool $active = true;
    public bool $isClosed = false;

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
        $userIds = [$this->user->id];

        if(is_employer($this->user) && $this->user->staff->count()) {
            $userIds = $this->user->staff->pluck('id')->merge($this->user->id)->toArray();
        }
        /** @var Conversation $chat */
        $chat = Conversation::query()
            ->where('id', $id)
            ->whereHas('participants', static function (Builder $builder) use ($userIds) {
                $builder->whereIn('messageable_id', $userIds)->whereNull('deleted_at');
            })
            ->first();

        if (null === $chat) {
            $url = route('user.chat.index');
            $this->emit('chatReady', $url);
            return;
        }

//        if ($this->user->can('read', $chat)) {
        $messages = $chatMessagesScreenService->loadMessages($chat);

        $otherUser = $chat->getOtherUser($this->user->id);
        if(is_employer($this->user) && $this->user->staff->count() && ($staff = $this->user->staff->whereIn('id', array_keys($chat->data['participant_presentation']))->first())) {
            $this->participationId = $chat->participantFromSender($staff)->id;
        } else {
            $this->participationId = $chat->participantFromSender($this->user)->id;
        }

        $this->chatId = $chat->id;
        if (!empty($chat->data['job']['id'])) {
            $this->chatTopic = 'Job: ' . Job::query()->find($chat->data['job']['id'])?->title;
        } else {
            $this->chatTopic = 'Common chat';
        }

        if (is_employer($otherUser) || is_employee($otherUser)) {
            $this->participantName = $otherUser->parent ? $otherUser->name . ' (' . $otherUser->parent->company->name . ')' : $otherUser->company->name;
        } else {
            $this->participantName = is_applied($otherUser->id) || (!is_employer() && !is_employee()) ? $otherUser->getDisplayName() : $otherUser->getShortCutName();
        }

        if ($otherUser) {
            $this->avatar = is_applied($otherUser->id) || !is_employer() ? $otherUser->getAvatarUrl() : asset('images/avatar.png');
            if (is_employer($otherUser) || is_employee($otherUser)) {
                $this->profileLink = route('companies.detail', ['slug' => ($otherUser->parent ?? $otherUser)->company->slug]);
            } else {
                $this->profileLink = route('candidate.detail', ['candidate' => $otherUser->id]);
            }
        } else {
            $this->profileLink = null;
            $this->avatar = asset('images/avatar.png');
        }

        if (!empty($chat['data']['job']['id'])) {
            $this->jobLink = route('job.detail', ['slug' => Job::query()->find($chat['data']['job']['id'])?->slug]);
        }

        $this->isClosed = $chat->activeParticipants->count() < 2;

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

    public function onScreenChanged($active)
    {
        $this->active = (bool)$active;
    }

    public function isClosed(): bool
    {
        /** @var Conversation|null $chat */
        $chat = $this->conversationService->getById($this->chatId);

        if ($chat->activeParticipants->count() < 2) {
            $this->isClosed = true;
        }

        return $this->isClosed;
    }

    public function onMessagesLoaded(string $screen, array $data)
    {
        $this->emitTo($screen, 'messagesLoaded', $data);
    }

    public function render(ConversationService $conversationService): View
    {
        return view('livewire.chat', [
            'count' => $conversationService
                ->getConversations(['participants' => [auth()->user()], 'private' => 1], ['id', 'data'])
                ->count()
        ]);
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
            $this->onChatHistoryClear(app()->make(ChatMessagesScreenService::class));
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

        if (null === $chat) {
            $this->emit('chatReady', route('user.chat.index'));
            return;
        }

//        $this->authorize('delete', $chat);

        if ($this->conversationService->deleteChat($chat, $this->user)) {
            $this->onChatHistoryClear(app()->make(ChatMessagesScreenService::class));
            $this->emit('deleteChat');

            $this->participationId = null;
            $this->chatId = null;
            $this->chatTopic = null;
            $this->participantName = null;
            $this->avatar = null;
            $this->role = null;

            $this->emit('chatReady', route('user.chat.index'));
        }
    }
}
