<?php


namespace Modules\Job\Controllers;


use App\Services\BasicFilterService;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Candidate\Models\CandidateCvs;
use Modules\Core\Events\CreatedServicesEvent;
use Modules\Core\Events\UpdatedServiceEvent;
use Modules\FrontendController;
use Modules\Job\Events\EmployerChangeApplicantsStatus;
use Modules\Job\Events\EmployerDeletedApplicantApplies;
use Modules\Job\Exports\ApplicantsExport;
use Modules\Job\Exports\AppliedJobsExport;
use Modules\Job\Exports\JobsExport;
use Modules\Job\Models\Job;
use Modules\Job\Models\JobCandidate;
use Modules\Job\Models\JobCategory as Category;
use Modules\Job\Models\JobTranslation;
use Modules\Job\Models\JobType;
use Modules\Job\Requests\BulkJobRequest;
use Modules\Job\Requests\UpdateApplicantsRequest;
use Modules\Job\Requests\UpdateJobRequest;
use Modules\Job\Resources\CategoryResource;
use Modules\Job\Resources\JobApplicantsResource;
use Modules\Job\Resources\JobManageResource;
use Modules\Location\Models\Location;
use Modules\Location\Services\LocationService;
use Modules\Order\Helpers\CartManager;
use Modules\Skill\Models\Skill;
use Modules\User\Models\Plan;
use Modules\User\Models\PlanFeature;

class UserJobController extends FrontendController
{
    protected $jobClass;
    protected $candidateClass;


    public function __construct()
    {
        parent::__construct();
        $this->jobClass = Job::class;
        $this->candidateClass = JobCandidate::class;
    }

    public function manageJobs(Request $request, BasicFilterService $basicFilterService)
    {
        $this->checkPermission('job_manage');

        $companyId = Auth::user()->company->id ?? '';

        $jobs = call_user_func([Job::class, 'search'], $request, $companyId);

        $data = [
            'rows' => JobManageResource::collection($jobs),
            'pagination' => $basicFilterService->pagination($jobs),
            'filters' => [
                'category' => [
                    'items' => CategoryResource::collection(Category::where('status', 'publish')->withCount('openJobs')->get()->sortBy('name')->toTree()),
                    'values' => $request->category,
                ],
                'active' => $basicFilterService->active($request->active, $this->jobClass, $companyId),
                'sponsored' => $basicFilterService->sponsored($request->sponsored),
                'keywords' => $basicFilterService->popularSearchKeywords($request->keywords, App::make($this->jobClass)->type),
                'location' => $basicFilterService->popularSearchLocation($request->location, App::make($this->jobClass)->type),
                'orderby' => $basicFilterService->orderby($request->orderby),
                'limit' => $basicFilterService->limit($request->limit),
            ],
            'menu_active' => 'manage_jobs',
            "locale" => \App::getLocale(),
            'page_title' => __("Jobs Management")
        ];

        return view('Job::frontend.layouts.manage-job.manage-jobs', $data);
    }

    public function createJob(Request $request)
    {
        $this->checkPermission('job_manage');

        $row = new Job();
        $row->fill([
            'status' => 'publish',
        ]);
        $data = [
            'categories' => Category::get()->sortBy('name')->toTree(),
            'job_types' => JobType::query()->where('status', 'publish')->get(),
            'job_skills' => Skill::query()->where('status', 'publish')->where('skill_type', '=','job')->get(),
            'random_skills' => Skill::query()->where('status', 'publish')->where('skill_type', '=','job')->inRandomOrder()->limit(5)->get(),
            'job_location' => Location::where('status', 'publish')->get()->toTree(),
            'row' => $row,
            'menu_active' => 'new_job',
            'page_title' => __("Add Job"),
            'translation' => new JobTranslation(),
            'is_user_page' => true
        ];
        return view('Job::frontend.layouts.manage-job.edit-job', $data);
    }

    public function editJob(Request $request, $id)
    {
        $this->checkPermission('job_manage');

        $row = Job::with('skills')->find($id);

        $companyId = Auth::user()->company->id ?? '';

        if (empty($row)) {
            return redirect(route('user.manage.jobs'));
        } elseif ($companyId != $row->company_id) {
            return redirect(route('user.manage.jobs'));
        }

        $translation = $row->translateOrOrigin($request->query('lang'));

        $data = [
            'row' => $row,
            'translation' => $translation,
            'categories' => Category::query()->where('status', 'publish')->get()->sortBy('name')->toTree(),
            'job_location' => Location::query()->where('status', 'publish')->get()->toTree(),
            'job_types' => JobType::query()->where('status', 'publish')->get(),
            'job_skills' => Skill::query()->where('status', 'publish')->where('skill_type', 'job')->get(),
            'random_skills' => Skill::query()->where('status', 'publish')->where('skill_type', '=','job')->inRandomOrder()->limit(5)->get(),
            'enable_multi_lang' => true,
            'page_title' => __("Edit Job: ") . $translation->title,
            'menu_active' => 'manage_jobs',
            'is_user_page' => true
        ];
        return view('Job::frontend.layouts.manage-job.edit-job', $data);
    }

