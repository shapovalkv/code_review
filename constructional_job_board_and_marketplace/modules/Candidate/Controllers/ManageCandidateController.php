<?php


namespace Modules\Candidate\Controllers;


use App\Services\BasicFilterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Modules\Candidate\Resources\ApplicantsAppliedJobResource;
use Modules\FrontendController;
use Modules\Job\Events\CandidateDeleteApplied;
use Modules\Job\Models\Job;
use Modules\Job\Models\JobCandidate;
use Modules\Job\Models\JobCategory as Category;
use Modules\Job\Requests\BulkJobRequest;
use Modules\Job\Resources\CategoryResource;

class ManageCandidateController extends FrontendController
{
    protected $jobClass;

    public function __construct()
    {
        parent::__construct();
        $this->jobClass = Job::class;
    }

    public function appliedJobs(Request $request, BasicFilterService $basicFilterService)
    {
        $this->checkPermission('candidate_manage');

        $jobs = call_user_func([JobCandidate::class, 'search'], $request, null, Auth::id());

        $data = [
            'rows' => ApplicantsAppliedJobResource::collection($jobs),
            'pagination' => $basicFilterService->pagination($jobs),
            'filters' => [
                'category' => [
                    'items' => CategoryResource::collection(Category::where('status', 'publish')->withCount('openJobs')->get()->sortBy('name')->toTree()),
                    'values' => $request->category,
                ],
                'status' => $basicFilterService->applicantStatus($request->status),
                'date' => $basicFilterService->date($request->date),
                'orderby' => $basicFilterService->orderby($request->orderby),
                'limit' => $basicFilterService->limit($request->limit),
                'keywords' => $basicFilterService->popularSearchKeywords($request->keywords, App::make($this->jobClass)->type),
                'location' => $basicFilterService->popularSearchLocation($request->location, App::make($this->jobClass)->type),

            ],
            'menu_active' => 'applied_jobs',
            'page_title' => __("Applied Jobs")
        ];

        return view('Candidate::frontend.applied-jobs', $data);
    }

    public function deleteJobApplied(Request $request, $id)
    {
        $this->checkPermission('candidate_manage');
        $row = JobCandidate::query()
            ->where('candidate_id', Auth::id())
            ->where('id', $id)
            ->first();
        if (empty($row)) {
            return redirect()->back()->with('error', __('Job not found!'));
        }
        if ($row->status != 'pending') {
            return redirect()->back()->with('error', __("Can't delete this item"));
        }
        //Send Email and Notify
        event(new CandidateDeleteApplied($row));

        $row->delete();

        return back()->with('success', __('Delete successfully!'));
    }

    public function bulk(BulkJobRequest $request)
    {
        $this->checkPermission('candidate_manage');
        $data = $request->validated();

        $query = JobCandidate::with('jobInfo', 'jobInfo.user', 'candidateInfo', 'company', 'company.getAuthor')
            ->where('candidate_id', Auth::id())
            ->whereIn('bc_job_candidates.id', $data['ids']);

        foreach ($data['ids'] as $id) {
            $query = $query->where('id', $id)->first();
            $oldStatus = $query->status;
            if ($oldStatus->status != 'pending') {
                return redirect()->back()->with('error', __("Can't delete this item"));
            }
            $query->update(['status' => $data['applicant_status']]);
            //Send Notify and Email
            event(new CandidateDeleteApplied($query));
        }

        return response()->json(['status' => 'success', 'message' => __('Update success!')]);
    }

}
