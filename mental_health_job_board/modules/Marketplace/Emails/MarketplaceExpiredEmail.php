<?php

namespace Modules\Marketplace\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MarketplaceExpiredEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $content;

    public function __construct($body)
    {
        $this->content = $body;
    }

    public function build()
    {
        $subject = __(':site_name - Your Announcement post has expired', ['site_name'=>setting_item('site_title')]);
        return $this->subject($subject)->view('Marketplace::emails.marketplaceExpire', ['content' => $this->content]);
    }
}
