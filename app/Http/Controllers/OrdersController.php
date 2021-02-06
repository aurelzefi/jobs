<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $orders = Order::forUser($request->user())->get();

        return response()->json($orders);
    }

    public function store(OrderRequest $request): JsonResponse
    {
        $order = Order::create($request->only('job_id', 'type'));

        return response()->json($order);
    }

    public function show(Order $order): JsonResponse
    {
        $this->authorize('view', $order);

        return response()->json($order);
    }
}
