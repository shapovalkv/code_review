<?php


namespace Modules\User\Controllers;


use Illuminate\Http\Request;
use Modules\FrontendController;
use Modules\Order\Helpers\CartManager;
use Modules\User\Models\Plan;
use Modules\User\Models\Role;
use Modules\User\Models\UserPlan;

class PlanController extends FrontendController
{

    public function index()
    {
        $user = auth()->user();
        $role = $user ? $user->role : Role::find('employer');
        $data = [
            'page' => [],
            'page_title' => __('Pricing Packages'),
            'plans' => Plan::byRole($role)->visible()->get(),
            'user' => $user,
        ];
        return view("User::frontend.plan.index", $data);
    }

    public function myPlan()
    {
        if (!auth()->user()->user_plan) {
            return redirect(route('user.plan'));
        }
        $data = [
            'page' => [],
            'page_title' => __('My Plan'),
            'user' => auth()->user(),
            'menu_active' => 'my_plan'
        ];
        return view("User::frontend.plan.my-plan", $data);
    }

    public function buy(Request $request, $id)
    {
        $plan = Plan::find($id);
        if (!$plan) return;

        $user = auth()->user();

        $planRoute = route('user.plan');
        $myPlanRoute = route('user.plan');

        if ($user->role_id != $plan->role_id) {
            return redirect()->to($planRoute)->with("warning", __("Please select other plan"));
        }

        $user_plan = $user->user_plan;
//        if($user_plan and $user_plan->plan_id == $plan->id and $user_plan->is_valid){
//            return redirect()->to($plan_page)->with("warning",__("Please select other plan"));
//        }

        if ($request->query('annual') and !$plan->annual_price) {
            return redirect()->to($planRoute)->with("warning", __("This plan doesn't have annual pricing"));
        }

        // For Annual price
        if ($request->query('annual')) {
            CartManager::clear();
            CartManager::add($plan, $plan->title, 1, $plan->annual_price, ['annual' => 1]);
            return redirect()->route('checkout', $request->query());
        }

        // For Normal Price
        if (!$plan->price || $plan->price == 0) {
            // For Free
            $user->applyPlan($plan, $plan->price);
            return redirect()->to($myPlanRoute)->with('success', __("Purchased user package successfully"));
        } else {
            CartManager::clear();
            CartManager::add($plan);
            return redirect()->route('checkout', $request->query());
        }

    }

    public function cancel(Request $request, $id)
    {
        $user = auth()->user();
        if (!$user->user_plan || $id != $user->user_plan->id) {
            abort(403);
        }
        $user->user_plan->cancel();
        return redirect()->back()->with('success', __("The plan successfully cancelled"));
    }
}
