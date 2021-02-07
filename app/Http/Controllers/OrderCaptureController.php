<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Paypal\Payment;
use Illuminate\Http\JsonResponse;

class OrderCaptureController extends Controller
{
    public function __invoke(Order $order, Payment $payment): JsonResponse
    {
        $this->authorize('capture', $order);

        $paypalOrder = $payment->forOrder($order)->capture();

        $order->fill([
            'capture_id' => $paypalOrder->captureId(),
        ])->save();

        return response()->json($order);
    }
}
