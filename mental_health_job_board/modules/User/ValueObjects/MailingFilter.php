<?php

namespace Modules\User\ValueObjects;

use Illuminate\Support\Carbon;

class MailingFilter
{

    /** @var int[] */
    private array $roleIds;
    private ?string $status = null;
    private ?Carbon $registerFrom;

    public function __construct(array $roleIds, ?Carbon $registerFrom = null, ?string $status = null)
    {
        $this->roleIds = $roleIds;
        $this->status = $status;
        $this->registerFrom = $registerFrom;
    }

    /**
     * @return int[]
     */
    public function getRoleIds(): array
    {
        return $this->roleIds;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getRegisterFrom(): ?Carbon
    {
        return $this->registerFrom;
    }

}
