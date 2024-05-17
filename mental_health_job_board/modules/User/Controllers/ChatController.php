<?php

namespace Modules\User\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\User\Constants\ConversationConstant;
use Modules\User\Helpers\MessageHelper;
use Modules\User\Models\Chat\Conversation;
use Modules\User\Models\Chat\Message;
use Modules\User\Models\Role;
use Modules\User\Services\Chat\ContactClassesService;
use Modules\User\Services\Chat\ConversationService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Modules\Job\Models\Job;

class ChatController extends Controller
{
    public function contact(User $targetUser, ?Job $job, ConversationService $conversationService): View|RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        if (!isChatFeature($user)) {
            return redirect(route('user.chat.index'));
        }

        if (!\in_array($targetUser->role->id, [Role::CANDIDATE, Role::EMPLOYER, Role::MARKETPLACE, Role::EMPLOYEE], true)) {
            return redirect(route('user.chat.index'))->with('error', __('You can\'t start Messages. Wrong account type'));
        }

        if ($user->id === $targetUser->id) {
            return redirect(route('user.chat.index'))->with('error', __('You can\'t start Messages with yourself'));
        }

        $key = $targetUser->id + $user->id + $job?->id;
        $params = [
            'participants' => [$user, $targetUser],
            'search'       => ['key' => $key],
            'data'         => [
                'key'                      => $key,
                'chat_type'                => ConversationConstant::CHAT_TYPE_GENERAL,
                'participant_presentation' => [
                    $targetUser->id => [
                        $user->id => $user->name,
                    ],
                    $user->id       => [
                        $targetUser->id => $targetUser->name,
                    ],
                ],
            ],
        ];

        if ($job) {
            $params['data']['job']['id'] = $job->id;
        }

        if($user->parent) {
            $params['data']['parent']['id'] = $user->parent->id;
        }

        $conversation = $conversationService->findOrCreate($params);

        $user = auth()->user();

        $participants = [$user];

        if (is_employer($user) && $user->staff->count()) {
            $participants = $user->staff->merge([$user])->all();
        }

        return view('User::frontend.chat.index', [
            'activeChatId' => $conversation->id,
            'count'        => $conversationService
                ->getConversations(['participants' => $participants, 'private' => 1], ['id', 'data'])
                ->count()
        ]);
    }

    public function index(ConversationService $conversationService, ?int $id = null): View|RedirectResponse
    {
        if (null !== $id && null === Conversation::query()->find($id)) {
            return redirect()->route('user.chat.index');
        }

        $user = auth()->user();

        $participants = [$user];

        if (is_employer($user) && $user->staff->count()) {
            $participants = $user->staff->merge([$user])->all();
        }

        return view('User::frontend.chat.index', [
            'activeChatId' => $id,
            'count'        => $conversationService
                ->getConversations(['participants' => $participants, 'private' => 1], ['id', 'data'])
                ->count()
        ]);
    }

    public function download(Message $message, MessageHelper $messageHelper): BinaryFileResponse
    {
        $path = $messageHelper->getFileStoragePath($message->body);

        abort_if(!file_exists($path), 404);

        return response()->download(
            $path, $messageHelper->getOriginalName($message->body)
        );
    }
}
