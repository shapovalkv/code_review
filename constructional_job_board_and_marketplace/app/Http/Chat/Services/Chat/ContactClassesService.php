<?php

namespace App\Http\Chat\Services\Chat;

use App\User;
use Modules\Candidate\Models\Candidate;
use Modules\Company\Models\Company;
use Modules\Equipment\Models\Equipment;
use Modules\Job\Models\Job;


class ContactClassesService

{
    const JOB = Job::class;
    const CANDIDATE = Candidate::class;
    const EQUIPMENT = Equipment::class;
    const COMPANY = Company::class;

    public function getTopicName($request, $user)
    {
        if ($request->has(['contactModel', 'contactId'])){
            switch ($modelName = $request->input('contactModel')){
                case "job":
                    $modelName = self::JOB;
                    break;
                case "equipment":
                    $modelName = self::EQUIPMENT;
                    break;
                case "company":
                    $modelName = self::COMPANY;
                    break;
            }
            $model= $modelName::find($request->input('contactId'));
            $topic = $model->name ?? $model->title;
        } else {
            $topic = 'Chat with: '.$user->name;
        }

        return $topic ?? null;
    }
}
