<?php
namespace Modules\MarketplaceUser\Admin;

use App\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\AdminController;
use Modules\MarketplaceUser\Exports\MarketplaceUserExport;
use Modules\MarketplaceUser\Models\MarketplaceUser;
use Modules\MarketplaceUser\Requests\GetMarketplaceUserListRequest;
use Modules\MarketplaceUser\Services\MarketplaceUserService;
use Modules\User\Models\Role;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class MarketplaceUserController extends AdminController
{
    public function __construct()
    {
        $this->setActiveMenu('admin/module/marketplace_user');
        if(!is_admin()){
            $this->middleware('verified');
        }
        parent::__construct();
    }

    public function index(GetMarketplaceUserListRequest $request, MarketplaceUserService $marketplaceUserService): View
    {
        $this->checkPermission('marketplace_user_manage_others');
        $this->isAdmin();

        $data = [
            'rows'        => $marketplaceUserService->getMarketplaceUserBuilder(
                $request->query('s'),
                $request->query('status'),
                $request->query('orderBy'),
                $request->query('orderDirection')
            )->paginate(20),
            'breadcrumbs' => [
                [
                    'name' => __('MarketplaceUser'),
                    'url'  => 'admin/module/marketplace_user'
                ],
                [
                    'name'  => __('All'),
                    'class' => 'active'
                ],
            ],
            'page_title'=>__("Marketplace User Management")
        ];
        return view('MarketplaceUser::admin.marketplace_user.index', $data);
    }

    public function bulkEdit(Request $request)
    {
        $this->checkPermission('marketplace_user_manage_others');
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
                $marketplace_user = MarketplaceUser::where("id", $id)->first();
                if(!empty($query)){
                    $query->email.='_d_'.uniqid().rand(0,99999);
                    $query->save();
                    $query->delete();

                    if(!empty($marketplace_user)){
                        $marketplace_user->delete();
                    }
                }
            }
        } else {
            foreach ($ids as $id) {
                $query = MarketplaceUser::where("id", $id);
                if (!$this->hasPermission('marketplace_user_manage_others')) {
                    $query->where("create_user", Auth::id());
                    $this->checkPermission('marketplace_user_manage');
                }
                $query->update(['status' => $action]);
            }
        }
        return redirect()->back()->with('success', __('Update success!'));
    }

    public function export(GetMarketplaceUserListRequest $request, MarketplaceUserService $marketplaceUserService): BinaryFileResponse
    {
        return (new MarketplaceUserExport(
            $marketplaceUserService->getMarketplaceUserBuilder(
                $request->query('s'),
                $request->query('status'),
                $request->query('orderBy'),
                $request->query('orderDirection')
            )
        ))->download('marketplace-user-' . date('M-d-Y') . '.xlsx');
    }

}
