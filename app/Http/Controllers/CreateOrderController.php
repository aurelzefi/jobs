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

        $type = $request->input('type');
        $amount = config("app.orders.{$type}");

        $order = $payment->withType($type)->withAmount($amount)->create();

        $order = $job->orders()->create([
            'paypal_order_id' => $order->id(),
            'type' => $type,
            'amount' => $amount,
        ]);

        return response()->json($order);
    }
}
