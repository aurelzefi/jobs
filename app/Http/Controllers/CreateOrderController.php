<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Order;
use App\Paypal\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CreateOrderController extends Controller
{
    public function __invoke(Request $request, Job $job, Payment $payment): JsonResponse
    {
        $this->authorize('update', $job);

        $order = $job->orders()->create(
            $this->determineOrderTypeAndAmount($request)
        );

        $paypalOrder = $payment->forOrder($order)->create();

        $order->fill([
            'paypal_order_id' => $paypalOrder->id(),
        ])->save();

        return response()->json($order);
    }

    protected function determineOrderTypeAndAmount(Request $request): array
    {
        if ($request->user()->isEligibleForFreeOrder()) {
            return [
                'type' => Order::ORDER_TYPE_FREE,
                'amount' => 0,
            ];
        }

        return [
            'type' => $type = $request->input('type'),
            'amount' => config("app.orders.{$type}"),
        ];
    }
}
