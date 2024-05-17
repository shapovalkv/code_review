<?php
namespace Modules\Candidate\Events;

use Illuminate\Queue\SerializesModels;

class CandidateChangeJobInviteStatus
{
    use SerializesModels;
    public $row;

    public function __construct($row)
    {
        $this->row = $row;
    }
}
