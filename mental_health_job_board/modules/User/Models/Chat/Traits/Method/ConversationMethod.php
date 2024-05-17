<?php

namespace Modules\User\Models\Chat\Traits\Method;

trait ConversationMethod
{
    public function getReportTargetName()
    {
        return $this->data['topic'] ?? null;
    }

    public function getReportUser(int $ignoreId)
    {
        return $this->getOtherUser($ignoreId);
    }

    public function getParticipationAvatar(int $forUserId)
    {
        $data = $this->data;
        $chatType = $data['chat_type'] ?? null;

        if (!empty($chatType)) {
            $user = $this->getOtherUser($forUserId);
            return is_null($this->avatar_id) ? asset('images/avatar.png') : $user->getAvatarUrl();
        } else {
            return null;
        }
    }

    public function getParticipationRole(int $forUserId)
    {
        $user = $this->getOtherUser($forUserId);

        return strtolower($user->getRoleNames()->first());
    }

    public function getParticipationPresentationForUser(int $forUserId)
    {
        $data = $this->data;
        $chatType = $data['chat_type'] ?? null;

        if (!empty($chatType)) {
            return $this->getParticipationPresentationOfUserForUser($forUserId);
        } else {
            return null;
        }
    }

    public function getParticipationPresentationOfUserForUser(int $forUserId, int $ofUserId = null)
    {
        $ofUserId = $ofUserId ?? optional($this->getOtherUser($forUserId))->id;

        return $this->data['participant_presentation'][$forUserId][$ofUserId] ?? null;
    }

    public function getOtherParticipant(int $ignoreId)
    {
        return $this->participants->first(fn($participant) => $participant->messageable_id !== $ignoreId);
    }

    public function getOtherUser(int $ignoreId)
    {
        return optional($this->getOtherParticipant($ignoreId))->messageable;
    }
}
