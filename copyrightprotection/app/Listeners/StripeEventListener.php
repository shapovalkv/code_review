<?php

namespace App\Listeners;

use App\Models\Subscription;
use App\Models\UserProject;
use App\Services\StripeService;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Events\WebhookReceived;
use Stripe\Event;

class StripeEventListener
{
    public function handle(WebhookReceived $event): \Illuminate\Http\JsonResponse
    {
        switch ($event->payload['type']) {
            case 'subscription_schedule.canceled':
            case 'invoice.payment_failed':
            case 'customer.subscription.deleted':
                $subscription = Subscription::where('stripe_id', $event->payload['data']['object']['id'])->first();
                $userProject = $subscription->userProject;
                $userProject->status = UserProject::IN_ACTIVE;
                $userProject->save();
                break;
            default:
                return response()->json(['message' => 'Unhandled event'], 200);
        }

        return response()->json(['message' => 'Event handled'], 200);
    }
}
