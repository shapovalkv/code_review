<?php
namespace Modules\Job\Events;

use Illuminate\Queue\SerializesModels;

class EmployerInviteCanditateToJob
{
    use SerializesModels;
    public $row;

    public function __construct($row)
    {
        $this->row = $row;
    }
}
