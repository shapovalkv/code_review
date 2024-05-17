<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Recipient extends Model
{
    use Notifiable;

    protected string $recipient;
    protected string $email;

    public function __construct()
    {
        parent::__construct();
        $this->recipient = config('mail.from.name');
        $this->email = config('mail.from.address');
    }
}
