<?php

namespace Modules\User\Admin;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Modules\AdminController;
use Modules\Order\Models\OrderItem;
use Modules\User\Exports\PlanReportExport;
use Modules\User\Models\Plan;
use Modules\User\Requests\GetPlanReportRequest;
use Modules\User\Services\PlanReportService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PlanReportController extends AdminController
{

    public function __construct()
    {
        parent::__construct();

        $this->setActiveMenu(route('user.admin.plan.index'));
    }

    public function index(GetPlanReportRequest $request, PlanReportService $planReportService): View
    {
        $items = $planReportService->getGroupedReportBuilder(
            $request->getPlanTypes(),
            $request->getPlanIds(),
            PlanReportService::SEPARATE_MONTH,
            $request->getFrom(),
            $request->getTo(),
            'asc'
        )->where('price', '>', 0)->get();

        return view('User::admin.plan-report.index', [
            'items'          => $planReportService->getGroupedReportBuilder(
                $request->getPlanTypes(),
                $request->getPlanIds(),
                $request->input('separate'),
                $request->getFrom(),
                $request->getTo(),
            )->paginate(10),
            'plans'          => Plan::all(),
            'planTypes'      => [
                Plan::TYPE_RECURRING => __('Recurring'),
                Plan::TYPE_ONE_TIME  => __('One time'),
                Plan::TYPE_FREE      => __('Free'),
            ],
            'separate'       => [
                PlanReportService::SEPARATE_YEAR  => 'Year',
                PlanReportService::SEPARATE_MONTH => 'Month',
                PlanReportService::SEPARATE_WEEK  => 'Week',
            ],
            'chartSalesData' => $planReportService->getSalesChartData($items),
            'chartPlansData' => $planReportService->getPlanChartData($items),
            'startDate'      => OrderItem::query()->select('created_at')->orderBy('created_at')->first()->created_at
        ]);
    }

    public function export(GetPlanReportRequest $request): BinaryFileResponse
    {
        return Excel::download(new PlanReportExport(
            $request->getPlanTypes(),
            $request->getPlanIds(),
            $request->input('separate'),
            $request->getFrom(),
            $request->getTo(),
        ), 'plan-report-' . $request->input('separate') . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function chart(GetPlanReportRequest $request, PlanReportService $planReportService): JsonResponse
    {
        return match ($request->input('chart')) {
            'sales' => $this->sendSuccess([
                'data' => $planReportService->getSalesChartData(
                    $planReportService->getGroupedReportBuilder(
                        [],
                        [],
                        PlanReportService::SEPARATE_MONTH,
                        $request->getFrom(),
                        $request->getTo(),
                    )->get()
                )
            ]),
            'plans' => $this->sendSuccess([
                'data' => $planReportService->getPlanChartData(
                    $planReportService->getGroupedReportBuilder(
                        [],
                        [],
                        PlanReportService::SEPARATE_MONTH,
                        $request->getFrom(),
                        $request->getTo(),
                    )->get()
                )
            ]),
            default => $this->sendSuccess([
                'data' => []
            ]),
        };
    }
}
