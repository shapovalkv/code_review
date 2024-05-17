<?php

namespace Modules\Marketplace\Events;

use Illuminate\Queue\SerializesModels;

class AutomaticMarketplace5daysExpiration
{
    use SerializesModels;

    public $row;

    public function __construct($row)
    {
        $this->row = $row;
    }
}
