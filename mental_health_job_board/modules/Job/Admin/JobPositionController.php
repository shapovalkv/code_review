<?php
namespace Modules\Job\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\AdminController;
use Modules\Job\Models\JobPosition;
use Modules\Job\Models\JobPositionTranslation;

class JobPositionController extends AdminController
{
    public function __construct()
    {
        $this->setActiveMenu('admin/module/job/category');
        parent::__construct();
    }

    public function index(Request $request)
    {
        $this->checkPermission('job_manage_others');
        $this->isAdmin();

        $positionList = new JobPosition;
        if ($catename = $request->query('s')) {
            $positionList = $positionList->where('name', 'LIKE', '%' . $catename . '%');
        }
        $positionList = $positionList->orderby('id', 'desc');
        $rows = $positionList->get();

        $data = [
            'rows'        => $rows->toTree(),
            'row'         => new JobPosition(),
            'breadcrumbs' => [
                [
                    'name' => __('Job'),
                    'url'  => 'admin/module/job'
                ],
                [
                    'name'  => __('Category'),
                    'class' => 'active'
                ],
            ],
            'translation'=>new JobPositionTranslation()
        ];
        return view('Job::admin.position.index', $data);
    }

    public function edit(Request $request, $id)
    {
        $this->checkPermission('job_manage_others');
        $row = JobPosition::find($id);

        $translation = $row->translateOrOrigin($request->query('lang'));

        if (empty($row)) {
            return redirect('admin/module/job/position');
        }
        $data = [
            'row'     => $row,
            'translation'     => $translation,
            'parents' => JobPosition::get()->toTree(),
            'enable_multi_lang'=>true
        ];
        return view('Job::admin.position.detail', $data);
    }

    public function store(Request $request, $id){
        $this->checkPermission('job_manage_others');

        if($id>0){
            $row = JobPosition::find($id);
            if (empty($row)) {
                return redirect(route('job.admin.position.index'));
            }
        }else{
            $row = new JobPosition();
            $row->status = "publish";
        }

        $row->fill($request->input());
        $res = $row->saveOriginOrTranslation($request->input('lang'), true);

        if ($res) {
            if($id > 0 ){
                return back()->with('success',  __('Position updated') );
            }else{
                return redirect(route('job.admin.position.index'))->with('success', __('Position created') );
            }
        }
    }

    public function bulkEdit(Request $request)
    {
        $this->checkPermission('job_manage_others');
        $ids = $request->input('ids');
        $action = $request->input('action');
        if (empty($ids) or !is_array($ids)) {
            return redirect()->back()->with('error', __('Please select at least 1 item!'));
        }
        if (empty($action)) {
            return redirect()->back()->with('error', __('Please select an Action!'));
        }
        if ($action == 'delete') {
            foreach ($ids as $id) {
                $query = JobPosition::where("id", $id)->first();
                if(!empty($query)){
                    $query->delete();
                }
            }
        }else{

            foreach ($ids as $id) {
                $query = JobPosition::where("id", $id)->update(['status' => $action]);
            }
        }
        return redirect()->back()->with('success', __('Update success!'));
    }

    public function getForSelect2(Request $request)
    {
        $pre_selected = $request->query('pre_selected');
        $selected = $request->query('selected');

        if($pre_selected && $selected){
            if(is_array($selected))
            {
                $query = JobPosition::query()->select('id', DB::raw('name as text'));
                $items = $query->whereIn('bc_job_position.id', $selected)->take(50)->get();
                return response()->json([
                    'items'=>$items
                ]);
            }
            $item = JobPosition::find($selected);
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
        $query = JobPosition::select('id', 'name as text')->where("status","publish");
        if ($q) {
            $query->where('name', 'like', '%' . $q . '%');
        }
        $res = $query->orderBy('id', 'desc')->limit(20)->get();
        return response()->json([
            'results' => $res
        ]);
    }
}
