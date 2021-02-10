<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Job;
use App\Paypal\Payment;
use Illuminate\Http\JsonResponse;

class CreateOrderController extends Controller
{
    public function __invoke(OrderRequest $request, Job $job, Payment $payment): JsonResponse
    {
        $this->authorize('update', $job);

        $order = $job->orders()->create([
            'type' => $type = $request->input('type'),
            'amount' => $this->getOrderAmount($request),
        ]);

        $paypalOrder = $payment->forOrder($order)->create();

        $order->fill([
            'paypal_order_id' => $paypalOrder->id(),
        ])->save();

        return response()->json($order);
    }

    protected function getOrderAmount(OrderRequest $request)
    {
        if ($request->user()->isEligibleForFreeOrder() && $request->creatingBasicOrder()) {
            return 0;
        }

        return config("app.orders.{$request->input('type')}");
    }
}
