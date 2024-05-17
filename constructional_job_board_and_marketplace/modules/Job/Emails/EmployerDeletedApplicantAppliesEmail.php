<?php

namespace Modules\Job\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmployerDeletedApplicantAppliesEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $content;

    public function __construct($body)
    {
        $this->content = $body;
    }

    public function build()
    {
        $subject = __(':site_name - Your application has been deleted', ['site_name'=>setting_item('site_title')]);

        return $this->subject($subject)->view('Job::emails.delete-applicants',['content' => $this->content]);
    }

}
