<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Paypal\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CreateOrderController extends Controller
{
    public function __invoke(Request $request, Job $job, Payment $payment): JsonResponse
    {
        $this->authorize('update', $job);

        $order = $job->orders()->create([
            'type' => $request->input('type'),
            'amount' => config('app.orders.'.$request->input('type')),
        ]);

        $paypalOrder = $payment->forOrder($order)->create();

        $order->fill([
            'paypal_order_id' => $paypalOrder->id(),
        ])->save();

        return response()->json($order);
    }
}
