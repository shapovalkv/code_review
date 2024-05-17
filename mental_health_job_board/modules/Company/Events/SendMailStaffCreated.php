<?php

namespace Modules\Company\Events;


use App\User;
use Illuminate\Queue\SerializesModels;

class SendMailStaffCreated
{
    use SerializesModels;
    public User $user;
    private string $password;

    public function __construct(User $user, string $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

}
