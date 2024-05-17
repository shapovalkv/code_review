<?php

namespace App\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailChatMessageEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $content;
    protected $senderCompany;
    protected $senderUser;

    public function __construct($body, $senderCompany, $senderUser)
    {
        $this->content = $body;
        $this->senderCompany = $senderCompany;
        $this->senderUser = $senderUser;
    }

    public function build()
    {
        $subject = __('constructional_job_board_and_marketplace.com - New message from :sender', [ 'sender' => $this->senderCompany ? $this->senderCompany->name : $this->senderUser->firt_name." ".$this->senderUser->last_name]);
        return $this->subject($subject)->view('User::emails.chatMessage', ['content' => $this->content]);
    }
}
