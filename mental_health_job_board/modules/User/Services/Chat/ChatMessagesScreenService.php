<?php

namespace Modules\User\Services\Chat;

use Modules\User\Constants\ConversationConstant;
use Modules\User\Models\Chat\Conversation;

class ChatMessagesScreenService

{
    public function loadMessages(Conversation $chat)
    {
        return $chat
            ->messages()
            ->select(ConversationConstant::MESSAGE_FIELDS)
            ->get()
            ->keyBy('id')
            ->makeHidden(['participation'])
            ->mapToGroups(function ($item) {
                if ($item['type'] === ConversationConstant::MESSAGE_TYPE_TEXT) {
                    return [ConversationConstant::SCREEN_MESSAGES => $item];
                }

                return [ConversationConstant::SCREEN_MEDIA => $item];
            });
    }

    public function getChatData($ChatClass)
    {
        return [
            'chatId' => $ChatClass->chatId,
            'chatTopic' => $ChatClass->chatTopic,
            'participationId' => $ChatClass->participationId,
            'participantName' => $ChatClass->participantName,
            'avatar' => $ChatClass->avatar,
            'profileLink' => $ChatClass->profileLink,
            'role' => $ChatClass->role,
        ];
    }

    public function deleteAllMessages(Conversation $chat)
    {
        return $chat
            ->messages()
            ->select(ConversationConstant::MESSAGE_FIELDS)
            ->delete();
    }
}
