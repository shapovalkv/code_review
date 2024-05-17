<?php

namespace Modules\Equipment\Events;

use Illuminate\Queue\SerializesModels;

class AutomaticEquipmentExpiration
{
    use SerializesModels;

    public $row;

    public function __construct($row)
    {
        $this->row = $row;
    }
}
