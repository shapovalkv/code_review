<?php

namespace Modules\User\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserSponsoringAnnouncementEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $content;

    public function __construct($body)
    {
        $this->content = $body;
    }

    public function build()
    {
        $subject = __(':site_name - Your Announcement post is published', ['site_name'=>setting_item('site_title')]);
        return $this->subject($subject)->view('Marketplace::emails.userSponsoringAnnouncement', ['content' => $this->content]);
    }
}