    public function storeJob(LocationService $locationService, Request $request, $id)
    {
        $this->checkPermission('job_manage');
        $user = Auth::user();

        if (!empty($request->input('salary_max')) && (int)$request->input('salary_max') > 0 && !empty($request->input('salary_min')) && (int)$request->input('salary_min') > 0) {

            $check = Validator::make($request->input(), [
                'title' => 'required|max:255',
                'content' => 'required',
                'salary_min' => 'required|numeric|min:1|max:999999',
                'salary_type' => 'required',
                'experience' => 'required',
                'salary_max' => 'gt:salary_min|numeric|max:1000000',
                'category_id' => 'required|max:255',
                'job_type_id' => 'required|max:255',
            ]);
            if (!$check->validated()) {
                return back()->withInput($request->input());
            }
        }

        if (is_default_lang()) {
            $request->validate([
                'map_location' => 'required',
            ], [
                'map_location.required' => __('Please select location from drop down or select place on the map'),
            ]);
        }

        if ($id > 0) {
            $row = Job::find($id);
            if (empty($row)) {
                return redirect(route('user.manage.jobs'));
            }
        } else {
            $row = new Job();
            $row->status = "draft";
            $row->is_featured = 0;
        }
        $input = $request->input();
        $attr = [
            'title',
            'content',
            'category_id',
            'thumbnail_id',
            'location_id',
            'company_id',
            'job_type_id',
            'expiration_date',
            'hours',
            'hours_type',
            'salary_min',
            'salary_max',
            'salary_type',
            'gender',
            'map_lat',
            'map_lng',
            'map_zoom',
            'experience',
            'seniority_level',
            'is_urgent',
            'create_user',
            'apply_type',
            'apply_link',
            'apply_email',
            'wage_agreement',
            'gallery',
            'video',
            'video_cover_id',
            'number_recruitments'
        ];
        $row->fillByAttr($attr, $input);
        $row->location_id = $locationService->store($request);
        $row->expiration_date = Carbon::now()->addDays(Job::BASIC_EXPIRATION_DAYS);

        if ($request->input('slug')) {
            $row->slug = $request->input('slug');
        }
        if (empty($request->input('create_user'))) {
            $row->create_user = $user->id;
        }

        if (!empty($user->company)) {
            $row->company_id = $user->company->id;
        }

        $res = $row->createSeoAndSave([
            'seo_title' => 'constructional_job_board_and_marketplace | ' . $user->company->name . ' | ' . $row->title,
            'seo_desc' => mb_strimwidth($row->content, 0, 130, '...'),
            'seo_keywords' => null //TODO Create auto generating seo keywords with SEO team requirements
        ]);

        $row->skills()->sync($request->input('job_skills') ?? []);

        if ($res) {
            if ($id > 0) {
                event(new UpdatedServiceEvent($row));
                return back()->with('success', __('Job updated'));
            } else {
                event(new CreatedServicesEvent($row));
                return redirect(route('user.choose.job.plan', ['job' => $row->id]))->with('success', __('Your Job post has been created'));
            }
        }
    }

    public function choosePlan(Job $job)
    {
        $this->checkPermission('job_manage');

        return view("User::frontend.plan.choose-job-plan", [
            'page' => [],
            'job' => $job,
            'user' => auth()->user(),
            'feature' => PlanFeature::JOB_SPONSORED
        ]);
    }

    public function storePlan(Request $request, Job $job)
    {
        $this->checkPermission('job_manage');
        $user = Auth::user();
        if ($job->create_user !== $user->id) {
            return abort(403);
        }
        $data = $request->validate([
            'action' => 'required'
        ]);
        if ($data['action'] === 'free') {
            $job->publish();
            return redirect()->route('user.all.jobs')->with('success', __('Your Job post has been posted'));
        } elseif ($data['action'] === 'promote') {
            CartManager::clear();
            CartManager::add(
                Plan::where(['plan_type' => Plan::TYPE_ONE_TIME, 'title' => Plan::PLAN_JOB_SPONSORED])->first(),
                '', 1, 0, ['action' => 'sponsored', 'model' => Job::class, 'model_id' => $job->id]
            );
            return redirect()->route('checkout', ['redirectTo' => route('user.all.jobs')]);
        } elseif ($data['action'] === 'current_plan' && $user->canUseFeature(PlanFeature::JOB_SPONSORED)) {
            $job->publish(['is_featured' => true]);
            $user->useFeature(PlanFeature::JOB_SPONSORED);
            return redirect()->route('user.all.jobs')->with('success', __('Your Job post has been posted'));
        }
        return back();
    }

