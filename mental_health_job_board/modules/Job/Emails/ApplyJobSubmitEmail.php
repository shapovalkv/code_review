<?php

namespace Modules\Job\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApplyJobSubmitEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $content;
    protected $cv_file;

    public function __construct($body, $cv_file)
    {
        $this->content = $body;
        $this->cv_file = $cv_file;
    }

    public function build()
    {

        $subject = __('A Candidate has applied for your Job Post');
        if(!empty($this->cv_file->file_path) && file_exists(public_path().'/uploads/'.$this->cv_file->file_path)){
            return $this->subject($subject)
                ->view('Job::emails.apply-job',['content' => $this->content])
                ->attach( public_path().'/uploads/'. $this->cv_file->file_path, [
                    'as' => $this->cv_file->file_name.'.'.$this->cv_file->file_extension,
                    'mime' => $this->cv_file->file_type,
                ]);
        }else{
            return $this->subject($subject)->view('Job::emails.apply-job',['content' => $this->content]);
        }

    }

}
