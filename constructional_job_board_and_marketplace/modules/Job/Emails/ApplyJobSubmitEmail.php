<?php

namespace Modules\Job\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApplyJobSubmitEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $content;

    public function __construct($body)
    {
        $this->content = $body;
    }

    public function build()
    {
        $subject = __('constructional_job_board_and_marketplace.com - Candidate has applied to your job post');
        return $this->subject($subject)->view('Job::emails.apply-job',['content' => $this->content]);
    }

}
