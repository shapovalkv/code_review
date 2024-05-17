<?php
namespace Modules\Contact\Events;

use Illuminate\Queue\SerializesModels;

class UserSentHelpMessageEvent
{
    use SerializesModels;
    public $row;

    public function __construct($row)
    {
        $this->row = $row;
    }
}
