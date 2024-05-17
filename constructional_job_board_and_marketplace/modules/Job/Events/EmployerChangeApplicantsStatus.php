<?php
namespace Modules\Job\Events;

use Illuminate\Queue\SerializesModels;

class EmployerChangeApplicantsStatus
{
    use SerializesModels;
    public $row;
    public $message;

    public function __construct($row, $message)
    {
        $this->row = $row;
        $this->message = $message;
    }
}
