<?php

namespace App\Livewire;


use Modules\User\Constants\ConversationConstant;
use App\Helpers\MessageHelper;
use App\Livewire\Chat\Traits\AddsMessages;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ChatMediaPhotosTab extends Component
{
    use AddsMessages;

    protected $listeners = ['messagesLoaded', 'onMediaTabChanged', 'newMediaMessage'];
    protected MessageHelper $messageHelper;

    public bool $active = true;
    public array $messagesList = [];

    public function __construct()
    {
        parent::__construct();
        $this->messageHelper = resolve(MessageHelper::class);
    }

    public function render()
    {
        return view('livewire.chat-media-photos-tab');
    }

    public function messagesLoaded($data)
    {
        $validator = Validator::make(['data' => $data], [
            'data' => 'required|array',
            'data.messages' => 'nullable|array',
            'data.messages.*' => 'required|array',
            'data.messages.*.*' => 'required|array',
            'data.messages.*.*.body' => 'required|string',
            'data.messages.*.*.id' => 'required|integer',
        ]);

        if (!$validator->fails()) {
            $this->messagesList = collect($data['messages'])
                ->map(
                    fn ($group) =>
                        collect($group)
                            ->filter(fn ($item) => $this->messageHelper->fileExists(
                                decrypt($item['original_body'])
                            ))
                            ->map(fn ($item) => $this->messageHelper->preparePhotoMessage($item))
                        )
                ->toArray();
        }
    }

    public function onMediaTabChanged($active)
    {
        $this->active = (bool)$active;
    }

    public function newMediaMessage($message)
    {
        $validator = Validator::make(['message' => $message], [
            'message' => 'required|array',
            'message.id' => 'required|integer',
            'message.body' => 'required|string',
            'message.type' => ['required', 'string', Rule::in(ConversationConstant::MESSAGE_TYPE_PHOTO)],
            'message.created_at' => 'required|date'
        ]);

        if (!$validator->fails()) {
            $this->addPreparedMessage($this->messageHelper->preparePhotoMessage($message));
        }
    }
}
