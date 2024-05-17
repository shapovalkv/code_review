<?php

namespace Modules\Candidate\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChangeJobInviteStatusEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $content;

    public function __construct($body)
    {
        $this->content = $body;
    }

    public function build()
    {
        $subject = __('Candidate change applicants status');

        return $this->subject($subject)->view('Candidate::email.change-invite-job-status',['content' => $this->content]);
    }

}
