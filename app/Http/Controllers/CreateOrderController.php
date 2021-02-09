<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Job;
use App\Paypal\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class CreateOrderController extends Controller
{
    public function __invoke(OrderRequest $request, Job $job, Payment $payment): JsonResponse
    {
        $this->authorize('update', $job);

        if ($request->creatingFreeOrder() && ! $request->user()->isEligibleForFreeOrder()) {
            throw ValidationException::withMessages([
                'type' => __('You are no longer eligible for a free order.'),
            ]);
        }

        $order = $job->orders()->create([
            'type' => $type = $request->input('type'),
            'amount' => config("app.orders.{$type}"),
        ]);

        $paypalOrder = $payment->forOrder($order)->create();

        $order->fill([
            'paypal_order_id' => $paypalOrder->id(),
        ])->save();

        return response()->json($order);
    }
}
