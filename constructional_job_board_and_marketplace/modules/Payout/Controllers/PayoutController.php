<?php
namespace Modules\Payout\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\AdminController;
use Modules\FrontendController;
use Modules\Location\Models\Location;
use Modules\Payout\Commands\CreatePayoutsCommand;
use Modules\Payout\Models\VendorPayout;
use Modules\Payout\Models\VendorPayoutAccount;
use Illuminate\Support\Facades\Artisan;

class PayoutController extends FrontendController
{
    /**
     * @var VendorPayout
     */
    protected $vendorPayoutClass;

    public function __construct()
    {
        $this->vendorPayoutClass = VendorPayout::class;
        parent::__construct();
    }
    public function candidateIndex(Request $request)
    {
        if(!VendorPayout::isEnable()) {
            return redirect('/');
        }
        $this->checkPermission('candidate_payout_manage');
        $this->setActiveMenu('payout');
        $user = Auth::user();
        $payouts = $this->vendorPayoutClass::query()->select("*")->where('vendor_id',$user->id);
        if(!empty($payout_method = $request->query("payout_method")))
        {
            $payouts = $payouts->where('payout_method',$payout_method);
        }
        $payouts = $payouts->orderBy('id','desc')->paginate(20);

        $data = [
            'page_title'=>__('Payouts'),
            'payouts'=>$payouts,
            'currentUser'=>Auth::user(),
            "current_payout"=>$user->current_payout,
            'payout_account'=>VendorPayoutAccount::where('vendor_id',$user->id)->get(),
            'menu_active' => 'payout'
        ];
        return view("Payout::frontend.index",$data);
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
        foreach ($account_info as $bank => $ac)
        {
            $payout_account = VendorPayoutAccount::where(['vendor_id'=>$user->id,'payout_method'=>$bank])->first();
            if(!$payout_account)
            {
                $payout_account = new VendorPayoutAccount();
                $payout_account->vendor_id = $user->id;
            }

            $payout_account->payout_method = $bank;
            $payout_account->account_info = $ac;
            $payout_account->is_main = $payout_method == $bank ? 1 : 0;
            $payout_account->save();
        }
        return $this->sendSuccess([
            "message"=>__("Your account information has been saved")
        ]);

    }
}
