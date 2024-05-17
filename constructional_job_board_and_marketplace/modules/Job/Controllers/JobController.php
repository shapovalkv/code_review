<?php

namespace Modules\Job\Controllers;

use App\Http\Controllers\Controller;
use App\Resources\LocationResource;
use App\Services\BasicFilterService;
use App\Services\PopularSearchService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Modules\Candidate\Models\Candidate;
use Modules\Candidate\Models\CandidateCvs;
use Modules\Equipment\Models\Equipment;
use Modules\Job\Models\JobCategory as Category;
use Modules\Job\Events\CandidateApplyJobSubmit;
use Modules\Job\Models\Job;
use Modules\Job\Models\JobCandidate;
use Modules\Job\Models\JobType;
use Modules\Job\Resources\CategoryResource;
use Modules\Job\Resources\JobDetailResource;
use Modules\Job\Resources\JobListResource;
use Modules\Job\Resources\JobTypeResource;
use Modules\Location\Models\Location;
use Modules\Media\Models\MediaFile;
use Modules\Page\Models\Page;

class JobController extends Controller
{
    protected $jobClass;

    public function __construct()
    {
        $this->jobClass = Job::class;
    }

    public function index(Request $request, PopularSearchService $popularSearchService, BasicFilterService $basicFilterService)
    {
        $popularSearchService->store($request);

        $jobs = call_user_func([Job::class, 'search'], $request);

        $data = [
            'items' => JobListResource::collection($jobs),
            'filters' => [
                'category' => [
                    'items' => CategoryResource::collection(Category::where('status', 'publish')->withCount('openJobs')->get()->sortBy('name')->toTree()),
                    'values' => $request->category,
                ],
                'job_type' => [
                    'items' => JobTypeResource::collection(JobType::where('status', 'publish')->get()->sortBy('name')),
                    'values' => $request->job_type,
                ],
                'salary' => [
                    'items' => Job::getMinMaxPrice(),
                    'values' => [
                        'salary_from' => $request->salary_from,
                        'salary_to' => $request->salary_to,
                    ]
                ],
                'experience' => $basicFilterService->experience($request->experience),
                'seniority_level' => $basicFilterService->seniorityLevel($request->seniority_level),
                'keywords' => $basicFilterService->popularSearchKeywords($request->keywords, App::make($this->jobClass)->type),
                'location' => $basicFilterService->popularSearchLocation($request->location, App::make($this->jobClass)->type),
                'location_type' => $basicFilterService->searchLocationType($request->location_type),
                'location_state' => $basicFilterService->searchLocationState($request->location_state),
                'orderby' => $basicFilterService->orderby($request->orderby),
                'limit' => $basicFilterService->limit($request->limit),
            ],
            "seo_meta" => getSeoData($this->jobClass)
        ];

        return inertia('Job/Index', $data);
    }

    public function detail(Request $request, $slug)
    {
        $job = Job::with(['location', 'translations', 'category', 'company', 'company.teamSize', 'jobType', 'skills'])->where('slug', $slug)->first();

        views($job)->record();

        if (empty($job)) {
            abort('404');
        }

        $jobRelated = new Collection();
        $jobRelatedCount = 4;
        if (!empty($job->category_id)) {
            $jobRelated = Job::with(['location', 'translations', 'company', 'category', 'jobType'])
                ->where('category_id', $job->category_id)
                ->where("status", "publish")
                ->whereNotIn('id', [$job->id])
                ->take($jobRelatedCount)
                ->get();
        }

        if ($jobRelated->isEmpty() || $jobRelated->count() < 4) {
            $jobRelated = Job::with(['location', 'translations', 'company', 'category', 'jobType'])
                ->where("status", "publish")
                ->orderByDesc('created_at')
                ->whereNotIn('id', [$job->id])
                ->take($jobRelated->isEmpty() ? $jobRelatedCount : abs($jobRelatedCount - $jobRelated->count()))
                ->get();
        }

        $equipmentRelated = new Collection();
        $equipmentRelatedCount = 2;
        if (!empty($job->company_id)) {
            $equipmentRelated = Equipment::with(['company', 'location'])
                ->where('company_id', $job->company_id)
                ->where("status", "publish")
                ->take($equipmentRelatedCount)
                ->get();
        }

        if ($equipmentRelated->isEmpty() || $equipmentRelated->count() < 2) {
            $equipmentRelated = Equipment::with(['company', 'location'])
                ->where("status", "publish")
                ->orderByDesc('created_at')
                ->take($equipmentRelated->isEmpty() ? $equipmentRelatedCount : abs($equipmentRelatedCount - $equipmentRelated->count()))
                ->get();
        }

        $candidate = Auth::check() ? Candidate::with('cvs')->where('id', Auth::id())->first() : false;
        $applied = false;
        if ($candidate) {
            $jobCandidate = JobCandidate::query()
                ->where('job_id', $job->id)
                ->where('candidate_id', Auth::id())
                ->first();
            if ($jobCandidate) $applied = true;
        }

        $data = [
            'job' => new JobDetailResource($job),
            'job_related' => JobListResource::collection($jobRelated),
            'equipment_related' => $equipmentRelated->map(function ($equipment) {
                $equipment->company = $equipment->company ? $equipment->company->only(['id', 'name', 'avatar_url']) : null;
                $equipment->url = $equipment->getDetailUrl();
                $equipment->location = $equipment->location ? new LocationResource($equipment->location) : null;
                return $equipment->only(['id', 'slug', 'title', 'price', 'is_featured', 'image_url', 'company', 'url', 'location']);
            }),
            'candidate' => $candidate,
            'applied' => $applied,
            'seo_meta' => $job->getSeoMeta()
        ];

        $this->setActiveMenu($job);

        return inertia('Job/Detail', $data);
    }

