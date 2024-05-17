<?php

namespace Modules\Candidate\Controllers;

use App\Helpers\ReCaptchaEngine;
use App\Notifications\PrivateChannelServices;
use App\Services\BasicFilterService;
use App\Services\PopularSearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Collection;
use Matrix\Exception;
use Modules\Candidate\Models\CandidateContact;
use Modules\Candidate\Models\CandidateCvs;
use Modules\Candidate\Emails\NotificationCandidateContact;
use Modules\Candidate\Resources\CandidateDetailResource;
use Modules\Candidate\Resources\CandidateListResource;
use Modules\Candidate\Resources\CategoryResource;
use Modules\Candidate\Resources\JobTypeResource;
use Modules\Core\Models\Notification;
use Modules\FrontendController;
use Modules\Job\Models\JobCandidate;
use Modules\Job\Models\JobType;
use Modules\Candidate\Models\Candidate;
use Modules\Candidate\Models\Category;
use Modules\Location\Models\Location;
use Modules\Page\Models\Page;
use Modules\Skill\Models\Skill;
use Modules\User\Models\User;

class CandidateController extends FrontendController
{
    protected $candidateClass;

    public function __construct()
    {
        parent::__construct();
        $this->candidateClass = Candidate::class;
    }

    public function index(Request $request, PopularSearchService $popularSearchService, BasicFilterService $basicFilterService)
    {
        $popularSearchService->store($request);

        $candidates = call_user_func([Candidate::class, 'search'], $request);

        $data = [
            'items' => CandidateListResource::collection($candidates),
            'filters' => [
                'category' => [
                    'items' => CategoryResource::collection(Category::where('status', 'publish')->withCount('openCandidates')->get()->sortBy('name')->toTree()),
                    'values' => $request->category,
                ],
                'job_type' => [
                    'items' => JobTypeResource::collection(JobType::where('status', 'publish')->get()->sortBy('name')),
                    'values' => $request->job_type,
                ],
                'skill' => [
                    'items' => Skill::where('status', 'publish')->get()->map(function ($skills) {
                        return $skills->only(['id', 'name', 'slug']);
                    })->sortBy('name'),
                    'values' => $request->skill
                ],
                'experience' => $basicFilterService->experience($request->experience),
                'seniority_level' => $basicFilterService->seniorityLevel($request->seniority_level),
                'gender' => $basicFilterService->gender($request->gender),
                'salary' => [
                    'items' => Candidate::getMinMaxPrice(),
                    'values' => [
                        'salary_from' => $request->salary_from,
                        'salary_to' => $request->salary_to,
                    ]
                ],
                'keywords' => $basicFilterService->popularSearchKeywords($request->keywords, App::make($this->candidateClass)->type),
                'location' => $basicFilterService->popularSearchLocation($request->location, App::make($this->candidateClass)->type),
                'location_type' => $basicFilterService->searchLocationType($request->location_type),
                'location_state' => $basicFilterService->searchLocationState($request->location_state),
                'orderby' => $basicFilterService->orderby($request->orderby),
                'limit' => $basicFilterService->limit($request->limit),
            ],
            "seo_meta" => getSeoData($this->candidateClass)
        ];

        return inertia('Candidate/Index', $data);
    }

    public function detail(Request $request, $slug)
    {
        $candidate = Candidate::with(['skills', 'categories', 'user', 'location'])->where('slug', $slug)->first();
        if (empty($candidate)) {
            return redirect('/');
        } else {
            $apply_id = $request->get('apply_id');
            $job_candidate = JobCandidate::query()->where('candidate_id', $candidate->id)->find($apply_id);
            if (empty($job_candidate)) {
                if ($candidate->allow_search == 'hide' && $candidate->id != Auth::id()) {
                    return redirect('/');
                }
            }
        }

        get_user_view($candidate->create_user);

        $similarCandidates = new Collection();
        $similarCandidateCount = 4;
        if (!empty($candidate->category_id)) {
            $similarCandidates = Candidate::with(['skills', 'categories', 'user', 'location'])
                ->where('category_id', $candidate->category_id)
                ->whereNotIn('id', [$candidate->id])
                ->whereNotIn('allow_search', ['draft'])
                ->take($similarCandidateCount)
                ->get();
        }

        if ($similarCandidates->isEmpty() || $similarCandidates->count() < 4) {
            $similarCandidates = Candidate::with(['skills', 'categories', 'user', 'location'])
                ->orderByDesc('created_at')
                ->whereNotIn('id', [$candidate->id])
                ->whereNotIn('allow_search', ['draft'])
                ->take($similarCandidates->isEmpty() ? $similarCandidateCount : abs($similarCandidateCount - $similarCandidates->count()))
                ->get();
        }

        $data = [
            'candidate' => new CandidateDetailResource($candidate),
            'similar_candidates' => CandidateListResource::collection($similarCandidates),
            'cv' => CandidateCvs::query()->where('origin_id', $candidate->id)->where('is_default', 1)->first(),
            'seo_meta' => $candidate->getSeoMeta(),
        ];

        return inertia('Candidate/Detail', $data);
    }

    public function storeContact(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'max:255',
                'email'
            ],
            'name' => ['required'],
            'message' => ['required']
        ]);
        /**
         * Google ReCapcha
         */
        if (ReCaptchaEngine::isEnable()) {
            $codeCapcha = $request->input('g-recaptcha-response');
            if (!$codeCapcha or !ReCaptchaEngine::verify($codeCapcha)) {
                $data = [
                    'status' => 0,
                    'message' => __('Please verify the captcha'),
                ];
                return response()->json($data, 200);
            }
        }
        $row = new CandidateContact($request->input());
        $row->status = 'sent';
        if ($row->save()) {
            $this->sendEmail($row);
            $data = [
                'status' => 1,
                'message' => __('Thank you for contacting us! We will get back to you soon'),
            ];
            return response()->json($data, 200);
        }
    }

    protected function sendEmail($contact)
    {
        $userNotify = User::query()->where('id', $contact->origin_id)->first();
        if ($userNotify) {
            try {
                Mail::to($userNotify->email)->send(new NotificationCandidateContact($contact));

                $data = [
                    'message_type' => Notification::USERS_NOTIFICATION,
                    'id' => $contact->id,
                    'event' => 'ContactToCandidate',
                    'to' => 'candidate',
                    'name' => $contact->name ?? '',
                    'avatar' => '',
                    'link' => route("candidate.admin.myContact"),
                    'type' => 'apply_job',
                    'message' => __(':name have sent a contact to you', ['name' => $contact->name ?? ''])
                ];

                $userNotify->notify(new PrivateChannelServices($data));
            } catch (Exception $exception) {
                Log::warning("Contact Candidate Send Mail: " . $exception->getMessage());
            }
        }
    }

    public function CandidateSearchCount(Request $request)
    {
        return call_user_func([Candidate::class, 'search'], $request);
    }
}
