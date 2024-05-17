<?php
namespace Modules\Payout\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\AdminController;
use Modules\Location\Models\Location;
use Modules\Payout\Commands\CreatePayoutsCommand;
use Modules\Payout\Models\VendorPayout;
use Modules\Payout\Models\VendorPayoutAccount;
use Illuminate\Support\Facades\Artisan;

class PayoutController extends AdminController
{
    /**
     * @var VendorPayout
     */
    protected $vendorPayoutClass;

    public function __construct()
    {

        $this->vendorPayoutClass = VendorPayout::class;
        $this->setActiveMenu('admin/module/payout');
        parent::__construct();
    }
    public function runPayout()
    {
//        $this->checkPermission('admin_payout_manage');
//        Artisan::call('candidate:create_payouts');
//        dd("done");
    }
    public function candidateIndex(Request $request)
    {
        if(!VendorPayout::isEnable()) {
            return redirect('/');
        }
        $this->checkPermission('candidate_payout_manage');
        $this->setActiveMenu('payout');
        $user = Auth::user();
        $data = [
            'page_title'=>__('Payouts'),
            'payouts'=>$user->payouts()->orderBy('id','desc')->paginate(20),
            'currentUser'=>Auth::user(),
            "current_payout"=>$user->current_payout,
            'payout_account'=>$user->payout_account
        ];

        return view("Payout::admin.payouts.candidate-index",$data);
    }
    public function storePayoutAccount(Request $request){
        if(!VendorPayout::isEnable()) {
            return redirect('/');
        }
        $this->checkPermission('candidate_payout_manage');

        $request->validate([
            'payout_method'=>"required",
            "account_info"=>'required|array'
        ]);

        $user = Auth::user();
        $payout_account = $user->payout_account;
        if(!$payout_account){
            $payout_account = new VendorPayoutAccount();
            $payout_account->vendor_id = $user->id;
        }

        $account_info = $request->input('account_info');
        $payout_method = $request->input('payout_method');
        if(empty($account_info[$payout_method])){
            return $this->sendError(__("Please enter payout account info"));
        }

        $data = [
            'payout_method'=>$request->input('payout_method'),
            'account_info'=>[$account_info[$payout_method]]
        ];
        $payout_account->fillByAttr(array_keys($data),$data);

        $payout_account->save();

        return $this->sendSuccess([
            "message"=>__("Your account information has been saved")
        ]);

    }
    public function index(Request $request)
    {
        if(!VendorPayout::isEnable()) {
            return redirect('/');
        }
        $this->checkPermission('admin_payout_manage');

        $query = $this->vendorPayoutClass::query() ;
        $query->orderBy('id', 'desc');

        if($request->query('s'))
        {
            $query->where('id',$request->query('s'));
        }
        if($request->query('vendor_id'))
        {
            $query->where('vendor_id',$request->query('vendor_id'));
        }

        $data = [
            'rows'               => $query->with(['candidate'])->paginate(20),
            'page_title'=>__("Payout Management"),
            'breadcrumbs'        => [
                [
                    'name'  => __('Candidate'),
                    'url' => route('candidate.admin.index')
                ],
                [
                    'name'  => __('Payout Management'),
                    'class' => 'active'
                ],
            ],
            'all_statuses'=>$this->vendorPayoutClass::getAllStatuses()
        ];
        return view('Payout::admin.payouts.index', $data);
    }

    public function bulkEdit(Request $request)
    {
        $this->checkPermission('admin_payout_manage');

        $ids = $request->input('ids');
        $action = $request->input('action');
        if (empty($ids) or !is_array($ids)) {
            return $this->sendError(__('No items selected!'));
        }
        if (empty($action)) {
            return $this->sendError(__('Please select an action!'));
        }

        $all_statuses = $this->vendorPayoutClass::getAllStatuses();

        switch ($action){
            case "delete":
                foreach ($ids as $id) {
                    $query = $this->vendorPayoutClass::find($id);
                    if(!empty($query)){
                        //event(new PayoutRequestEvent('delete',$query));
                        $query->delete();
                    }
                }
                return $this->sendSuccess(__('Deleted success!'));
                break;
            default:
                // Change status
                if(!array_key_exists($action,$all_statuses)){
                    abort(404);
                }
                foreach ($ids as $id) {
                    $payout = $this->vendorPayoutClass::find($id);
                    if($payout){
                        $payout->status = $action;
                        if(\request()->input('pay_date'))
                        {
                            $payout->pay_date = $request->input('pay_date');
                        }
                        if(\request()->input('note_to_vendor'))
                        {
                            $payout->note_to_vendor = $request->input('note_to_vendor');
                        }

                        $payout->save();

                        if($action == 'rejected'){
                            //event(new PayoutRequestEvent('reject',$payout));
                        }else{
                            //event(new PayoutRequestEvent('update',$payout));
                        }
                    }
                }
                return $this->sendSuccess( __('Update success!'));
                break;
        }
    }
}
