<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class NewMessageToChat
{
    use SerializesModels;

    public $row;
    public $participation;

    public function __construct($row, $participation)
    {
        $this->row = $row;
        $this->participation = $participation;
    }
}
