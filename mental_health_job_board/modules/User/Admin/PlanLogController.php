<?php

namespace Modules\User\Admin;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\AdminController;
use Modules\User\Exports\PlanLogExport;
use Modules\User\Models\Plan;
use Modules\User\Models\UserPlan;
use Modules\User\Requests\GetPlanLogRequest;
use Modules\User\Services\PlanLogService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PlanLogController extends AdminController
{

    public function __construct()
    {
        parent::__construct();
        $this->setActiveMenu(route('user.admin.plan.index'));
    }

    public function index(GetPlanLogRequest $request, PlanLogService $planLogService): View
    {
        $this->checkPermission('user_manage');

        $data = [
            'rows'        => $planLogService
                ->getGroupedReportBuilder(
                    $request->getStatusIds(),
                    $request->getPlanIds(),
                    $request->input('create_user'),
                    $request->getFrom(),
                    $request->getTo(),
                    $request->query('orderBy'),
                    $request->query('orderDirection')
                )
                ->paginate(20),
            'plans'       => Plan::query()->where('status', Plan::STATUS_PUBLISH)->get(),
            'statuses'    => [UserPlan::USED => 'Expired', UserPlan::CURRENT => 'Active', UserPlan::NOT_USED => 'Waiting'],
            'breadcrumbs' => [
                [
                    'name'  => __('User Plans'),
                    'class' => 'active'
                ],
            ],
            'page_title'  => __("Plan Log")
        ];
        return view('User::admin.plan-log.index', $data);
    }

    public function bulkEdit(Request $request)
    {
        $this->checkPermission('user_manage');
        $ids = $request->input('ids');
        $action = $request->input('action');
        if (empty($ids) or !is_array($ids)) {
            return redirect()->back()->with('error', __('Select at least 1 item!'));
        }
        if (empty($action)) {
            return redirect()->back()->with('error', __('Select an Action!'));
        }
        if ($action == "delete") {
            foreach ($ids as $id) {
                $query = Plan::query()->where("id", $id)->first();
                if (!empty($query)) {
                    //Del parent category
                    $query->delete();
                }
            }
        } else {
            foreach ($ids as $id) {
                $query = Plan::where("id", $id);
                $query->update(['status' => $action]);
            }
        }
        return redirect()->back()->with('success', __('Updated success!'));
    }

    public function export(GetPlanLogRequest $request, PlanLogService $planLogService): BinaryFileResponse
    {
        return Excel::download(new PlanLogExport(
            $planLogService
                ->getGroupedReportBuilder(
                    $request->getStatusIds(),
                    $request->getPlanIds(),
                    $request->input('create_user'),
                    $request->getFrom(),
                    $request->getTo(),
                    $request->query('orderBy'),
                    $request->query('orderDirection')
                )
        ), 'plan-log-' . time() . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }
}
