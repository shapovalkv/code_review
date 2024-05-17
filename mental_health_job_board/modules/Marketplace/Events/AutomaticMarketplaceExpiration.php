<?php

namespace Modules\Marketplace\Events;

use Illuminate\Queue\SerializesModels;

class AutomaticMarketplaceExpiration
{
    use SerializesModels;

    public $row;

    public function __construct($row)
    {
        $this->row = $row;
    }
}
