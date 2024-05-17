<?php

namespace App\Livewire;

use Modules\User\Constants\ConversationConstant;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Livewire\Component;
use function App\Http\Chat\Livewire\humanReadableDate;

class ChatMediaScreen extends Component
{
    protected $listeners = ['messagesLoaded', 'onScreenChanged', 'onMediaTabChange', 'newMediaMessage'];

    public $messages = [];
    public bool $active = false;
    public array $counters = [
        ConversationConstant::TAB_PHOTOS => 0,
        ConversationConstant::TAB_FILES => 0
    ];

    public function render()
    {
        return view('livewire.chat-media-screen');
    }

    public function messagesLoaded($data)
    {
        $validator = Validator::make(['data' => $data], [
            'data' => 'required|array',
            'data.chatId' => 'required|integer',
            'data.messages' => 'nullable|array',
            'data.messages.*' => 'required|array',
            'data.messages.*.body' => 'required|string',
            'data.messages.*.type' => ['required', 'string', Rule::in(array_keys(ConversationConstant::MESSAGE_TYPES))],
            'data.messages.*.created_at' => 'required|date',
        ]);

        if (!$validator->fails()) {

            $messages = collect($data['messages'])
                ->groupBy('type');

            foreach (array_keys(ConversationConstant::TABS) as $tabKey) {

                $tabMessages = $messages->get($tabKey, collect());
                $this->counters[$tabKey] = $tabMessages->count();

                $this->emitTo(ConversationConstant::TAB_COMPONENTS[$tabKey], 'messagesLoaded', ['messages' => $tabMessages->groupBy(fn ($msg) => humanReadableDate($msg['created_at']))]);
            }
        }
    }

    public function onScreenChanged($active)
    {
        $this->active = (bool)$active;
    }

    public function onMediaTabChange($tabKey)
    {
        $newTabKey = isset(ConversationConstant::TABS[$tabKey]) ? $tabKey : ConversationConstant::DEFAULT_TAB;

        if ($newTabKey !== $this->activeTab) {
            foreach (ConversationConstant::TAB_COMPONENTS as $key => $component) {
                $this->emitTo($component, 'onMediaTabChanged', $tabKey === $key);
            }

            $this->activeTab = $newTabKey;
        }
    }

    public function newMediaMessage($message)
    {
        $validator = Validator::make(['message' => $message], [
            'message' => 'required|array',
            'message.id' => 'required|integer',
            'message.body' => 'required|string',
            'message.type' => ['required', 'string', Rule::in(array_keys(ConversationConstant::MESSAGE_TYPES))],
            'message.created_at' => 'required|date'
        ]);

        if (!$validator->fails()) {
            $this->counters[$message['type']]++;

            $this->emitTo(ConversationConstant::TAB_COMPONENTS[$message['type']], 'newMediaMessage', $message);
        }
    }
}
