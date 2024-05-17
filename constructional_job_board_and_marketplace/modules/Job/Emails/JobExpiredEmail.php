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
        $subject = __('constructional_job_board_and_marketplace.com - Your job post has been expired.');
        return $this->subject($subject)->view('Job::emails.jobExpire', ['content' => $this->content]);
    }
}
