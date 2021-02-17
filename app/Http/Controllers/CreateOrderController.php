<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Job;
use App\Models\Order;
use App\Paypal\Payment;
use Illuminate\Http\JsonResponse;

class CreateOrderController extends Controller
{
    public function __invoke(OrderRequest $request, Job $job, Payment $payment): JsonResponse
    {
        $this->authorize('update', $job);

        $order = $this->createOrder($request, $job, $payment);

        return response()->json($order);
    }

    protected function createOrder(OrderRequest $request, Job $job, Payment $payment): Order
    {
        if ($request->user()->freeOrdersLeft() > 0 && $request->creatingFreeOrder()) {
            return $this->createFreeOrder($job);
        }

        return $this->createPaidOrder($job, $payment, $request->input('type'));
    }

    protected function createFreeOrder(Job $job): Order
    {
        return $job->orders()->create([
            'type' => Order::TYPE_BASIC,
            'amount' => 0,
            'paid_at' => now(),
        ]);
    }

    protected function createPaidOrder(Job $job, Payment $payment, string $type): Order
    {
        $amount = config("app.orders.{$type}");

        $order = $payment->withType($type)->withAmount($amount)->create();

        return $job->orders()->create([
            'paypal_order_id' => $order->id(),
            'type' => $type,
            'amount' => $amount,
        ]);
    }
}
