<?php
namespace Modules\User\Admin;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Modules\AdminController;
use Modules\User\Models\Plan;
use Modules\User\Models\PlanFeature;
use Modules\User\Models\PlanTranslation;
use Modules\User\Models\Promocode;
use Modules\User\Requests\StorePromocodeRequest;

class PromocodeController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->setActiveMenu(route('user.admin.promocode.index'));
    }

    public function index(Request $request): View
    {
        $this->checkPermission('user_manage');
        $builder = Promocode::withTrashed();

        if (!empty($search = $request->query('s'))) {
            $builder->where('title', 'LIKE', '%' . $search . '%');
        }

        return view('User::admin.promocode.index', [
            'rows'        => $builder->orderBy('id', 'desc')->paginate(20),
            'breadcrumbs' => [
                [
                    'name'  => __('Promo Codes'),
                    'class' => 'active'
                ],
            ],
        ]);
    }

    public function edit(Promocode $promocode): View
    {
        $this->checkPermission('user_manage');
        $data = [
            'row'               => $promocode,
            'breadcrumbs'       => [
                [
                    'name'  => __('Promo Codes'),
                    'class' => 'active'
                ],
            ],
        ];
        return view('User::admin.promocode.detail', $data);
    }

    public function store(StorePromocodeRequest $request, $id): RedirectResponse
    {
        $this->checkPermission('user_manage');

        $row = $id > 0 ? Promocode::query()->findOrFail($id) : new Promocode;

        $request->merge([
            'is_percent' => $request->input('is_percent', false),
            'is_annual'  => $request->input('is_annual', false),
        ]);

        $row->fill($request->input());
        $row->save();

        if ($request->getPlanIds() !== []) {
            $row->plans()->sync($request->getPlanIds());
        }

        return redirect()->route('user.admin.promocode.edit', ['promocode' => $row->id])->with('success',  __('Promo Code saved') );
    }

    public function bulkEdit(Request $request): RedirectResponse
    {
        $ids = $request->input('ids');
        $action = $request->input('action');
        if (empty($ids) or !is_array($ids)) {
            return redirect()->back()->with('error', __('Select at least 1 item!'));
        }
        if (empty($action)) {
            return redirect()->back()->with('error', __('Select an Action!'));
        }
        if ($action === 'delete') {
            Promocode::query()->whereIn('id', $ids)->delete();
        } elseif($action === 'restore') {
            Promocode::withTrashed()->whereIn('id', $ids)->restore();
        }
        return redirect()->back()->with('success', __('Updated success!'));
    }
}
