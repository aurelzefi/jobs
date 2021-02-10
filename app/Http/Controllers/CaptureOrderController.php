<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Order;
use App\Paypal\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class CaptureOrderController extends Controller
{
    public function __invoke(Order $order, Payment $payment): JsonResponse
    {
        $this->authorize('capture', $order);

        if ($order->isPaid()) {
            throw ValidationException::withMessages([
                'order' => __('This order has already been captured.'),
            ]);
        }

        $paypalOrder = $payment->forOrder($order)->capture();

        $order->fill([
            'capture_id' => $paypalOrder->captureId(),
            'paid_at' => now(),
        ])->save();

        return response()->json($order);
    }
}
