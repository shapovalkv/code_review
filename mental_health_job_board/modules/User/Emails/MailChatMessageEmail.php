<?php

namespace Modules\User\Emails;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailChatMessageEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected string $content;
    protected User $senderUser;

    public function __construct(string $body, User $senderUser)
    {
        $this->content = $body;
        $this->senderUser = $senderUser;
    }

    public function build()
    {
        $subject = __(config('app.name') . ' - New message from :sender', ['sender' => $this->senderUser->company ? $this->senderUser->company->name : $this->senderUser->firt_name . " " . $this->senderUser->last_name]);
        return $this->subject($subject)->view('User::emails.chatMessage', ['content' => $this->content]);
    }
}
