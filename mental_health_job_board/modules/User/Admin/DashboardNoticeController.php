<?php

namespace Modules\User\Admin;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Modules\User\Models\Role;
use Modules\AdminController;
use Modules\User\Models\DashboardNotice;
use Modules\User\Requests\StoreDashboardNoticeRequest;

class DashboardNoticeController extends AdminController
{

    public function index(): View
    {
        $this->checkPermission('dashboard_notice_manage');
        $notices = [];
        /** @var DashboardNotice $notice */
        foreach (DashboardNotice::query()->orderBy('sort')->orderBy('created_at', 'desc')->get() as $notice) {
            $notices[$notice->filter['role_id']][] = $notice;
        }

        return view('User::admin.dashboard-notice.index', ['notices' => $notices, 'roles' =>  Role::query()->orderBy('id', 'desc')->get()]);
    }

    public function edit(?DashboardNotice $dashboardNotice = null): View
    {
        if (!$dashboardNotice) {
            $dashboardNotice = new DashboardNotice;
        }

        return view('User::admin.dashboard-notice.edit', ['notice' => $dashboardNotice]);
    }

    public function store(StoreDashboardNoticeRequest $request, ?DashboardNotice $dashboardNotice = null): RedirectResponse
    {

        if (!$dashboardNotice) {
            $dashboardNotice = new DashboardNotice;
        }

        $dashboardNotice->fill($request->all());
        $dashboardNotice->touch();

        return redirect()->route('user.admin.notice.index')->with('success', 'Notice created');
    }

}
