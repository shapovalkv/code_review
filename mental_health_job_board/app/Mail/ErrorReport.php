<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ErrorReport extends Mailable
{
    use Queueable, SerializesModels;
    protected $error;

    public function __construct($error)
    {
        $this->error = $error;
    }

    public function build()
    {
        return $this->subject('mhc Error Handler')->view('Core::emails.errorHandler')->with(['error' => $this->error, 'request' => request()]);
    }
}