    public function applyJob(Request $request)
    {
        $cvFile = $request->file('cvFile');
        $applyCvId = $request->input('apply_cv_id');
        $jobId = $request->input('job_id');
        $companyId = $request->input('company_id');

        //Save Cv
        if (!empty($cvFile)) {
            $fileId = MediaFile::saveUploadFile($cvFile, 'cvs');
            if (empty($fileId)) {
                return $this->sendError(__("An error occurred!"));
            }
            $candidateCv = new CandidateCvs();
            $candidateCv->file_id = $fileId;
            $candidateCv->origin_id = Auth::id();
            $candidateCv->save();
            $applyCvId = $candidateCv->id;
        }

        $row = JobCandidate::query()
            ->where('job_id', $jobId)
            ->where('candidate_id', Auth::id())
            ->first();
        if ($row) {
            return $this->sendError(__("You have applied this job already"));
        }
        $row = new JobCandidate();
        $row->job_id = $jobId;
        $row->candidate_id = Auth::id();
        $row->cv_id = $applyCvId ?? null;
        if (!empty($message = $request->input('message'))){
            $row->message = $message;
        }
        $row->status = 'pending';
        $row->company_id = $companyId;
        $row->save();
        $row->load('jobInfo', 'jobInfo.user', 'candidateInfo', 'company', 'company.getAuthor');
        //
        event(new CandidateApplyJobSubmit($row));

        if ($request->session()->has('job_id') && $request->session()->has('job_apply_refer')) {
            $request->session()->forget(['job_id', 'job_apply_refer']);
        }

        return $this->sendSuccess([
            'message' => __("Apply successfully!")
        ]);
    }

    public function categoryIndex(Request $request, $slug)
    {
        $cat = Category::where('slug', $slug)->first();

        if (empty($cat)) {
            return redirect(route('job.search'));
        }

        $translation = $cat->translateOrOrigin(app()->getLocale());

        $request->merge(['category', $cat->id]);
        $request->category = $cat->id;
        $list = call_user_func([Job::class, 'search'], $request);

        $markers = [];
        if (!empty($list)) {
            foreach ($list as $row) {
                if (!empty($row->map_lat) && !empty($row->map_lng)) {
                    $markers[] = [
                        "id" => $row->id,
                        "title" => $row->title,
                        "lat" => (float)$row->map_lat,
                        "lng" => (float)$row->map_lng,
                        "infobox" => view('Job::frontend.layouts.elements.map-infobox', ['row' => $row, 'disable_lazyload' => 1, 'wrap_class' => 'infobox-item'])->render(),
                        'customMarker' => view('Job::frontend.layouts.elements.map-marker', ['row' => $row, 'disable_lazyload' => 1])->render()
                    ];
                }
            }
        }

        $limitLocation = 1000;
        $data = [
            'rows' => $list,
            'list_locations' => Location::where('status', 'publish')->limit($limitLocation)->get()->toTree(),
            'list_categories' => Category::where('status', 'publish')->get()->toTree(),
            'category' => $cat,
            'job_types' => JobType::where('status', 'publish')->get(),
            'markers' => $markers,
            'min_max_price' => Job::getMinMaxPrice(),
            "filter" => $request->query('filter'),
            "seo_meta" => $cat->getSeoMetaWithTranslation(app()->getLocale(), $translation)
        ];
        $layout = 'job-list-v1';
        $data['style'] = $layout;

        return view('Job::frontend.index', $data);
    }

