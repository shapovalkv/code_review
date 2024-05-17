<?php


namespace Modules\Order\Controllers;


use App\Currency;
use App\Helpers\ReCaptchaEngine;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\FrontendController;
use Modules\Marketplace\Models\Marketplace;
use Modules\Order\Events\PaymentUpdated;
use Modules\Order\Gateways\StripeGateway;
use Modules\Order\Helpers\CartManager;
use Modules\Order\Models\Order;
use Modules\Order\Models\OrderItem;
use Modules\Order\Models\Payment;
use Modules\Order\Requests\ApplyPromocodeRequest;
use Modules\User\Models\Plan;
use Modules\User\Models\Promocode;

class CheckoutController extends FrontendController
{

    public function index()
    {
        $data = [
            'items'           => CartManager::items(),
            'page_title'      => __("Checkout"),
            'hide_newsletter' => true,
            'gateways'        => get_payment_gateway_objects(),
            'user'            => auth()->user()
        ];
        // todo check it
//            if([] !== CartManager::items()->first()->meta && app()->make(CartManager::items()->first()->meta['model']) instanceof Marketplace){
//                $data['announcement'] = Marketplace::find(CartManager::items()->first()->meta['model_id']);
//            }
        return view('Order::frontend.checkout.index', $data);
    }

    public function process(Request $request)
    {
        $user = auth()->user();
        $items = CartManager::items();
        if ($items->isEmpty()) {
            return $this->sendError(__("Your cart is empty"));
        }

        /**
         * Google ReCapcha
         */
        if (ReCaptchaEngine::isEnable() and setting_item("order_enable_recaptcha")) {
            $codeCapcha = $request->input('g-recaptcha-response');
            if (!$codeCapcha or !ReCaptchaEngine::verify($codeCapcha)) {
                return $this->sendError(__("Please verify the captcha"));
            }
        }

        $rules = [
            'first_name'      => 'required|string|max:255',
            'last_name'       => 'required|string|max:255',
            'phone'           => 'required|string|max:255',
            'country'         => 'required',
            'address'         => 'required',
            'zip_code'        => 'required',
            'payment_gateway' => 'required',
            'term_conditions' => 'required',
//                'card_name' => 'required',
        ];
        $payment_gateway = $request->input('payment_gateway');

        $messages = [
            'term_conditions.required' => __('Please read and accept Term conditions'),
            'payment_gateway.required' => __('Please select Payment gateway'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return $this->sendError('', ['errors' => $validator->errors()]);
        }

        DB::beginTransaction();

        $order = CartManager::order();

        $order->gateway = $payment_gateway;
        $billing_data = [
            'first_name' => $request->input('first_name'),
            'last_name'  => $request->input('last_name'),
            'phone'      => $request->input('phone'),
            'country'    => $request->input('country'),
            'address'    => $request->input('address'),
            'address2'   => $request->input('address2'),
            'state'      => $request->input('state'),
            'city'       => $request->input('city'),
            'zip_code'   => $request->input('zip_code'),
        ];
        $order->billing = $billing_data;

        $gateways = get_payment_gateways();

        /** @var StripeGateway $gatewayObj */
        $gatewayObj = new $gateways[$payment_gateway]($payment_gateway);
        if (!empty($rules['payment_gateway'])) {
            if (empty($gateways[$payment_gateway]) or !class_exists($gateways[$payment_gateway])) {
                return $this->sendError(__("Payment gateway not found"));
            }
            if (!$gatewayObj->isAvailable()) {
                return $this->sendError(__("Payment gateway is not available"));
            }
        }

        $order->addMeta('locale', app()->getLocale());

        $payment = new Payment();
        $payment->object_id = $order->id;
        $payment->object_model = 'order';
        $payment->amount = $order->total;
        $payment->currency = Currency::getCurrent();
        $payment->gateway = $payment_gateway;
        $payment->save();

        $order->payment_id = $payment->id;
        $order->save();

        // Save User
        $billing_data = [
            'billing_first_name' => $request->input('first_name'),
            'billing_last_name'  => $request->input('last_name'),
            'phone'              => $request->input('phone'),
            'country'            => $request->input('country'),
            'address'            => $request->input('address'),
            'address2'           => $request->input('address2'),
            'state'              => $request->input('state'),
            'city'               => $request->input('city'),
            'zip_code'           => $request->input('zip_code'),
        ];
        foreach ($billing_data as $k => $v) {
            $user->setAttribute($k, $v);
        }
        $user->save();
// todo
//            if(app()->make($order->items->first()->meta['model']) instanceof Marketplace){
//                $redirectUrl = $order->getDetailannouncementUrl();
//            }else{
        $redirectUrl = $order->getDetailUrl();
//            }

        try {
            if (!$payment->amount) {
                $payment->status = $payment::COMPLETED;
                $payment->save();
                PaymentUpdated::dispatch($payment);
            } else {
                $response = $gatewayObj->process($payment, $redirectUrl);

                if (!isset($response['url'])) {
                    return response()->json($response);
                }

                $redirectUrl = $response['url'];
            }

            DB::commit();
            CartManager::clear();

            return $this->sendSuccess([
                'url' => $redirectUrl
            ]);

        } catch (\Throwable $throwable) {
            return $this->sendError($throwable->getMessage(), $throwable->getTrace());
        }
    }

    public function promocode(ApplyPromocodeRequest $request): RedirectResponse
    {
        /** @var Collection<OrderItem> $items */
        $items = CartManager::items();
        if ($items->whereNotNull('promocode_id')->first()) {
            return redirect()->back()->with('error', __('Promo code was aplied for the order'))->withInput();
        }

        /** @var Promocode $promocode */
        $promocode = Promocode::query()
            ->with(Promocode::RELATION_PLANS)
            ->where('code', $request->input('promocode'))
//            ->whereDoesntHave(Promocode::RELATION_USERS, static function (Builder $builder) {
//                $builder->where('user_id', auth()->user()->id);
//            })
            ->first();

        if (null === $promocode) {
            return redirect()->back()->with('error', __('Promo code not found'))->withInput();
        }

        if (null !== $promocode->users()->where('id', auth()->user()->id)->first()) {
            return redirect()->back()->with('error', __('This promo code has already been redeemed'))->withInput();
        }

        if ($promocode->expiration_date && $promocode->expiration_date->isPast()) {
            return redirect()->back()->with('error', __('This promo code has expired'))->withInput();
        }

        /** @var OrderItem $item */
        if ($promocode->plans !== []) {
            $item = $items->whereIn('object_id', $promocode->plans->pluck('id')->toArray())->first();

            if (null === $item || ((int)($item->meta['annual'] ?? 0) === 1 && !$promocode->is_annual)) {
                return redirect()->back()->with('error', __('Promo code cannot be applied to these products'))->withInput();
            }
        } else {
            $item = $items->first();
        }

        $newPrice = $item->price - ($promocode->is_percent ? $item->price / 100 * $promocode->value : $promocode->value);

        if ($newPrice < 0) {
            $newPrice = 0;
        }

        CartManager::update($item->id, $item->qty, round($newPrice, 2), $item->meta, false, $promocode->id);

        return redirect()->back()->with('success', __('Promo code successfully applied'))->withInput();

    }
}
