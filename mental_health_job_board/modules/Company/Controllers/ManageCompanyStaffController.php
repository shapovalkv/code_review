<?php

namespace Modules\Company\Controllers;

use App\Enums\UserPermissionEnum;
use App\User;
use App\UserPermission;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Company\Events\SendMailStaffCreated;
use Modules\Company\Requests\StoreSubAccountRequest;
use Modules\FrontendController;
use Modules\User\Models\PlanFeature;
use Modules\User\Models\Role;

class ManageCompanyStaffController extends FrontendController
{

    public function index(): View
    {
        $this->checkPermission('employer_manage', UserPermissionEnum::COMPANY_STAFF_MANAGE);

        /** @var User $user */
        $user = auth()->user();
        $user->parent && $user = $user->parent;
        $user->load(User::RELATION_STAFF);

        return view('Company::frontend.layouts.staff.index', [
            'rows' => $user->staff,
        ]);
    }

    public function edit(User $user = null): View|RedirectResponse
    {
        $this->checkPermission('employer_manage', UserPermissionEnum::COMPANY_STAFF_MANAGE);

        $register = false;

        if (!$user) {
            $user = new User;
            $register = true;
        }

        $parent = auth()->user();
        $parent->parent && $parent = $parent->parent;

        if (!$parent->checkSubAccountPlan(!$register)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', __('Your Subscription Plan have max :count Employee accounts', ['count' => $parent->getCurrentPlanFeatureCount(PlanFeature::SUB_ACCOUNTS)]));
        }

        return view('Company::frontend.layouts.staff.edit', [
            'row'     => $user,
            'company' => $parent->company
        ]);
    }

    public function store(StoreSubAccountRequest $request, User $user = null): RedirectResponse
    {
        $this->checkPermission('employer_manage', UserPermissionEnum::COMPANY_STAFF_MANAGE);

        $register = false;

        if (null === $user) {
            $user = new User;
            $register = true;
        }

        $parent = auth()->user();
        $parent->parent && $parent = $parent->parent;

        if (!$parent->checkSubAccountPlan(!$register)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', __('Your Subscription Plan have max :count Employee accounts', ['count' => $parent->getCurrentPlanFeatureCount(PlanFeature::SUB_ACCOUNTS)]));
        }

        $user->fill($request->input());
        if ($register) {
            $password = Str::random(8);

            $user->setAttribute('password', Hash::make($password));
            $user->setAttribute('show_tutorial_popup', 0);
            $user->setAttribute('need_update_pw', 1);
            $user->setAttribute('role_id', Role::EMPLOYEE);
            $user->parent()->associate($parent);
        }

        $user->save();
        $user->markEmailAsVerified();
        $permissions = [];

        foreach ((array)$request->input('permissions') as $key => $value) {
            $permissions[] = (new UserPermission)->setAttribute('permission', $key);
        }

        $user->permissions()->delete();
        $user->permissions()->saveMany($permissions);
        if ($register) {
            event(new SendMailStaffCreated($user, $password));
        }

        return redirect()->route('user.company.staff');
    }

    public function delete(User $trashedUser): RedirectResponse
    {
        $this->checkPermission('employer_manage', UserPermissionEnum::COMPANY_STAFF_MANAGE);

        if ($trashedUser->parent->id !== auth()->user()->id) {
            return redirect()->back()->with('error', 'You have no permissions');
        }

        $trashedUser->forceDelete();

        return redirect()->route('user.company.staff')->with('success', 'Account successfully deleted');
    }

    public function disable(User $trashedUser): RedirectResponse
    {
        $this->checkPermission('employer_manage', UserPermissionEnum::COMPANY_STAFF_MANAGE);

        $user = auth()->user();
        if ($trashedUser->parent->id !== $user->id && $trashedUser->parent->id !== $user->parent->id) {
            return redirect()->back()->with('error', 'You have no permissions');
        }

        $trashedUser->delete();

        return redirect()->route('user.company.staff')->with('success', 'Account successfully disabled');
    }

    public function enable(User $trashedUser): RedirectResponse
    {
        $this->checkPermission('employer_manage', UserPermissionEnum::COMPANY_STAFF_MANAGE);

        $user = auth()->user();
        if ($trashedUser->parent->id !== $user->id && $trashedUser->parent->id !== $user->parent->id) {
            return redirect()->back()->with('error', 'You have no permissions');
        }

        if (!$user->parent->checkSubAccountPlan()) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', __('Your Subscription Plan have max :count Employee accounts', ['count' => $user->parent->getCurrentPlanFeatureCount(PlanFeature::SUB_ACCOUNTS)]));
        }

        $trashedUser->restore();

        return redirect()->route('user.company.staff')->with('success', 'Account successfully enabled');
    }

}
