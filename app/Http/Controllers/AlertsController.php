<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\AlertRequest;
use App\Models\Alert;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AlertsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $alerts = $request->user()->alerts;

        return response()->json($alerts);
    }

    public function store(AlertRequest $request): JsonResponse
    {
        dd(1);

        $alert = $request->user()->alerts()->create([
            'country_id' => $request->input('country_id'),
            'name' => $request->input('name'),
            'keywords' => $request->input('keywords'),
            'has_all_keywords' => $request->has('has_all_keywords'),
            'city' => $request->input('city'),
            'types' => $request->input('types'),
            'style' => $request->input('style'),
        ]);

        return response()->json($alert);
    }

    public function show(Alert $alert): JsonResponse
    {
        $this->authorize('view', $alert);

        return response()->json($alert);
    }

    public function update(AlertRequest $request, Alert $alert): JsonResponse
    {
        $this->authorize('update', $alert);

        $alert->fill([
            'country_id' => $request->input('country_id'),
            'name' => $request->input('name'),
            'keywords' => $request->input('keywords'),
            'has_all_keywords' => $request->has('has_all_keywords'),
            'city' => $request->input('city'),
            'types' => $request->input('types'),
            'style' => $request->input('style'),
        ])->save();

        return response()->json($alert);
    }

    public function destroy(Alert $alert): JsonResponse
    {
        $this->authorize('delete', $alert);

        $alert->delete();

        return response()->json([], 204);
    }
}
