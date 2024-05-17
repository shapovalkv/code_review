<?php


namespace Modules\Order\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\FrontendController;
use Modules\Order\Models\Order;
use Modules\Order\Models\OrderItem;

class OrderController extends FrontendController
{
    public function detail($id)
    {
        $order = Order::findOrFail($id);
        if ($order->customer_id != auth()->id()) {
            abort(404);
        }

        $data = [
            'page' => [],
            'row' => $order,
            'page_title' => __("Order Detail"),
            'hide_newsletter' => true,
        ];
        return view('Order::frontend.detail', $data);
    }

    public function confirmPayment(Request $request, $gateway)
    {

        $gateways = get_payment_gateways();
        if (empty($gateways[$gateway]) or !class_exists($gateways[$gateway])) {
            $this->sendError(__("Payment gateway not found"));
        }
        $gatewayObj = new $gateways[$gateway]($gateway);
        if (!$gatewayObj->isAvailable()) {
            $this->sendError(__("Payment gateway is not available"));
        }
        return $gatewayObj->confirmPayment($request);
    }

    public function cancelPayment(Request $request, $gateway)
    {

        $gateways = get_payment_gateways();
        if (empty($gateways[$gateway]) or !class_exists($gateways[$gateway])) {
            $this->sendError(__("Payment gateway not found"));
        }
        $gatewayObj = new $gateways[$gateway]($gateway);
        if (!$gatewayObj->isAvailable()) {
            $this->sendError(__("Payment gateway is not available"));
        }
        return $gatewayObj->cancelPayment($request);
    }

    public function history(Request $request)
    {
        $rows = OrderItem::query()
            ->with(['order'])
            ->where('create_user', Auth::id())
            ->paginate(20);
        $data = [
            'page' => __('Order History'),
            'rows' => $rows,
            'page_title' => __("My Orders"),
            'menu_active' => 'my_orders'
        ];

        return view('Order::frontend.user.history', $data);
    }

}
