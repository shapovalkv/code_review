<?php

namespace Modules\User\Listeners;

use App\Events\NewMessageToChat;
use Illuminate\Support\Facades\Mail;
use App\User;
use Modules\User\Emails\MailChatMessageEmail;

class SendChatMessageToMailListener
{
    private const CODE = [
        'target_first_name' => '[target_first_name]',
        'target_last_name'  => '[target_last_name]',
        'sender_first_name' => '[sender_first_name]',
        'sender_last_name'  => '[sender_last_name]',
        'message'           => '[message]',
        'chatButton'        => '[chat_button]'
    ];

    public function handle(NewMessageToChat $event): void
    {
        $chat = $event->row;
        $participation = $event->participation;
        $senderUser = User::query()->find($participation->messageable_id);
        $targetUser = $chat->getOtherUser($senderUser->id);
        if (!empty($senderUser) && !empty($targetUser)) {
            $data = [
                'target_first_name' => $targetUser->first_name,
                'target_last_name'  => $targetUser->last_name,
                'sender_first_name' => $senderUser->first_name,
                'sender_last_name'  => $senderUser->last_name,
            ];

            if (!empty(setting_item('chat_message_content_email'))) {
                $body = $this->replaceContentEmail($data, setting_item_with_lang('chat_message_content_email', app()->getLocale()), $chat->id);
            } else {
                $body = $this->replaceContentEmail($data, $this->defaultBody($chat->id), $chat->id);
            }
            Mail::to($targetUser->email)->send(new MailChatMessageEmail($body, $senderUser));

            if (!empty($old)) {
                app()->setLocale($old);
            }
        }
    }

    public function defaultBody($chat_id): string
    {
        return '
            <h1>Hello [target_first_name] [target_last_name]!</h1>
            <p>[sender_first_name] [sender_last_name] sent you a message.</p>
            <p>Please login to your Dashboard to check your Messages inbox.</p>
            <p style="text-align: center">' . $this->chatButton($chat_id) . '</p>
            <p>Regards,<br>' . setting_item('site_title') . '</p>';
    }

    public function replaceContentEmail($data, $content, $chat_id): string
    {
        if (!empty($content)) {
            foreach (self::CODE as $item => $value) {
                if ($item == "chatButton") {
                    $content = str_replace($value, $this->chatButton($chat_id), $content);
                }

                $content = str_replace($value, @$data[$item], $content);
            }
        }

        return $content;
    }

    public function chatButton($chat_id): string
    {
        $link = route('user.chat.index', ['conversation' => $chat_id]);

        return '<a style="border-radius: 3px;
                color: #fff;
                display: inline-block;
                text-decoration: none;
                background-color: #3490dc;
                border-top: 10px solid #3490dc;
                border-right: 18px solid #3490dc;
                border-bottom: 10px solid #3490dc;
                border-left: 18px solid #3490dc;" href="' . $link . '">View message</a>';
    }
}
