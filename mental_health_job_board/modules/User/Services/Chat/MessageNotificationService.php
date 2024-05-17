<?php

namespace Modules\User\Services\Chat;

use Modules\User\Models\Chat\MessageNotification;
use App\User;

class MessageNotificationService
{
    public function __construct(MessageNotification $model)
    {
        $this->model = $model;
    }

    public function unreadMessagesByMessageable(int $messageableId, string $messageableType = User::class)
    {
        return $this->model
            ->select([
                'conversation_id',
                'count' =>
                    function ($query) use ($messageableId, $messageableType) {
                        $this
                            ->unreadMessagesQuery($query, $messageableId, $messageableType)
                            ->from('chat_message_notifications as notifications')
                            ->selectRaw('count(*) as count')
                            ->whereColumn('chat_message_notifications.conversation_id', 'notifications.conversation_id');
                    }
            ])
            ->pluck('count', 'conversation_id');
    }

    public function unreadMessagesQuery($query, int $messageableId, string $messageableType = User::class)
    {
        return $query
            ->where('is_seen', 0)
            ->where('messageable_id', $messageableId)
            ->where('messageable_type', $messageableType);
    }

    public function unreadMessagesByChatQuery($query, int $chatId, int $messageableId, string $messageableType = User::class)
    {
        return $this->unreadMessagesQuery($query, $messageableId, $messageableType)->where('conversation_id', $chatId);
    }

    public function unreadCount(int $messageableId, string $messageableType = User::class)
    {
        return $this->unreadMessagesQuery($this->model, $messageableId, $messageableType)->count();
    }

    public function unreadCountByChat(int $chatId, int $messageableId, string $messageableType = User::class)
    {
        return $this->unreadMessagesByChatQuery($this->model, $chatId, $messageableId, $messageableType)->count();
    }

    public function readAllByChat(int $chatId, int $messageableId, string $messageableType = User::class)
    {
        return $this->unreadMessagesByChatQuery($this->model, $chatId, $messageableId, $messageableType)->update(['is_seen' => 1]);
    }
}
