<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\JobRequest;
use App\Models\Job;
use App\Models\Order;
use App\Paypal\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $jobs = $request->user()->jobs;

        return response()->json($jobs);
    }

    public function store(JobRequest $request, Payment $payment): JsonResponse
    {
        $job = $request->user()->jobs()->create(
            $request->only('company_id', 'country_id', 'title', 'description', 'city', 'type', 'style')
        );

        $order = $job->orders()->create([
            'type' => $request->input('order_type'),
            'amount' => config('app.orders.'.$request->input('order_type')),
        ]);

        $payload = $payment->forOrder($order)->create();

        $order->fill([
            'paypal_order_id' => $payload['id'],
        ])->save();

        return response()->json($job);
    }

    public function show(Job $job): JsonResponse
    {
        $this->authorize('view', $job);

        return response()->json($job);
    }

    public function update(JobRequest $request, Job $job): JsonResponse
    {
        $this->authorize('update', $job);

        $job->fill(
            $request->only('company_id', 'country_id', 'title', 'description', 'city', 'type', 'style')
        )->save();

        return response()->json($job);
    }
}
