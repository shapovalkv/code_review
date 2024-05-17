<?php
namespace Modules\Candidate\Admin;

use App\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\AdminController;
use Modules\Candidate\Exports\CandidateExport;
use Modules\Candidate\Models\CandidateContact;
use Modules\Candidate\Requests\GetCandidateListRequest;
use Modules\Candidate\Services\CandidateService;
use Modules\Job\Events\CandidateDeleteApplied;
use Modules\Job\Models\JobCandidate;
use Modules\Language\Models\Language;
use Modules\Candidate\Models\Category;
use Modules\Candidate\Models\Candidate;
use Modules\Location\Models\Location;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CandidateController extends AdminController
{
    public function __construct()
    {
        $this->setActiveMenu('admin/module/candidate');
        if(!is_admin()){
            $this->middleware('verified');
        }
        parent::__construct();
    }

    public function index(GetCandidateListRequest $request, CandidateService $candidateService): View
    {
        $this->checkPermission('candidate_manage_others');
        $this->isAdmin();

        $data = [
            'rows'               => $candidateService->getUserBuilder(
                $request->query('s'),
                $request->query('cate_id'),
                $request->query('allow_search'),
                $request->query('status'),
                $request->query('orderBy'),
                $request->query('orderDirection'),
            )->paginate(20),
            'categories'         => Category::all(),
            'candidate_location' => Location::query()->where('status', 'publish')->inRandomOrder()->limit(10)->get()->toTree(),
            'breadcrumbs'        => [
                [
                    'name' => __('Candidate'),
                    'url'  => 'admin/module/candidate'
                ],
                [
                    'name'  => __('All'),
                    'class' => 'active'
                ],
            ],
            'page_title'         => __("Candidate Management")
        ];

        return view('Candidate::admin.candidate.index', $data);
    }

    public function bulkEdit(Request $request)
    {
        $this->checkPermission('candidate_manage_others');
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
                if($id == Auth::id()) continue;
                $query = \App\User::where("id", $id)->first();
                $candidate = Candidate::where("id", $id)->first();
                if(!empty($query)){
                    $query->email.='_d_'.uniqid().rand(0,99999);
                    $query->save();
                    $query->delete();

                    if(!empty($candidate)){
                        $candidate->appliedJobs()->delete();
                        $candidate->delete();
                    }
                }
            }
        } else {
            foreach ($ids as $id) {
                $query = Candidate::where("id", $id);
                if (!$this->hasPermission('candidate_manage_others')) {
                    $query->where("create_user", Auth::id());
                    $this->checkPermission('candidate_manage');
                }
                $query->update(['status' => $action]);
            }
        }
        return redirect()->back()->with('success', __('Update success!'));
    }

    public function trans($id,$locale){
        $row = Candidate::find($id);

        if(empty($row)){
            return redirect()->back()->with("danger",__("Candidate does not exist"));
        }

        $translated = Candidate::query()->where('origin_id',$id)->where('lang',$locale)->first();
        if(!empty($translated)){
            redirect($translated->getEditUrl());
        }

        $language = Language::where('locale',$locale)->first();
        if(empty($language)){
            return redirect()->back()->with("danger",__("Language does not exist"));
        }

        $new = $row->replicate();

        if(!$row->origin_id){
            $new->origin_id = $row->id;
        }

        $new->lang = $locale;

        $new->save();


        return redirect($new->getEditUrl());
    }

    function myApplied(Request $request){
        $this->setActiveMenu('admin/module/candidate/my-applied');
        $query = JobCandidate::with(['jobInfo', 'candidateInfo', 'cvInfo'])->where('candidate_id', Auth::id());
        if($s = $request->get('s')){
            $query->whereHas('jobInfo', function ($q) use ($s){
                $q->where("title", 'like', '%'.$s.'%');
            });
        }
        if($status = $request->get('status')){
            $query->where('status', $status);
        }
        if($orderby = $request->get('orderby')){
            switch ($orderby){
                case 'oldest':
                    $query->orderBy('id', 'asc');
                    break;
                default:
                    $query->orderBy('id', 'desc');
                    break;
            }
        }else{
            $query->orderBy('id', 'desc');
        }

        $rows = $query->paginate(20);
        $data = [
            'rows' => $rows
        ];
        return view('Candidate::admin.candidate.my-applied', $data);
    }

    public function deleteJobApplied(Request $request, $id){
        $this->checkPermission('candidate_manage');
        $row = JobCandidate::with('jobInfo', 'jobInfo.user', 'candidateInfo', 'company')
            ->where('candidate_id', Auth::id())
            ->where('id', $id)
            ->first();
        if (empty($row)) {
            return redirect()->back()->with('error', __('Job not found!'));
        }
        if($row->status != 'pending') {
            return redirect()->back()->with('error', __("Can't delete this item"));
        }
        //Send Email and Notify
        event(new CandidateDeleteApplied($row));

        $row->delete();

        return back()->with('success',  __('Delete successfully!') );
    }

    function myContact(Request $request){
        $this->setActiveMenu('admin/module/candidate/my-contact');
        $query = CandidateContact::query()
            ->where(function($q){
                $q->whereNull('contact_to')
                    ->orWhere('contact_to', 'candidate');
            })->where('origin_id', Auth::id());

        if($orderby = $request->get('orderby')){
            switch ($orderby){
                case 'oldest':
                    $query->orderBy('id', 'asc');
                    break;
                default:
                    $query->orderBy('id', 'desc');
                    break;
            }
        }else{
            $query->orderBy('id', 'desc');
        }

        $rows = $query->paginate(20);
        $data = [
            'rows' => $rows
        ];
        return view('Candidate::admin.candidate.my-contact', $data);
    }

    public function getForSelect2(Request $request)
    {
        $pre_selected = $request->query('pre_selected');
        $selected = $request->query('selected');

        if($pre_selected && $selected){
            if(is_array($selected))
            {

                $query = \App\User::query()->whereHas(
                    'role', function($q){
                    $q->where('id', 3);
                });
                $imploded_strings = implode("','", $selected);
                $items = $query->whereIn('id', $selected)->take(50)->orderByRaw(DB::raw("FIELD(id, '$imploded_strings')"))->get();

                $data = [];
                if(!empty($items) && count($items) > 0){
                    foreach($items as $key => $val){
                        $data[] = [
                            'id' => $val->id,
                            'text' => $val->getDisplayName()
                        ];
                    }
                }
                return response()->json([
                    'items'=>$data
                ]);
            }
            $item = Candidate::find($selected);
            if(empty($item)){
                return response()->json([
                    'text'=>''
                ]);
            }else{
                return response()->json([
                    'text'=>$item->getDisplayName()
                ]);
            }
        }
        $s = $request->query('q');
        $listUser = \App\User::query()->whereHas(
            'role', function($q){
            $q->where('id', 3);
        });
        if (!empty($s)) {
            $listUser->where(function($query) use($s){
                $query->where('first_name', 'LIKE', '%' . $s . '%');
                $query->orWhere('last_name', 'LIKE', '%' . $s . '%');
            });
        }

        $res = $listUser->orderBy('id', 'desc')->limit(20)->get();
        $data = [];
        if(!empty($res) && count($res) > 0){
            foreach($res as $key => $val){
                $data[] = [
                    'id' => $val->id,
                    'text' => $val->getDisplayName()
                ];
            }
        }
        return response()->json([
            'results' => $data
        ]);
    }

    public function getCandidateInviteMessage(Request $request)
    {
        $candidateId = $request->input('candidateId');;
        $candidate = Candidate::findOrFail($candidateId);

        $inviteMessageText = __('Hello :candidate,

I would like to set up a time to meet with you to discuss our current open position. Are you available to meet :date.', ['candidate' => is_applied($candidate->id) ? $candidate->user->getDisplayName() : $candidate->user->getShortCutName(), 'date' => display_datetime(time())]);

        return response()->json([
            'results' => $inviteMessageText
        ]);
    }

    public function export(GetCandidateListRequest $request, CandidateService $candidateService): BinaryFileResponse
    {
        return (new CandidateExport(
            $candidateService->getUserBuilder(
                $request->query('s'),
                $request->query('cate_id'),
                $request->query('allow_search'),
                $request->query('status'),
                $request->query('orderBy'),
                $request->query('orderDirection'),
            )
        ))->download('candidate-' . date('M-d-Y') . '.xlsx');
    }
}
