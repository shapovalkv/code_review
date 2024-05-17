<?php

namespace Modules\User\Services\Chat;

use App\User;
use Modules\Job\Models\Job;

class ContactClassesService
{
    public function getTopicName(User $user, ?Job $job = null): string
    {
        $topic = 'Common chat';

        if ($job !== null) {
            $topic = 'Job: ' . $job->title;
        }

        return $topic;
    }
}
