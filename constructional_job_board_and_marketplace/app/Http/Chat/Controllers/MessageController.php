<?php

namespace App\Http\Chat\Controllers;

use App\Http\Chat\Constants\ConversationConstant;
use App\Http\Chat\Services\Chat\ContactClassesService;
use App\Http\Chat\Services\Chat\ConversationService;
use App\Http\Chat\Services\Chat\MessageNotificationService;
use App\Http\Controllers\Controller;
use App\Http\Chat\Helpers\MessageHelper;
use App\Http\Chat\Models\Chat\Conversation;
use App\Http\Chat\Models\Chat\Message;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Musonza\Chat\Models\Participation;

class MessageController extends Controller
{
    public function contact(User $targetUser, ConversationService $conversationService, ContactClassesService $contactClassesService, Request $request)
    {
        $user = auth()->user();

        if ($user->id === $targetUser->id) {
            return redirect(route('user.chat.index'))->with('error', __('You can\'t start chat with yourself'));
        }

        $key = $targetUser->id + $user->id;
        $params = [
            'participants' => [$user, $targetUser],
            'search' => ['key' => $key],
            'data' => [
                'key' => $key,
                'topic' => $contactClassesService->getTopicName($request, $targetUser),
                'chat_type' => ConversationConstant::CHAT_TYPE_GENERAL,
                'participant_presentation' => [
                    $targetUser->id => [
                        $user->id => $user->name,
                    ],
                    $user->id => [
                        $targetUser->id => $targetUser->name,
                    ],
                ],
            ],
        ];

        $conversation = $conversationService->findOrCreate($params);
        return view('message.index', [
            'activeChatId' => $conversation->id
        ]);
    }

    public function index(Conversation $conversation)
    {
        return view('message.index', ['activeChatId' => $conversation->id]);
    }

    public function download(Message $message, MessageHelper $messageHelper)
    {
        $path = $messageHelper->getFileStoragePath($message->body);

        abort_if(!file_exists($path), 404);

        return response()->download(
            $path, $messageHelper->getOriginalName($message->body)
        );
    }
}
