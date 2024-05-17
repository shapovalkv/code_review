<?php

namespace App\Enums;

class UserPermissionEnum
{
    public const COMPANY_FULL_ACCESS = 'company_full_access';
    public const COMPANY_STAFF_MANAGE = 'company_staff_manage';
    public const COMPANY_JOB_MANAGE = 'company_job_manage';
    public const COMPANY_ANNOUNCEMENT_MANAGE = 'company_announcement_manage';
    public const COMPANY_MESSAGING = 'company_messaging';

    public static function getName($constant): string
    {
        return match ($constant) {
            self::COMPANY_STAFF_MANAGE => 'Account User Manager',
            self::COMPANY_JOB_MANAGE => 'Post Jobs',
            self::COMPANY_ANNOUNCEMENT_MANAGE => 'Marketplace Manager',
            self::COMPANY_MESSAGING => 'Messaging',
            self::COMPANY_FULL_ACCESS => 'Admin (Full Access) - Same as you',
        };
    }

    public static function getConstants(): array
    {
        $reflector = new \ReflectionClass(self::class);

        return $reflector->getConstants();
    }
}
