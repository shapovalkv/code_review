<?php

namespace App\Http\Chat\Livewire;


use App\Http\Chat\Constants\ConversationConstant;
use App\Http\Chat\Helpers\MessageHelper;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Livewire\Component;
use App\Http\Chat\Livewire\Chat\Traits\AddsMessages;

class ChatMediaFilesTab extends Component
{
    use AddsMessages;

    protected $listeners = ['onMediaTabChanged', 'messagesLoaded', 'newMediaMessage'];
    protected MessageHelper $messageHelper;

    public bool $active = false;
    public array $messagesList = [];

    public function __construct()
    {
        $this->messageHelper = resolve(MessageHelper::class);
    }

    public function render()
    {
        return view('livewire.chat-media-files-tab');
    }

    public function messagesLoaded($data)
    {
        $validator = Validator::make(['data' => $data], [
            'data' => 'required|array',
            'data.messages' => 'nullable|array',
            'data.messages.*' => 'required|array',
            'data.messages.*.*' => 'required|array',
            'data.messages.*.*.id' => 'required|integer',
            'data.messages.*.*.body' => 'required|string',
            'data.messages.*.*.created_at' => 'required|date',
        ]);

        if (!$validator->fails()) {
            $this->messagesList = collect($data['messages'])
                ->map(
                    fn ($group) =>
                        collect($group)
                            ->filter(fn ($item) => $this->messageHelper->fileExists(decrypt($item['original_body'])))
                            ->map(fn ($item) => $this->messageHelper->prepareFileMessage($item))
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
            'message.type' => ['required', 'string', Rule::in(ConversationConstant::MESSAGE_TYPE_FILE)],
            'message.created_at' => 'required|date'
        ]);

        if (!$validator->fails()) {
            $this->addPreparedMessage($this->messageHelper->prepareFileMessage($message));
        }
    }
}
