<?php

namespace Modules\Job\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InviteCandidateToJobEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $content;
    protected $cv_file;

    public function __construct($body)
    {
        $this->content = $body;
    }

    public function build()
    {

        $subject = __('Invitation to Apply for a Position');
        if(!empty($this->cv_file->file_path) && file_exists(public_path().'/uploads/'.$this->cv_file->file_path)){
            return $this->subject($subject)->view('Job::invite-candidate-job',['content' => $this->content]);
        }else{
            return $this->subject($subject)->view('Job::emails.invite-candidate-job',['content' => $this->content]);
        }

    }

}
