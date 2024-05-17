<?php

namespace Modules\Company\Controllers;

use App\Helpers\ReCaptchaEngine;
use App\Notifications\PrivateChannelServices;
use App\Services\BasicFilterService;
use App\Services\PopularSearchService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Matrix\Exception;
use Modules\Candidate\Emails\NotificationCandidateContact;
use Modules\Candidate\Models\CandidateContact;
use Modules\Company\Models\Company;
use Modules\Company\Models\CompanyCategory as Category;
use Modules\Company\Resources\CategoryResource;
use Modules\Company\Resources\CompanyDetailResource;
use Modules\Company\Resources\CompanyListResource;
use Modules\Company\Resources\JobListResource;
use Modules\Core\Models\Attributes;
use Modules\Core\Models\Notification;
use Modules\FrontendController;
use Modules\Job\Models\Job;
use Modules\Language\Models\Language;
use Modules\Location\Models\Location;
use Modules\User\Models\PopularSearch;
use Modules\User\Models\User;

class CompanyController extends FrontendController
{
    protected $company;
    protected $category;
    protected $language;
    protected $job;
    protected $attributes;
    protected $location;

    public function __construct()
    {
        parent::__construct();
        $this->company = Company::class;
        $this->category = Category::class;
        $this->language = Language::class;
        $this->job = Job::class;
        $this->attributes = Attributes::class;
        $this->location = Location::class;
        $this->popularSearch = PopularSearch::class;
    }

    public function index(Request $request, PopularSearchService $popularSearchService, BasicFilterService $basicFilterService)
    {

        $popularSearchService->store($request);

        $companies = call_user_func([$this->company, 'search'], $request);

        $data = [
            'items' => CompanyListResource::collection($companies),
            'filters' => [
                'category' => [
                    'items' => CategoryResource::collection($this->category::query()->where("status", "publish")->withCount('openCompanys')->get()->sortBy('name')->toTree()),
                    'values' => $request->category,
                ],
                'founded_date' => [
                    'items' => Company::getMinMaxFoundedDate(),
                    'values' => [
                        'from' => $request->salary_from,
                        'to' => $request->salary_to,
                    ]
                ],
                'keywords' => $basicFilterService->popularSearchKeywords($request->keywords, App::make($this->company)->type),
                'location' => $basicFilterService->popularSearchLocation($request->location, App::make($this->company)->type),
                'location_type' => $basicFilterService->searchLocationType($request->location_type),
                'location_state' => $basicFilterService->searchLocationState($request->location_state),
                'orderby' => $basicFilterService->orderby($request->orderby),
                'limit' => $basicFilterService->limit($request->limit),
            ],
            "seo_meta" => getSeoData($this->company)
        ];

        return inertia('Company/Index', $data);
    }

    public function detail(Request $request, $slug)
    {
        $company = $this->company::where('slug', $slug)->with(["category", "location", "companyTerm", "getAuthor", "skills"])
            ->withCount(['job' => function (Builder $query) {
                $query->where('status', 'publish');
            }])
            ->where('status', 'publish')->first();

        if (empty($company)) {
            return redirect('/');
        }

        get_user_view($company->owner_id);

        $companyJobs = $this->job::with(['location', 'translations', 'company', 'category', 'jobType'])
            ->where('company_id', $company->id)
            ->where("status", "publish")
            ->take(4)
            ->get();

        $data = [
            'company' => new CompanyDetailResource($company),
            'company_jobs' => JobListResource::collection($companyJobs),
            'seo_meta' => $company->getSeoMeta(),
        ];

        return inertia('Company/Detail', $data);
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
                    'to' => 'company',
                    'name' => $contact->name ?? '',
                    'avatar' => '',
                    'link' => route("company.admin.myContact"),
                    'type' => 'contact_form',
                    'message' => __(':name have sent a contact to you', ['name' => $contact->name ?? ''])
                ];

                $userNotify->notify(new PrivateChannelServices($data));
            } catch (Exception $exception) {
                Log::warning("Contact Company Send Mail: " . $exception->getMessage());
            }
        }
    }

    public function CompanySearchCount(Request $request)
    {
        return call_user_func([$this->company, 'search'], $request);
    }
}
