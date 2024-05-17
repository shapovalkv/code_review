<?php

namespace Modules\Job\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JobExpiredEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $content;

    public function __construct($body)
    {
        $this->content = $body;
    }

    public function build()
    {
        $subject = __('Mental Healthcare Careers - Your job post has expired.');
        return $this->subject($subject)->view('Job::emails.jobExpire', ['content' => $this->content]);
    }
}
