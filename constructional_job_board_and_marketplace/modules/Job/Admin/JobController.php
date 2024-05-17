<?php
namespace Modules\Job\Admin;

use App\Notifications\PrivateChannelServices;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\AdminController;
use Modules\Candidate\Models\CandidateCvs;
use Modules\Job\Events\CandidateApplyJobSubmit;
use Modules\Job\Models\JobCategory as Category;
use Modules\Job\Events\EmployerChangeApplicantsStatus;
use Modules\Job\Exports\ApplicantsExport;
use Modules\Job\Models\Job;
use Modules\Job\Models\JobCandidate;
use Modules\Job\Models\JobTranslation;
use Modules\Job\Models\JobType;
use Modules\Language\Models\Language;
use Modules\Location\Models\Location;
use Modules\Location\Services\LocationService;
use Modules\Media\Models\MediaFile;
use Modules\Skill\Models\Skill;
use Illuminate\Support\Facades\DB;

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

    public function index(Request $request)
    {
        $this->checkPermission('job_manage');
        $jobQuery = Job::query()->with(['location', 'category', 'company'])->orderBy('id', 'desc');
        $title = $request->query('keywords');
        $cate = $request->query('category_id');
        $companyId = $request->query('company_id');
        if ($cate) {
            $jobQuery->where('category_id', $cate);
        }
        if($companyId){
            $jobQuery->where('company_id', $companyId);
        }
        if ($title) {
            $jobQuery->where('title', 'LIKE', '%' . $title . '%');
            $jobQuery->orderBy('title', 'asc');
        }
        if(!is_admin()){
            $companyId = Auth::user()->company->id ?? '';
            $jobQuery->where('company_id', $companyId);
        }

        $data = [
            'rows'        => $jobQuery->paginate(20),
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
            "languages"=>Language::getActive(false),
            "locale"=>\App::getLocale(),
            'page_title'=>__("Jobs Management")
        ];
        return view('Job::admin.job.index', $data);
    }

    public function create(Request $request)
    {
        $this->checkPermission('job_manage');

        if(!is_admin()){
            if(!auth()->user()->checkJobPlan()) {
                return redirect(route('user.plan'));
            }
        }

        $row = new Job();
        $row->fill([
            'status' => 'publish',
        ]);
        $data = [
            'categories'        => Category::get()->toTree(),
            'job_types' => JobType::query()->where('status', 'publish')->get(),
            'job_skills' => Skill::query()->where('status', 'publish')->where('skill_type', '=','job')->get(),
            'job_location'     => Location::where('status', 'publish')->get()->toTree(),
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

        $row = Job::with('skills', 'location')->find($id);

        $translation = $row->translateOrOrigin($request->query('lang'));
        $companyId = Auth::user()->company->id ?? '';

        if (empty($row)) {
            return redirect(route('job.admin.index'));
        }elseif(!is_admin() && $companyId != $row->company_id){
            return redirect(route('job.admin.index'));
        }

        $data = [
            'row'  => $row,
            'translation'  => $translation,
            'categories' => Category::query()->where('status', 'publish')->get()->toTree(),
            'job_types' => JobType::query()->where('status', 'publish')->get(),
            'job_skills' => Skill::query()->where('status', 'publish')->where('skill_type', '=','job')->get(),
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

    public function store(Request $request, LocationService $locationService, $id){
        $this->checkPermission('job_manage');

        if(!empty($request->input('salary_max')) && (int)$request->input('salary_max') > 0 && !empty($request->input('salary_min')) && (int)$request->input('salary_min') > 0) {
            $check = Validator::make($request->input(), [
                'salary_max' => 'required|gt:salary_min'
            ]);
            if (!$check->validated()) {
                return back()->withInput($request->input());
            }
        }

        if(!is_admin() and !auth()->user()->checkJobPlan()){
            return redirect(route('user.plan'));
        }

        if($id>0){
            $row = Job::find($id);
            if (empty($row)) {
                return redirect(route('job.admin.index'));
            }
        }else{

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
            'seniority_level',
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
            'number_recruitments'
        ];
        $row->fillByAttr($attr, $input);
        $row->location_id = $locationService->store($request);
        if($expirationDate = $request->input('expiration_date')){
            $row->expiration_date = Carbon::createFromFormat(get_date_format(), $expirationDate);
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

        $res = $row->saveOriginOrTranslation($request->query('lang'),true);
        $row->skills()->sync($request->input('job_skills') ?? []);

        if ($res) {
            if($id > 0 ){
                return back()->with('success',  __('Job updated') );
            }else{
                return redirect(route('job.admin.edit',$row->id))->with('success', __('Job created') );
            }
        }
    }

    public function bulkEdit(Request $request)
    {
        if(!is_admin() and !auth()->user()->checkJobPlan()){
            return redirect(route('user.plan'));
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
                    $companyId = Auth::user()->company->id ?? '';
                    $query->where('company_id', $companyId);
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
                    $companyId = Auth::user()->company->id ?? '';
                    $query->where('company_id', $companyId);
                    $this->checkPermission('job_manage');
                }
                $query->update(['status' => $action]);
            }
        }
        return redirect()->back()->with('success', __('Update success!'));
    }

    public function allApplicants(Request $request){

        $this->setActiveMenu('admin/module/job/all-applicants');
        $candidateId = $request->query('candidate_id');
        $rows = JobCandidate::with(['jobInfo', 'candidateInfo', 'cvInfo', 'company', 'company.getAuthor'])
            ->whereHas('jobInfo', function ($q) use($request){
                $jobId = $request->query('job_id');
                $companyId = $request->query('company_id');
                if (!$this->hasPermission('job_manage_others')) {
                    $companyId = Auth::user()->company->id ?? '';
                    $q->where('company_id', $companyId);
                }
                if( $companyId && $this->hasPermission('job_manage_others')){
                    $q->where('company_id', $companyId);
                }
                if($jobId){
                    $q->where("id", $jobId);
                }
            });

        if( $candidateId && $this->hasPermission('job_manage_others')){
            $rows->where('candidate_id', $candidateId);
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
                $companyId = Auth::user()->company->id ?? '';
                $q->where('company_id', $companyId);
            });
        };
        $row = $row->first();
        if (empty($row)){
            return redirect()->back()->with('error', __('Item not found!'));
        }
        $oldStatus = $row->status;
        if($status != 'approved' && $status != 'rejected'){
            return redirect()->back()->with('error', __('Status unavailable'));
        }
        $row->status = $status;
        $row->save();
        //Send Notify and email
        if($oldStatus != $status) {
            event(new EmployerChangeApplicantsStatus($row, null));
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
                    $companyId = Auth::user()->company->id ?? '';
                    $q->where('company_id', $companyId);
                });
            }
            $query = $query->first();
            $oldStatus = $query->status;
            $query->status = $action;
            $query->save();
            //Send Notify and Email
            if($oldStatus != $action) {
                event(new EmployerChangeApplicantsStatus($query, null));
            }

        }
        return redirect()->back()->with('success', __('Update success!'));
    }

    public function applicantsExport(){
        return (new ApplicantsExport())->download('applicants-' . date('M-d-Y') . '.xlsx');
    }

    public function getForSelect2(Request $request)
    {
        $preSelected = $request->query('pre_selected');
        $selected = $request->query('selected');
        $expiration_date = $request->query('expiration_date','');

        if($preSelected && $selected){
            if(is_array($selected))
            {
                $implodedStrings = implode("','", $selected);
                $query = Job::query()->select('id', DB::raw('title as text'));
                $items = $query->whereIn('bc_jobs.id', $selected)->take(50)->orderByRaw(DB::raw("FIELD(id, '$implodedStrings')"))->get();
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
        $keywords = $request->query('keywords');
        $query = Job::select('id', 'title as text')->where("status","publish");
        if ($keywords) {
            $query->where('title', 'like', '%' . $keywords . '%');
        }
        if(!is_admin()){
            $companyId = Auth::user()->company->id ?? '';
            $query->where('company_id', $companyId);
        }
        if(!empty($expiration_date))
        {
            $query->where('expiration_date', '>=',  date('Y-m-d H:s:i'));
        }
        $res = $query->orderBy('id', 'desc')->limit(20)->get();
        return response()->json([
            'results' => $res
        ]);
    }
    public function applicantsCreate()
    {
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
        $candidateId = $request->input('candidate_id');
        $status = $request->input('status');
        $applyCvId = $request->input('apply_cv_id');
        $message = $request->input('content');
        $jobId = $request->input('job_id');
        $companyId = ($user->company) ? $user->company->id : '';

        if(empty($candidateId)){
            return redirect()->back()->with('error', __('Choose a candidate'));
        }
        if(empty($applyCvId)){
            return redirect()->back()->with('error', __('Choose a cv'));
        }
        if(empty($jobId)){
            return redirect()->back()->with('error', __('Choose a job'));
        }
        $row = JobCandidate::query()
            ->where('job_id', $jobId)
            ->where('candidate_id', $candidateId)
            ->first();
        if ($row){
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

        return redirect()->back()->with('success', __('Apply successfully!'));
    }
    public function applicantsGetCv(Request $request)
    {
       $id = $request->query('id');
       $cvs = CandidateCvs::query()->where('origin_id', $id)->with('media')->get();
       return $this->sendSuccess(['cv'=>$cvs],'success');
    }
}
