<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Paypal\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $orders = Order::forUser($request->user())->get();

        return response()->json($orders);
    }

    public function show(Order $order): JsonResponse
    {
        $this->authorize('view', $order);

        return response()->json($order);
    }

    public function capture(Order $order, Payment $payment): JsonResponse
    {
        $this->authorize('capture', $order);

        $payload = $payment->forOrder($order)->create();

        $order->fill([
            'paypal_order_id' => $payload['id'],
        ])->save();

        return response()->json($order);
    }
}
