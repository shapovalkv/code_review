<?php

namespace Modules\Job\Controllers\Api;

use App\Enums\UserPermissionEnum;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Modules\Candidate\Models\Candidate;
use Modules\FrontendController;
use Modules\Job\Models\Job;
use Modules\User\Models\Role;

class JobController extends FrontendController
{
    public function updateJobAttribute(Job $job = null, Request $request): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();
        if (!$user) {
            abort(403);
        }
        $user->parent && $user = $user->parent;

        $this->checkPermission('job_manage', UserPermissionEnum::COMPANY_JOB_MANAGE);

        if ($job && $job->company && $job->company->owner_id !== $user->id) {
            abort(403);
        }

        $jobAttr = [
            'title',
            'job_type_id',
            'category_id',
            'location',
            'position_id',
            'salary_min',
            'salary_max',
            'salary_type',
            'skills_and_exp',
            'content',
            'hours',
            'hours_type',
            'experience',
            'experience_type',
            'map_lat',
            'map_lng',
            'map_zoom',
            'location_id',
            'employment_location',
            'is_urgent',
            'key_responsibilities'
        ];

        if ($job !== null && $job->id !== null) {
            $emLocs = json_decode($job->employment_location, true);
        } else {
            $emploc = Arr::get(Cache::get(auth()->id() . Job::CACHE_KEY_DRAFT, []), 'employment_location', []);
            $emLocs = !empty($emploc) ? json_decode($emploc, true) : [];
        }

        if ($request->input('employment_location')) {
            foreach ($request->input('employment_location') as $k => $value) {
                if ((int)$value === 1) {
                    $emLocs[$k] = $value;
                } else {
                    unset($emLocs[$k]);
                }
            }
        }

        if ($job !== null && $job->id !== null) {
            $job->fill($request->only($jobAttr));
            $job->setAttribute('employment_location', $emLocs ?: null);

            $result = $job->save();
        } else {
            $saved = array_replace(Cache::get(auth()->id() . Job::CACHE_KEY_DRAFT, []), $request->only($jobAttr));
            $saved['employment_location'] = !empty($emLocs) ? json_encode($emLocs) :  json_encode([]);
            $result = Cache::put(auth()->id() . Job::CACHE_KEY_DRAFT, $saved);
        }

        return response()->json(['success' => $result], $result ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
    }
}
