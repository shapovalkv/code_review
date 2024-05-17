<?php


namespace Modules\Job\Controllers;


use App\Enums\UserPermissionEnum;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Modules\Candidate\Models\CandidateCvs;
use Modules\Core\Models\SEO;
use Modules\FrontendController;
use Modules\Job\Events\EmployerChangeApplicantsStatus;
use Modules\Job\Events\EmployerInviteCanditateToJob;
use Modules\Job\Exports\ApplicantsExport;
use Modules\Job\Models\Job;
use Modules\Job\Models\JobCandidate;
use Modules\Job\Models\JobCategory as Category;
use Modules\Job\Models\JobPosition;
use Modules\Job\Models\JobTranslation;
use Modules\Job\Models\JobType;
use Modules\Job\Services\JobService;
use Modules\Language\Models\Language;
use Modules\Location\Models\Location;
use Modules\Skill\Models\Skill;
use Modules\User\Services\Chat\ConversationService;

class ManageJobController extends FrontendController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function manageJobs(Request $request)
    {
        $this->checkPermission('job_manage', UserPermissionEnum::COMPANY_JOB_MANAGE);

        /** @var User $user */
        $user = auth()->user();
        $user->parent && $user = $user->parent;

        $job_query = Job::query()->with(['location', 'category', 'company'])->orderBy('id', 'desc');
        $title = $request->query('s');

        if ($title) {
            $job_query->where('title', 'LIKE', '%' . $title . '%');
            $job_query->orderBy('title', 'asc');
        }

        $company_id = $user->company->id ?? '';
        $job_query->where('company_id', $company_id);


        $data = [
            'rows' => $job_query->paginate(20),
            'menu_active' => 'manage_jobs',
            "languages" => Language::getActive(false),
            "locale" => \App::getLocale(),
            'page_title' => __("Jobs Management")
        ];
        return view('Job::frontend.layouts.manage-job.manage-jobs', $data);
    }

    public function createJob(Request $request, JobService $jobService)
    {
        $this->checkPermission('job_manage', UserPermissionEnum::COMPANY_JOB_MANAGE);

        if ($request->get('cache') === 'clear') {
            Cache::forget(auth()->id() . Job::CACHE_KEY_DRAFT);

            return redirect()->route('user.create.job');
        }

        /** @var User $user */
        $user = auth()->user();
        $subUser = null;
        if($user->parent) {
            $subUser = $user;
            $user = $user->parent;
        }
        if (!$user->checkCompanyInfo()) {
            if (!$subUser) {
                return redirect(route('user.company.profile'))->with('error', __('Need to complete Company Profile before posting a job'));
            } else {
                return redirect(route('user.dashboard'))->with('error', __('Need to complete Company Profile before posting a job'));
            }
        }

        if (!$user->currentUserPlan) {
            if ($subUser) {
                return redirect(route('user.dashboard'))->with('error', __('Need to select Pricing Plan before posting a job'));
            } else {
                return redirect(route('subscription'))->with('error', __('Need to select Pricing Plan before posting a job'));
            }
        }

        $row = new Job();
        $row->fill([
            'status' => 'publish',
        ]);

        $data = [
            'categories' => Category::orderBy('name')->get()->toTree(),
            'job_types' => JobType::query()->where('status', 'publish')->get(),
            'job_skills' => Skill::query()->where('status', 'publish')->get(),
            'job_positions' => JobPosition::query()->where('status', 'publish')->get(),
            'job_location' => Location::where('status', 'publish')->inRandomOrder()->limit(10)->get()->toTree(),
            'row' => $jobService->fillByAttrForCreateJob($row),
            'menu_active' => 'new_job',
            'page_title' => __("Add Job"),
            'translation' => new JobTranslation(),
            'is_user_page' => true
        ];

        return view('Job::frontend.layouts.manage-job.edit-job', $data);
    }

    public function editJob(Request $request, $id)
    {
        $this->checkPermission('job_manage', UserPermissionEnum::COMPANY_JOB_MANAGE);
        /** @var User $user */
        $user = auth()->user();
        $user->parent && $user = $user->parent;

        $row = Job::with('skills')->find($id);
        $company_id = $user->company->id ?? '';

        if (empty($row)) {
            return redirect(route('user.manage.jobs'));
        } elseif ($company_id != $row->company_id) {
            return redirect(route('user.manage.jobs'));
        }

        $translation = $row->translateOrOrigin($request->query('lang'));

        $data = [
            'row' => $row,
            'translation' => $translation,
            'categories' => Category::query()->where('status', 'publish')->orderBy('name')->get()->toTree(),
            'job_positions' => JobPosition::query()->where('status', 'publish')->get(),
            'job_location' => Location::query()->where('status', 'publish')->inRandomOrder()->limit(10)->get()->toTree(),
            'job_types' => JobType::query()->where('status', 'publish')->get(),
            'job_skills' => Skill::query()->where('status', 'publish')->get(),
            'enable_multi_lang' => true,
            'page_title' => __("Edit Job: ") . $translation->title,
            'menu_active' => 'manage_jobs',
            'is_user_page' => true
        ];
        return view('Job::frontend.layouts.manage-job.edit-job', $data);
    }

    public function storeJob(Request $request, $id)
    {
        $this->checkPermission('job_manage', UserPermissionEnum::COMPANY_JOB_MANAGE);
        /** @var User $authUser */
        $authUser = auth()->user();
        $authUser->parent && $authUser = $authUser->parent;

        if ($id){
            if(!$authUser->currentUserPlan()->exists()){
                $msg = 'Your has plan expired. Please select same or select new one.';
                return redirect('subscription')->with('error',__($msg));
            }
        }

        $check = Validator::make($request->input(), [
            'title' => 'required',
            'job_type_id' => 'required',
            'category_id' => 'required',
            'location' => 'required',
            'position_id' => 'required',
//            'expiration_date' => 'required|date_format:Y/m/d',
            'salary_min' => 'lte:salary_max',
            'salary_max' => 'gte:salary_min',
        ]);
        if (!$check->validated()) {
            return back()->withInput($request->input());
        }
//        if (!empty($request->input('salary_max')) && (int)$request->input('salary_max') > 0 && !empty($request->input('salary_min')) && (int)$request->input('salary_min') > 0) {
//            $check = Validator::make($request->input(), [
//                'salary_max' => 'required|gt:salary_min'
//            ]);
//            if (!$check->validated()) {
//                return back()->withInput($request->input());
//            }
//        }

        if ($id > 0) {
            $row = Job::find($id);
            if (empty($row)) {
                return redirect(route('user.manage.jobs'));
            }
        } else {
            $row = new Job();
            $row->status = "publish";
        }

        $input = $request->input();
        $attr = [
            'title',
            'content',
            'key_responsibilities',
            'skills_and_exp',
            'category_id',
            'thumbnail_id',
            'location_id',
            'company_id',
            'job_type_id',
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
            'experience_type',
            'is_urgent',
            'status',
            'create_user',
            'apply_type',
            'apply_link',
            'apply_email',
            'wage_agreement',
            'gallery',
            'video',
            'video_cover_id',
            'number_recruitments',
            'position_id',
        ];
        if (!empty($input['wage_agreement'])) {
            $input['salary_min'] = 0;
            $input['salary_max'] = 0;
        }
        if ($row->is_approved != Job::APPROVED and setting_item('job_need_approve')) {
            $attr[] = 'is_approved';
        }
        $row->fillByAttr($attr, $input);
        $row->employment_location = json_encode($request->input('employment_location'));

        if ($request->input('slug')) {
            $row->slug = $request->input('slug');
        }
        if (empty($request->input('create_user'))) {
            $row->create_user = Auth::id();
        }
        if (empty($request->input('is_featured'))) {
            $row->is_featured = false;
        }

        if (!empty($request->input('location'))) {
            $row->location_id = $request->input('location');
        }

        if (!empty($authUser->company)) {
            $row->company_id = $authUser->company->id;
        }

        $row->saveOriginOrTranslation($request->query('lang'),true);
        Cache::forget(auth()->id() . Job::CACHE_KEY_DRAFT);

        if ($id < 0){
            $row->expiration_date = Carbon::now()->addDays($authUser->currentUserPlan->plan->expiration_job_time);
        }else{
            if (Carbon::createFromFormat('Y-m-d H:i:s', $row->expiration_date)->timestamp <  Carbon::now()->timestamp){
                $row->status = 'draft';
                $row->saveOriginOrTranslation($request->query('lang'),true);
                $msg = 'Your job has expired. Please renew it from Job manager or<a href="'. route('user.renew.job', ['job' => $row->id]) . '"> Click here </a>  to renew it.';
                return redirect(route('user.edit.job', ['id' => $row->id]))->with('error',__($msg));
            }
        }
        // Check Plan
        if ($row->status === Job::PUBLISH && empty($request->input('is_featured')) && !$authUser->checkJobPlan(!is_null($id) && $id > 0) && !is_admin()) {
            $row->status = 'draft';
            $row->saveOriginOrTranslation($request->query('lang'),true);
            $msg = 'Maximum published items reached. <a href="'. route('subscription') . '"> Click here  </a>  to upgrade your user plan.';
            if ($authUser->checkFeaturedJobPlan()){
                $msg .= ' You also can post Popular jobs.';
            }
            return redirect(route('user.edit.job', ['id' => $row->id]))->with('error',__($msg));
        } elseif ($row->status === Job::PUBLISH && $request->input('is_featured') == 1 && !$authUser->checkFeaturedJobPlan() && !is_admin()) {
            $row->status = 'draft';
            $row->is_featured = 0;
            $row->saveOriginOrTranslation($request->query('lang'),true);
            $msg = 'Maximum popular Jobs published items reached. <a href="'. route('subscription') . '"> Click here  </a>  to upgrade your user plan.';
            if ($authUser->checkJobPlan()){
                $msg .= ' In Your current plan for posting left only job without Popular tag';
            }
            return redirect(route('user.edit.job', ['id' => $row->id]))->with('error',__($msg));
        }

        if ($request->input('is_featured') == 1 && $authUser->checkFeaturedJobPlan()){
            $row->is_featured = 1;
        }elseif (empty($request->input('is_featured')) && $authUser->checkFeaturedJobPlan()){
            $row->is_featured = 0;
        }

        $res = $row->saveOriginOrTranslation($request->query('lang'),true);
        $row->skills()->sync($request->input('job_skills') ?? []);

        if ($res) {
            $meta = SEO::where('object_id', $row->id)->where('object_model', 'job')->first();
            if ($meta) {
                $meta->seo_title = $row->title . '. Location: ' . $row->location->name . '. Company: ' . $row->company->name;
                $meta->save();
            }

            if($id > 0 ){
                return back()->with('success',  __('Job updated') );
            }else{
                return redirect(route('user.edit.job', ['id' => $row->id]))->with('success', __('Job created') );
            }
        }
    }

    public function deleteJob(Request $request, $id)
    {
        $this->checkPermission('job_manage');

        /** @var User $authUser */
        $authUser = auth()->user();
        $authUser->parent && $authUser = $authUser->parent;

        $query = Job::where("id", $id);

        $company_id = $authUser->company->id ?? '';
        $query->where('company_id', $company_id);

        $query->first();
        if (!empty($query)) {
            $query->delete();
        }

        return redirect()->back()->with('success', __('Deleted success!'));
    }

    public function update(Job $job)
    {
        $this->checkPermission('job_manage', UserPermissionEnum::COMPANY_JOB_MANAGE);
        /** @var User $authUser */
        $authUser = auth()->user();
        $authUser->parent && $authUser = $authUser->parent;

        if (!$authUser->currentUserPlan) {
            return redirect(route('subscription'))->with('error', __('Need to select Pricing Plan before updating a job'));
        }
        if(!$authUser->currentUserPlan()->exists()){
            $msg = 'Your has plan expired. Please select same or select new one.';
            return redirect('subscription')->with('error',__($msg));
        }
        if ($job->company->owner_id !== $authUser->id) {
            return redirect()->back()->with('success', __('Only author can made changes!'));
        }

        // Check Plan
        if ($job->is_featured == 0 && !$authUser->checkJobPlan(true) && !is_admin()) {
            $job->status = 'draft';
            $msg = 'Maximum published items reached. <a href="' . route('subscription') . '"> Click here  </a>  to upgrade your user plan.';
            return redirect(route('user.edit.job', ['id' => $job->id]))->with('error', __($msg));
        } elseif ($job->is_featured == 1 && !$authUser->checkFeaturedJobPlan() && !is_admin()) {
            $job->status = 'draft';
            $msg = 'Maximum featured published items reached. <a href="' . route('subscription') . '"> Click here  </a>  to upgrade your user plan.';
            return redirect(route('user.edit.job', ['id' => $job->id]))->with('error', __($msg));
        }

        $job->update([
            'expiration_date' => Carbon::now()->addDays($authUser->currentUserPlan->plan->expiration_job_time),
            'status' => "publish"
        ]);
        return redirect()->back()->with('success', __('Job renewed!'));
    }


    public function applicants(Request $request, ConversationService $conversationService)
    {
        $this->checkPermission('job_manage', UserPermissionEnum::COMPANY_JOB_MANAGE);
        /** @var User $user */
        $user = auth()->user();
        $user->parent && $user = $user->parent;

        $rows = JobCandidate::with(['jobInfo', 'candidateInfo', 'cvInfo', 'company', 'company.getAuthor'])
            ->whereHas('jobInfo', function ($q) use ($request, $user) {
                $job_id = $request->query('job_id');
                $company_id = $user->company->id ?? '';
                $q->where('company_id', $company_id);
                if ($job_id) {
                    $q->where("id", $job_id);
                }
            });

        $rows->where('status', '!=', 'delete')
            ->with(JobCandidate::RELATION_USER);
        $rows = $rows->orderBy('id', 'desc')
            ->paginate(20)
            ->through(function ($row) use ($user, $conversationService) {
                if ($row->user) {
                    $row->conversationId = $conversationService->findConversation(
                        [$user, $row->user],
                        ['key' => auth()->id() + $row->candidate_id + $row->job_id]
                    )?->id;
                } else {
                    $row->conversationId = null;
                }

                return $row;
            });

        $data = [
            'rows' => $rows,
            'menu_active' => 'all_applicants',
            'page_title' => __("All Applications"),
        ];
        return view('Job::frontend.layouts.manage-job.applicants', $data);

    }

    public
    function applicantsChangeStatus($status, $id)
    {
        $this->checkPermission('job_manage', UserPermissionEnum::COMPANY_JOB_MANAGE);

        $user = auth()->user();
        $user->parent && $user = $user->parent;

        $row = JobCandidate::with('jobInfo', 'jobInfo.user', 'candidateInfo', 'company', 'company.getAuthor')
            ->where('id', $id)
            ->whereHas('jobInfo', function ($q) use ($user) {
                $q->where('company_id', $user->company->id);
            });

        $row = $row->first();
        if (empty($row)) {
            return redirect()->back()->with('error', __('Item not found!'));
        }
        $old_status = $row->status;
        if ($status != 'approved' && $status != 'rejected' && $status != 'delete') {
            return redirect()->back()->with('error', __('Status unavailable'));
        }
        $row->status = $status;
        $row->save();
        //Send Notify and email
        if ($old_status != $status) {
            event(new EmployerChangeApplicantsStatus($row));
        }
        if ($status == 'delete') {
            return redirect()->back()->with('success', __('Delete success!'));
        }
        return redirect()->back()->with('success', __('Update success!'));
    }

    public
    function applicantsExport()
    {

        $this->checkPermission('job_manage', UserPermissionEnum::COMPANY_JOB_MANAGE);

        return (new ApplicantsExport())->download('applicants-' . date('M-d-Y') . '.xlsx');
    }

    public
    function applicantsCreate()
    {
        return redirect()->back();
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

    public
    function applicantsStore(Request $request)
    {
        $user = Auth::user();
        $candidate_id = $request->input('candidate_id');
        $status = $request->input('status');
        $apply_cv_id = $request->input('apply_cv_id');
        $message = $request->input('content');
        $job_id = $request->input('job_id');
        $company_id = ($user->company) ? $user->company->id : '';

        if (empty($candidate_id)) {
            return redirect()->back()->with('error', __('Choose a candidate'));
        }
//        if(empty($apply_cv_id)){
//            return redirect()->back()->with('error', __('Choose a cv'));
//        }
        if (empty($job_id)) {
            return redirect()->back()->with('error', __('Choose a job'));
        }
        $row = JobCandidate::query()
            ->where('job_id', $job_id)
            ->where('candidate_id', $candidate_id)
            ->first();
        if ($row) {
            return redirect()->back()->with('error', __('You has applied this job already'));
        }
        $row = new JobCandidate();
        $row->job_id = $job_id;
        $row->candidate_id = $candidate_id;
        $row->cv_id = $apply_cv_id;
        $row->message = !empty($message) ? $message : '';
        $row->status = $status;
        $row->company_id = $company_id;
        $row->initiator_id = $user->id;
        $row->save();
        $row->load('jobInfo', 'jobInfo.user', 'candidateInfo', 'company', 'company.getAuthor');

        event(new EmployerInviteCanditateToJob($row));

        return redirect(route('user.applicants'))->with('success', __('Added successfully!'));
    }

    public
    function applicantsGetCv(Request $request)
    {
        $id = $request->query('id');
        $cvs = CandidateCvs::query()->where('origin_id', $id)->with('media')->get();
        return $this->sendSuccess(['cv' => $cvs], 'success');
    }

}
