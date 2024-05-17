<?php

namespace Modules\Equipment\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EquipmentExpiredEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $content;

    public function __construct($body)
    {
        $this->content = $body;
    }

    public function build()
    {
        $subject = __('constructional_job_board_and_marketplace.com - Your equipment post has been expired.');
        return $this->subject($subject)->view('Equipment::emails.equipmentExpire', ['content' => $this->content]);
    }
}
