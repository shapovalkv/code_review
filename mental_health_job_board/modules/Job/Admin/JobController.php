<?php
namespace Modules\Job\Admin;

use App\Notifications\PrivateChannelServices;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\AdminController;
use Modules\Candidate\Models\CandidateCvs;
use Modules\Core\Models\SEO;
use Modules\Job\Events\CandidateApplyJobSubmit;
use Modules\Job\Exports\JobExport;
use Modules\Job\Models\JobCategory as Category;
use Modules\Job\Events\EmployerChangeApplicantsStatus;
use Modules\Job\Exports\ApplicantsExport;
use Modules\Job\Models\Job;
use Modules\Job\Models\JobCandidate;
use Modules\Job\Models\JobPosition;
use Modules\Job\Models\JobTranslation;
use Modules\Job\Models\JobType;
use Modules\Job\Requests\GetJobListRequest;
use Modules\Job\Services\JobService;
use Modules\Language\Models\Language;
use Modules\Location\Models\Location;
use Modules\Media\Models\MediaFile;
use Modules\Skill\Models\Skill;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class JobController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->setActiveMenu('admin/module/job');
        if(!is_admin()){
            $this->middleware('verified');
        }
    }

    public function index(GetJobListRequest $request, JobService $jobService): View
    {
        $this->checkPermission('job_manage');
        $this->isAdmin();

        $data = [
            'rows'        => $jobService->getJobBuilder(
                $request->query('s'),
                $request->query('category_id'),
                $request->query('company_id'),
                $request->query('orderBy'),
                $request->query('orderDirection')
            )->paginate(20),
            'breadcrumbs' => [
                [
                    'name' => __('Job'),
                    'url'  => 'admin/module/job'
                ],
                [
                    'name'  => __('All'),
                    'class' => 'active'
                ],
            ],
            'page_title'  => __("Jobs Management")
        ];

        return view('Job::admin.job.index', $data);
    }

    public function create(Request $request)
    {
        $this->checkPermission('job_manage');

        $row = new Job();
        $row->fill([
            'status' => 'publish',
        ]);
        $data = [
            'categories'        => Category::get()->toTree(),
            'job_types' => JobType::query()->where('status', 'publish')->get(),
            'job_skills' => Skill::query()->where('status', 'publish')->get(),
            'job_location' => Location::where('status', 'publish')->inRandomOrder()->limit(10)->get()->toTree(),
            'job_positions' => JobPosition::query()->where('status', 'publish')->get(),
            'row'         => $row,
            'breadcrumbs' => [
                [
                    'name' => __('Job'),
                    'url'  => 'admin/module/job'
                ],
                [
                    'name'  => __('Add Job'),
                    'class' => 'active'
                ],
            ],
            'translation' => new JobTranslation(),
            'page_title'=>__("Create new job")
        ];
        return view('Job::admin.job.detail', $data);
    }

    public function edit(Request $request, $id)
    {
        $this->checkPermission('job_manage');

        $row = Job::with('skills')->find($id);

        $translation = $row->translateOrOrigin($request->query('lang'));
        $company_id = Auth::user()->company->id ?? '';

        if (empty($row)) {
            return redirect(route('job.admin.index'));
        }elseif(!is_admin() && $company_id != $row->company_id){
            return redirect(route('job.admin.index'));
        }

        $data = [
            'row'  => $row,
            'translation'  => $translation,
            'categories' => Category::query()->where('status', 'publish')->get()->toTree(),
            'job_positions' => JobPosition::query()->where('status', 'publish')->get(),
            'job_location' => Location::where('status', 'publish')->inRandomOrder()->limit(10)->get()->toTree(),
            'job_types' => JobType::query()->where('status', 'publish')->get(),
            'job_skills' => Skill::query()->where('status', 'publish')->get(),
            'enable_multi_lang'=>true,
            'breadcrumbs' => [
                [
                    'name' => __('Job'),
                    'url'  => 'admin/module/job'
                ],
                [
                    'name'  => $row->title,
                    'class' => 'active'
                ],
            ],
            'page_title'=>__("Edit job: :name",['name'=>$row->title])
        ];
        return view('Job::admin.job.detail', $data);
    }

    public function store(Request $request, $id){
        $this->checkPermission('job_manage');

        if(!empty($request->input('salary_max')) && (int)$request->input('salary_max') > 0 && !empty($request->input('salary_min')) && (int)$request->input('salary_min') > 0) {
            $check = Validator::make($request->input(), [
                'salary_max' => 'required|gt:salary_min',
                'expiration_date' => 'required|date',
            ]);
            if (!$check->validated()) {
                return back()->withInput($request->input());
            }
        }

        if(!is_admin() and !auth()->user()->checkJobPlan()){
            return redirect(route('user.subscription'));
        }

        if($id>0){
            $row = Job::find($id);
            if (empty($row)) {
                return redirect(route('job.admin.index'));
            }
        }else{

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
            'is_featured',
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
            'is_approved',
            'position_id',
            'employment_location'
        ];
        if (!empty($input['wage_agreement'])){
            $input['salary_min'] = 0;
            $input['salary_max'] = 0;
        }

        $row->fillByAttr($attr, $input);

        if ($request->input('expiration_date')) {
            $row->setAttribute('expiration_date', \Illuminate\Support\Carbon::parse($request->input('expiration_date')));
        }

        if($request->input('slug')){
            $row->slug = $request->input('slug');
        }
        if(empty($request->input('create_user'))){
            $row->create_user = Auth::id();
        }
        if(empty($request->input('company_id')) && !is_admin()){
            $user = User::with('company')->find(Auth::id());
            if(!empty($user->company)){
                $row->company_id = $user->company->id;
            }
        }
        $row->employment_location = json_encode($request->input('employment_location'));

        $res = $row->saveOriginOrTranslation($request->query('lang'),true);
        $row->skills()->sync($request->input('job_skills') ?? []);

        if ($res) {
            $meta = SEO::where('object_id', $row->id)->where('object_model', 'job')->first();
            if ($meta) {
                $meta->seo_title = $row->title . '. Location: ' . ($row->location->name ?? 'No') . '. Company: ' . ($row->company->name ?? 'No');
                $meta->save();
            }
            if($id > 0 ){
                if ($row->expiration_date->isPast()){
                    $row->status = 'draft';
                    $row->save();
                    return back()->with('error',  __('The job has been updated, but it has been saved in DRAFT status because the expiration date time has expired.'));
                }
                return back()->with('success',  __('Job updated') );
            }else{
                return redirect(route('job.admin.edit',$row->id))->with('success', __('Job created') );
            }
        }
    }

    public function bulkEdit(Request $request)
    {
        if(!is_admin() and !auth()->user()->checkJobPlan()){
            return redirect(route('user.subscription'));
        }
        $this->checkPermission('job_manage');
        $ids = $request->input('ids');
        $action = $request->input('action');
        if (empty($ids) or !is_array($ids)) {
            return redirect()->back()->with('error', __('No items selected!'));
        }
        if (empty($action)) {
            return redirect()->back()->with('error', __('Please select an action!'));
        }
        if ($action == "delete") {
            foreach ($ids as $id) {
                $query = Job::where("id", $id);
                if (!$this->hasPermission('job_manage_others')) {
                    $company_id = Auth::user()->company->id ?? '';
                    $query->where('company_id', $company_id);
                    $this->checkPermission('job_manage');
                }
                $query->first();
                if(!empty($query)){
                    $query->delete();
                }
            }
        } else {
            foreach ($ids as $id) {
                $query = Job::where("id", $id);
                if (!$this->hasPermission('job_manage_others')) {
                    $company_id = Auth::user()->company->id ?? '';
                    $query->where('company_id', $company_id);
                    $this->checkPermission('job_manage');
                }
                $query->update(['status' => $action]);
            }
        }
        return redirect()->back()->with('success', __('Update success!'));
    }

    public function allApplicants(Request $request){

        $this->setActiveMenu('admin/module/job/all-applicants');
        $candidate_id = $request->query('candidate_id');
        $rows = JobCandidate::with(['jobInfo', 'candidateInfo', 'cvInfo', 'company', 'company.getAuthor'])
            ->whereHas('jobInfo', function ($q) use($request){
                $job_id = $request->query('job_id');
                $company_id = $request->query('company_id');
                if (!$this->hasPermission('job_manage_others')) {
                    $company_id = Auth::user()->company->id ?? '';
                    $q->where('company_id', $company_id);
                }
                if( $company_id && $this->hasPermission('job_manage_others')){
                    $q->where('company_id', $company_id);
                }
                if($job_id){
                    $q->where("id", $job_id);
                }
            });

        if( $candidate_id && $this->hasPermission('job_manage_others')){
            $rows->where('candidate_id', $candidate_id);
        }
        $rows->where('status','!=','delete');
        $rows = $rows->orderBy('id', 'desc')
            ->paginate(20);
        $data = [
            'rows' => $rows
        ];
        return view('Job::admin.job.all-applicants', $data);
    }

    public function applicantsChangeStatus($status, $id){
        $this->checkPermission('job_manage');

        $row = JobCandidate::with('jobInfo', 'jobInfo.user', 'candidateInfo', 'company', 'company.getAuthor')
            ->where('id', $id);

        if (!$this->hasPermission('job_manage_others')) {
            $row = $row->whereHas('jobInfo', function ($q){
                $company_id = Auth::user()->company->id ?? '';
                $q->where('company_id', $company_id);
            });
        };
        $row = $row->first();
        if (empty($row)){
            return redirect()->back()->with('error', __('Item not found!'));
        }
        $old_status = $row->status;
        if($status != 'approved' && $status != 'rejected'){
            return redirect()->back()->with('error', __('Status unavailable'));
        }
        $row->status = $status;
        $row->save();
        //Send Notify and email
        if($old_status != $status) {
            event(new EmployerChangeApplicantsStatus($row));
        }

        return redirect()->back()->with('success', __('Update success!'));
    }

    public function applicantsBulkEdit(Request $request){
        $this->checkPermission('job_manage');
        $ids = $request->input('ids');
        $action = $request->input('action');
        if (empty($ids) or !is_array($ids)) {
            return redirect()->back()->with('error', __('No items selected!'));
        }
        if (empty($action)) {
            return redirect()->back()->with('error', __('Please select an action!'));
        }
        foreach ($ids as $id) {
            $query = JobCandidate::with('jobInfo', 'jobInfo.user', 'candidateInfo', 'company', 'company.getAuthor')->where('id', $id);
            if (!$this->hasPermission('job_manage_others')) {
                $query = $query->whereHas('jobInfo', function ($q){
                    $company_id = Auth::user()->company->id ?? '';
                    $q->where('company_id', $company_id);
                });
            }
            $query = $query->first();
            $old_status = $query->status;
            $query->status = $action;
            $query->save();
            //Send Notify and Email
            if($old_status != $action) {
                event(new EmployerChangeApplicantsStatus($query));
            }

        }
        return redirect()->back()->with('success', __('Update success!'));
    }

    public function applicantsExport(){
        return (new ApplicantsExport())->download('applicants-' . date('M-d-Y') . '.xlsx');
    }

    public function getForSelect2(Request $request)
    {
        $pre_selected = $request->query('pre_selected');
        $selected = $request->query('selected');
        $expiration_date = $request->query('expiration_date','');
        $invited = $request->query('invited');
        $admin_invite = $request->query('admin_invite');

        if($pre_selected && $selected){
            if(is_array($selected))
            {
                $imploded_strings = implode("','", $selected);
                $query = Job::query()->select('id', DB::raw('title as text'));
                $items = $query->whereIn('bc_jobs.id', $selected)->take(50)->orderByRaw(DB::raw("FIELD(id, '$imploded_strings')"))->get();
                return response()->json([
                    'items'=>$items
                ]);
            }
            $item = Job::find($selected);
            if(empty($item)){
                return response()->json([
                    'text'=>''
                ]);
            }else{
                return response()->json([
                    'text'=>$item->name
                ]);
            }
        }
        $q = $request->query('q');

        $query = Job::with(['jobApplicants' => function($query) use ($invited) {
            $query->where('candidate_id', $invited);
        }])
            ->select('id', 'title as text')
            ->selectSub(function($query) use ($invited) {
                $query->select('id')
                    ->from('bc_job_candidates')
                    ->whereRaw('bc_job_candidates.job_id = bc_jobs.id')
                    ->where('bc_job_candidates.candidate_id', '=', $invited)
                    ->limit(1);
            }, 'disabled');

        if ($q) {
            $query->where('title', 'like', '%' . $q . '%');
        }
        if(!is_admin() || $admin_invite){
            $company_id = Auth::user()->company->id ?? '';
            $query->where('company_id', $company_id);
            $query->where('status', '=', 'publish');
        }
        if(!empty($expiration_date))
        {
            $query->whereDate('expiration_date', '>=',  date('Y-m-d'));
        }
        $res = $query->orderBy('id', 'desc')->limit(20)->get();
        return response()->json([
            'results' => $res->map(function ($item) {
                return collect($item)->when($item['disabled'], function ($collection) {
                    return $collection->put('text', $collection['text'] .' (Already invited)');
                });
            })]);
    }
    public function applicantsCreate()
    {
        if (!is_admin()){
            return redirect()->back();
        }
        $this->checkPermission('job_manage');

        $row = new JobCandidate();
        $row->fill([
            'status' => 'publish',
        ]);
        $data = [
            'row'         => $row,
            'breadcrumbs' => [
                [
                    'name' => __('All Applicants'),
                    'url'  => 'admin/module/job/all-applicants'
                ],
                [
                    'name'  => __('Add Applicant'),
                    'class' => 'active'
                ],
            ],
            'translation' => new JobTranslation()
        ];
        return view('Job::admin.job.detail-applicant', $data);
    }
    public function applicantsStore(Request $request)
    {
        $user = Auth::user();
        $candidate_id = $request->input('candidate_id');
        $status = $request->input('status');
        $apply_cv_id = $request->input('apply_cv_id');
        $message = $request->input('content');
        $job_id = $request->input('job_id');
        $company_id = ($user->company) ? $user->company->id : '';

        if(empty($candidate_id)){
            return redirect()->back()->with('error', __('Choose a candidate'));
        }
        if(empty($apply_cv_id)){
            return redirect()->back()->with('error', __('Choose a cv'));
        }
        if(empty($job_id)){
            return redirect()->back()->with('error', __('Choose a job'));
        }
        $row = JobCandidate::query()
            ->where('job_id', $job_id)
            ->where('candidate_id', $candidate_id)
            ->first();
        if ($row){
            return redirect()->back()->with('error', __('You has applied this job already'));
        }
        $row = new JobCandidate();
        $row->job_id = $job_id;
        $row->candidate_id = $candidate_id;
        $row->cv_id = $apply_cv_id;
        $row->message = !empty($message) ? $message : '';
        $row->status = $status;
        $row->company_id = $company_id;
        $row->save();
        $row->load('jobInfo', 'jobInfo.user', 'candidateInfo', 'company', 'company.getAuthor');

        return redirect()->back()->with('success', __('Apply successfully!'));
    }
    public function applicantsGetCv(Request $request)
    {
       $id = $request->query('id');
       $cvs = CandidateCvs::query()->where('origin_id', $id)->with('media')->get();
       return $this->sendSuccess(['cv'=>$cvs],'success');
    }

    public function export(GetJobListRequest $request, JobService $jobService): BinaryFileResponse
    {
        return (new JobExport(
            $jobService->getJobBuilder(
                $request->query('s'),
                $request->query('role'),
                $request->query('orderBy'),
                $request->query('orderDirection')
            )
        ))->download('job-' . date('M-d-Y') . '.xlsx');
    }
}
