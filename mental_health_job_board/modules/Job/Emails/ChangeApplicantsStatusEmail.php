<?php

namespace Modules\Job\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChangeApplicantsStatusEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $content;

    public function __construct($body)
    {
        $this->content = $body;
    }

    public function build()
    {
        $subject = __('Application Status Memo');

        return $this->subject($subject)->view('Job::emails.change-applicants-status',['content' => $this->content]);
    }

}
