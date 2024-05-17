<?php
namespace Modules\Company\Events;

use Illuminate\Queue\SerializesModels;

class EmployerFilledCompanyProfile
{
    use SerializesModels;
    public $row;

    public function __construct($row)
    {
        $this->row = $row;
    }
}