    public function deleteJob(Request $request, $id)
    {
        $this->checkPermission('job_manage');

        $this->deleteSingleJob($id);

        return back()->with('success', __('Deleted success!'));
    }

    protected function deleteSingleJob($id)
    {
        $query = Job::where("id", $id);
        $user = Auth::user();
        if ($user->company_id) {
            $query = $query->where('company_id', $user->company_id);
        }
        $job = $query->firstOrFail();
        $job->delete();
        $user->useFeature(PlanFeature::JOB_CREATE, 1);
        if ($job->is_featured) {
            $user->useFeature(PlanFeature::JOB_SPONSORED, 1);
        }
    }


    public function update(Job $job, UpdateJobRequest $request)
    {
        $this->checkPermission('job_manage');

        $data = $request->validated();

        if ($job->company->owner_id !== Auth::user()->id) {
            return response()->json(['status' => 'error', 'message' => __('Only author can made changes!')]);
        }
        switch ($data['action']) {
            case 'update_status':
                $job->update(['status' => $data['status']]);
                if ($_SERVER['HTTP_REFERER'] == route('user.choose.job.plan', ['job' => $job->id])) {
                    return redirect(route('user.all.jobs'))->with('success', 'Your Job post has been posted');
                } else {
                    return response()->json(['status' => 'success', 'message' => __('Status changed!')]);
                }

            case 'sponsored':
                // todo Update sponsored functionality after subscription will be implemented
                $job->update(['is_featured' => true, 'expiration_date' => Carbon::now()->addDays(Job::BASIC_EXPIRATION_DAYS)]);
                if ($_SERVER['HTTP_REFERER'] == route('user.post.job.plans', ['job' => $job->id])) {
                    return redirect(route('user.all.jobs'))->with('success', 'Your Job post has been posted and sponsored!');
                } else {
                    return response()->json(['status' => 'success', 'message' => __('Status changed!')]);
                }

            case 'renew':
                $job->update([
                    'expiration_date' => Carbon::now()->addDays(Job::BASIC_EXPIRATION_DAYS),
                    'status' => "publish"
                ]);
                return response()->json(['status' => 'success', 'message' => __('Job renewed!')]);
            default:
                return response()->json(['status' => 'error', 'message' => __('Action name error!')]);

        }
    }

    public function bulk(BulkJobRequest $request)
    {
        $this->checkPermission('job_manage');
        $data = $request->validated();
        $companyId = Auth::user()->company->id ?? '';

        if ($data['model'] == BulkJobRequest::JOB) {
            switch ($request['action']) {
                case BulkJobRequest::DELETE:
                    foreach ($data['ids'] as $id) {
                        $this->deleteSingleJob($id);
                    }

                    break;
                case BulkJobRequest::DRAFT:
                case BulkJobRequest::PUBLISH:
                    Job::query()
                        ->where('company_id', '=', $companyId)
                        ->whereIn("id", $data['ids'])
                        ->update(['status' => $data['action']]);
            }
        } elseif ($data['model'] == BulkJobRequest::APPLICANTS) {
            $query = JobCandidate::with('jobInfo', 'jobInfo.user', 'candidateInfo', 'company', 'company.getAuthor')
                ->whereHas('jobInfo', function ($q) use ($companyId) {
                    $q->where('bc_job_candidates.company_id', $companyId);
                })
                ->whereIn('bc_job_candidates.id', $data['ids']);

            foreach ($data['ids'] as $id) {
                $query = $query->where('id', $id)->first();
                $oldStatus = $query->status;
                $query->update(['status' => $data['applicant_status']]);
                //Send Notify and Email
                if ($oldStatus != $data['applicant_status']) {
                    event(new EmployerChangeApplicantsStatus($query, null));
                }
            }
            exit();
        }
        return response()->json(['status' => 'success', 'message' => __('Update success!')]);
    }

