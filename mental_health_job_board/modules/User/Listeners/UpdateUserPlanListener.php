<?php


namespace Modules\User\Listeners;


use App\User;
use Illuminate\Support\Carbon;
use Modules\Marketplace\Models\Marketplace;
use Modules\Order\Events\OrderUpdated;
use Modules\Order\Models\Order;
use Modules\User\Events\UserBoughtPremierUserPlan;
use Modules\User\Events\UserSponsoringAnnouncement;
use Modules\User\Models\Plan;

class UpdateUserPlanListener
{
    /**
     * Handle the event.
     *
     * @param $event OrderUpdated
     * @return void
     */
    public function handle(OrderUpdated $event)
    {
        $order = $event->_order;
        switch ($order->status) {
            case "completed":
                foreach ($order->items as $item) {
                    switch ($item->object_model) {
                        case "plan":
                            /** @var Plan|null $plan */
                            $plan = $item->model();
                            if ($plan) {
                                /** @var User $user */
                                $user = $order->customer;
                                $currentUserPlan = $user->currentUserPlan;
                                $startFrom = !empty($item->meta['start_at']) ? Carbon::parse($item->meta['start_at']) : null;
                                if ($plan->plan_type === Plan::TYPE_RECURRING && !empty($currentUserPlan) && (!$startFrom || $startFrom->isPast())) {
                                    $currentUserPlan->status = 0;
                                    $currentUserPlan->save();
                                }
                                $user->applyPlan(
                                    $plan,
                                    $item->price,
                                    (bool)($item->meta['annual'] ?? false),
                                    $startFrom
                                );

                                if (!empty($item->meta['model']) && $item->meta['model'] === Marketplace::class) {
                                    /** @var Marketplace $marketPlace */
                                    $marketPlace = Marketplace::query()->find($item->meta['model_id']);
                                    $marketPlace->publish($plan->expiration_announcement_time);
                                    event(new UserSponsoringAnnouncement($user, $marketPlace));
                                }

                                if ($user->hasRole('employer') && $plan->plan_type === Plan::TYPE_RECURRING) {
                                    $user->company->job()->update(['expiration_date' => Carbon::now()->addDays($plan->expiration_job_time)]);
                                }

                                if (!empty($plan->best_value)) {
                                    event(new UserBoughtPremierUserPlan($user, $plan));
                                }
                            }
                            break;
                    }
                }
                break;
        }

    }
}
