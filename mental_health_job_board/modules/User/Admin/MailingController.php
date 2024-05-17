<?php

namespace Modules\User\Admin;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Modules\AdminController;
use Modules\User\Emails\UserMailing;
use Modules\User\Jobs\UserMailingJob;
use Modules\User\Models\Role;
use Modules\User\Requests\CreateMailingRequest;
use Modules\User\ValueObjects\MailingFilter;

class MailingController extends AdminController
{
    public function index(): View
    {
        $this->checkPermission('mailing_manage');

        return view('User::admin.mailing.index', ['roles' => Role::all(), 'tags' => UserMailing::CODE]);
    }

    public function store(CreateMailingRequest $request): RedirectResponse
    {
        $this->checkPermission('mailing_manage');

        UserMailingJob::dispatch(
            new MailingFilter($request->getRoleIds(), $request->getRegisterFrom(), $request->getStatus()),
            $request->get('subject'),
            $request->get('body'),
        );

        return redirect()->back()->with(['success' => __('Mailing run successfully')]);
    }
}
