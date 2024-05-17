<?php

namespace Modules\User\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Modules\FrontendController;
use Modules\User\Models\DashboardNotice;

class DashboardNoticeController extends FrontendController
{

    public function readNotice(DashboardNotice $dashboardNotice): JsonResponse
    {
        $dashboardNotice->users()->attach(auth()->user());

        return response()->json(['success' => true]);
    }

}