    public function locationIndex(Request $request, $slug)
    {
        $location = Location::query()->where('slug', $slug)->first();
        if (empty($location)) {
            return redirect(route('job.search'));
        }
        $translation = $location->translateOrOrigin(app()->getLocale());

        $request->merge(['location', $location->id]);
        $request->location = $location->id;
        $list = call_user_func([Job::class, 'search'], $request);

        $markers = [];
        if (!empty($list)) {
            foreach ($list as $row) {
                if (!empty($row->map_lat) && !empty($row->map_lng)) {
                    $markers[] = [
                        "id" => $row->id,
                        "title" => $row->title,
                        "lat" => (float)$row->map_lat,
                        "lng" => (float)$row->map_lng,
                        "infobox" => view('Job::frontend.layouts.elements.map-infobox', ['row' => $row, 'disable_lazyload' => 1, 'wrap_class' => 'infobox-item'])->render(),
                        'customMarker' => view('Job::frontend.layouts.elements.map-marker', ['row' => $row, 'disable_lazyload' => 1])->render()
                    ];
                }
            }
        }

        $limitLocation = 1000;
        $data = [
            'rows' => $list,
            'list_locations' => Location::where('status', 'publish')->limit($limitLocation)->get()->toTree(),
            'list_categories' => Category::where('status', 'publish')->get()->toTree(),
            'location' => $location,
            'markers' => $markers,
            'job_types' => JobType::where('status', 'publish')->get(),
            'min_max_price' => Job::getMinMaxPrice(),
            "filter" => $request->query('filter'),
            "seo_meta" => $location->getSeoMetaWithTranslation(app()->getLocale(), $translation)
        ];
        $layout = 'job-list-v1';
        $data['style'] = $layout;

        return view('Job::frontend.index', $data);
    }

    public function categoryLocationIndex(Request $request, $cat_slug, $location_slug)
    {
        $cat = Category::where('slug', $cat_slug)->first();
        $location = Location::query()->where('slug', $location_slug)->first();
        if (empty($cat) || empty($location)) {
            return redirect(route('job.search'));
        }
        $translation = $cat->translateOrOrigin(app()->getLocale());

        $request->merge(['category', $cat->id]);
        $request->category = $cat->id;
        $request->merge(['location', $location->id]);
        $request->location = $location->id;

        $list = call_user_func([Job::class, 'search'], $request);

        $markers = [];
        if (!empty($list)) {
            foreach ($list as $row) {
                if (!empty($row->map_lat) && !empty($row->map_lng)) {
                    $markers[] = [
                        "id" => $row->id,
                        "title" => $row->title,
                        "lat" => (float)$row->map_lat,
                        "lng" => (float)$row->map_lng,
                        "infobox" => view('Job::frontend.layouts.elements.map-infobox', ['row' => $row, 'disable_lazyload' => 1, 'wrap_class' => 'infobox-item'])->render(),
                        'customMarker' => view('Job::frontend.layouts.elements.map-marker', ['row' => $row, 'disable_lazyload' => 1])->render()
                    ];
                }
            }
        }

        $limitLocation = 1000;
        $data = [
            'rows' => $list,
            'list_locations' => Location::where('status', 'publish')->limit($limitLocation)->get()->toTree(),
            'list_categories' => Category::where('status', 'publish')->get()->toTree(),
            'category' => $cat,
            'location' => $location,
            'markers' => $markers,
            'job_types' => JobType::where('status', 'publish')->get(),
            'min_max_price' => Job::getMinMaxPrice(),
            "filter" => $request->query('filter'),
            "seo_meta" => $cat->getSeoMetaWithTranslation(app()->getLocale(), $translation)
        ];
        $layout = 'job-list-v1';
        $data['style'] = $layout;

        return view('Job::frontend.index', $data);
    }

    public function JobSearchCount(Request $request)
    {
        return call_user_func([Job::class, 'search'], $request);
    }
}
