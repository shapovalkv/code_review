<?php


namespace Modules\User\Controllers;


use App\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\FrontendController;
use Modules\Marketplace\Services\MarketPlaceService;
use Modules\Order\Events\OrderUpdated;
use Modules\Order\Helpers\CartManager;
use Modules\Order\Models\OrderItem;
use Modules\Order\Models\Order;
use Modules\User\Models\Plan;
use Modules\User\Models\Role;
use Modules\User\Models\UserPlan;

class PlanController extends FrontendController
{

    public function index(): View
    {
        if (auth()->check()) {
            $role = auth()->user()->role;
        } else {
            $role = Role::find('employer');
        }

        $data = [
            'page_title' => __('Employers Subscription Packages'),
            'plans'      => $role->allowedPlans,
            'user'       => auth()->user(),
        ];

        return view('User::frontend.plan.index', $data);
    }

    public function myPlan(MarketPlaceService $marketPlaceService): View|RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        if (!$user->currentUserPlan) {
            return redirect(route('subscription'));
        }

        $data = [
            'page_title'             => __('My Plan'),
            'user'                   => $user,
            'menu_active'            => 'my_plan',
            'publishedAnnouncements' => $marketPlaceService->countAnnouncementsInThisPeriod($user),
        ];

        return view('User::frontend.plan.my-plan', $data);
    }

    public function buy(Request $request, $id): RedirectResponse
    {
        /** @var Plan $plan */
        $plan = Plan::query()->findOrFail($id);

        /** @var User $user */
        $user = auth()->user();

        if ($user->role_id !== $plan->role_id) {
            return redirect()->to(route('subscription'))->with("warning", __("Please select other plan"));
        }
        if ($request->query('annual') && !$plan->annual_price) {
            return redirect()->to(route('subscription'))->with("warning", __("This plan doesn't have annual pricing"));
        }

        CartManager::clear();

        // For Annual price
        if ($request->query('annual')) {
            $meta = ['action' => Plan::TYPE_RECURRING, 'model' => Plan::class, 'model_id' => $plan->id, 'annual' => 1];
            if ((bool)$request->input('start_after_current_plan') === true && $user->currentUserPlan->is_valid) {
                $meta['start_at'] = $user->currentUserPlan->end_date->format('Y-m-d H:i:s');
            }
            CartManager::add($plan, $plan->title, 1, $plan->annual_price, $meta);

            return redirect('checkout');
        }

        // For Normal Price
        if (!$plan->price || $plan->price == 0) {
            // For Free
            if (!empty($currentUserPlan = $user->currentUserPlan)) {
                $currentUserPlan->status = 0;
                $currentUserPlan->save();
            }

            CartManager::add($plan, $plan->title, 1, 0, ['action' => Plan::TYPE_RECURRING, 'model' => Plan::class, 'model_id' => $plan->id]);

            // imitation order for plan reports
            $order = CartManager::order();
            $order->status = Order::COMPLETED;
            $order->save();
            $order->items()->update(['status' => Order::COMPLETED]);

            OrderUpdated::dispatch($order);

            return redirect()->to(route('user.create.job'))->with('success', __("Purchased user package successfully"));
        } else {
            $meta = ['action' => Plan::TYPE_RECURRING, 'model' => Plan::class, 'model_id' => $plan->id];
            if ((bool)$request->input('start_after_current_plan') === true && $user->currentUserPlan->is_valid && $user->currentUserPlan->plan->price) {
                $meta['start_at'] = $user->currentUserPlan->end_date->format('Y-m-d H:i:s');
            }
            CartManager::add($plan, $plan->title, 1, $plan->price, $meta);

            return redirect('checkout');
        }

    }
}