    public function applicants(Request $request, BasicFilterService $basicFilterService)
    {
        $this->hasPermission('job_manage');

        $companyId = Auth::user()->company->id ?? '';

        $candidates = call_user_func([JobCandidate::class, 'search'], $request, $companyId);

        $data = [
            'rows' => JobApplicantsResource::collection($candidates),
            'pagination' => $basicFilterService->pagination($candidates),
            'filters' => [
                'category' => [
                    'items' => CategoryResource::collection(Category::where('status', 'publish')->withCount('openJobs')->get()->sortBy('name')->toTree()),
                    'values' => $request->category,
                ],
                'status' => $basicFilterService->applicantStatus($request->status),
                'keywords' => $basicFilterService->popularSearchKeywords($request->keywords),
                'orderby' => $basicFilterService->orderby($request->orderby),
                'limit' => $basicFilterService->limit($request->limit),
            ],
            'menu_active' => 'all_applicants',
            'page_title' => __("All Applications")
        ];

        return view('Job::frontend.layouts.manage-job.applicants', $data);

    }

    public function applicantsChangeStatus($id, UpdateApplicantsRequest $request)
    {
        $this->checkPermission('job_manage');

        $data = $request->validated();

        $row = JobCandidate::with('jobInfo', 'jobInfo.user', 'candidateInfo', 'company', 'company.getAuthor')
            ->where('bc_job_candidates.id', $id)
            ->whereHas('jobInfo', function ($q) {
                $companyId = Auth::user()->company->id ?? '';
                $q->where('bc_job_candidates.company_id', $companyId);
            });

        $row = $row->first();
        if (empty($row)) {
            return redirect()->back()->with('error', __('Item not found!'));
        }
        $oldStatus = $row->status;

        $row->status = $data['status'];
        if (!empty($data['message'])) {
            $row->message = $data['message'];
        }
        $row->save();
        //Send Notify and email
        if ($oldStatus != $data['status']) {
            event(new EmployerChangeApplicantsStatus($row, $data['message']));
        }
        if ($data['status'] == 'delete') {
            return redirect()->back()->with('success', __('Delete success!'));
        }
        return redirect()->back()->with('success', __('Update success!'));
    }

    public function applicantsExport()
    {
        $this->checkPermission('job_manage');

        return (new ApplicantsExport())->download('applicants-' . date('M-d-Y') . '.xlsx');
    }

    public function jobExport()
    {
        $this->checkPermission('job_manage');

        return (new JobsExport())->download('jobs-' . date('M-d-Y') . '.xlsx');
    }

    public function appliedJobExport()
    {
        $this->checkPermission('candidate_manage');

        return (new AppliedJobsExport())->download('jobs-' . date('M-d-Y') . '.xlsx');
    }

    public function applicantsCreate()
    {
        $this->checkPermission('job_manage');

        $row = new JobCandidate();
        $row->fill([
            'status' => 'pending',
        ]);
        $data = [
            'row' => $row,
            'page_title' => __("Create new applicant"),
            'menu_active' => 'all_applicants',
            'translation' => new JobTranslation()
        ];
        return view('Job::frontend.layouts.manage-job.applicant-create', $data);
    }

    public function applicantsStore(Request $request)
    {
        $user = Auth::user();
        $candidateId = $request->input('candidate_id');
        $status = $request->input('status');
        $applyCvId = $request->input('apply_cv_id');
        $message = $request->input('content');
        $jobId = $request->input('job_id');
        $companyId = ($user->company) ? $user->company->id : '';

        if (empty($candidateId)) {
            return redirect()->back()->with('error', __('Choose a candidate'));
        }
        if (empty($applyCvId)) {
            return redirect()->back()->with('error', __('Choose a cv'));
        }
        if (empty($jobId)) {
            return redirect()->back()->with('error', __('Choose a job'));
        }
        $row = JobCandidate::query()
            ->where('job_id', $jobId)
            ->where('candidate_id', $candidateId)
            ->first();
        if ($row) {
            return redirect()->back()->with('error', __('You have applied this job already'));
        }
        $row = new JobCandidate();
        $row->job_id = $jobId;
        $row->candidate_id = $candidateId;
        $row->cv_id = $applyCvId;
        $row->message = !empty($message) ? $message : '';
        $row->status = $status;
        $row->company_id = $companyId;
        $row->save();
        $row->load('jobInfo', 'jobInfo.user', 'candidateInfo', 'company', 'company.getAuthor');

        return redirect(route('user.applicants'))->with('success', __('Added successfully!'));
    }

    public function applicantsDelete(JobCandidate $jobCandidate)
    {
        $this->checkPermission('job_manage');

        if ($jobCandidate->company_id == Auth::user()->company->id){
            $jobCandidate->delete();
            event(new EmployerDeletedApplicantApplies($jobCandidate));
        }
    }

    public function applicantsGetCv(Request $request)
    {
        $id = $request->query('id');
        $cvs = CandidateCvs::query()->where('origin_id', $id)->with('media')->get();
        return $this->sendSuccess(['cv' => $cvs], 'success');
    }

}
