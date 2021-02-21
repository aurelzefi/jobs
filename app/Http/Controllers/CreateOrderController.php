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

        if ($job->isActive() && $job->expiresInFuture()) {
            throw ValidationException::withMessages([
                'job' => __('This job is already active.'),
            ]);
        }

        $type = $request->input('type');
        $amount = config("app.orders.{$type}");

        $paypalOrder = $payment->withType($type)->withAmount($amount)->create();

        $order = $request->user()->orders()->create([
            'job_id' => $job->id,
            'paypal_order_id' => $paypalOrder->id(),
            'type' => $type,
            'amount' => $amount,
        ]);

        return response()->json($order);
    }
}
