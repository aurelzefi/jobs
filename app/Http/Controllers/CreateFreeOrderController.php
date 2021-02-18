<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CreateFreeOrderController extends Controller
{
    public function __invoke(Request $request, Job $job): JsonResponse
    {
        if ($request->user()->hasNoFreeOrdersLeft()) {
            throw ValidationException::withMessages([
                'order' => __('You are no longer eligible for a free order.'),
            ]);
        }

        $order = $job->orders()->create([
            'type' => Order::TYPE_BASIC,
            'amount' => 0,
            'paid_at' => now(),
        ]);

        return response()->json($order);
    }
}
