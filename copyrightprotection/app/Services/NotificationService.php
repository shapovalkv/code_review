<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class NotificationService
{
    public function getUnreadNotifications($user, $paginateCount = 10)
    {
        return $user->unreadNotifications()
            ->when(Auth::user()->hasRole(User::ROLE_CUSTOMER), function ($query) {
                $query->whereJsonContains('data->project_id', Auth::user()->selected_project_id);
            })
            ->paginate($paginateCount, ['*'], 'unread_notifications');
    }

    public function getNotifications($user, $paginateCount = 10)
    {
        return $user->notifications()
            ->read()
            ->when(Auth::user()->hasRole(User::ROLE_CUSTOMER), function ($query) {
                $query->whereJsonContains('data->project_id', Auth::user()->selected_project_id);
            })
            ->paginate($paginateCount, ['*'], 'notifications');
    }
    public function markNotification($user, $data)
    {
        return $user
            ->unreadNotifications
            ->when($data->input('id'), function ($query) use ($data) {
                return $query->where('id', $data->input('id'));
            })
            ->markAsRead();
    }
}
