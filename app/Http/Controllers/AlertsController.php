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
        $alert = $request->user()->alerts()->create(
            $request->only('country_id', 'name', 'keywords', 'has_all_keywords', 'city', 'types', 'style')
        );

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

        $alert->fill(
            $request->only('country_id', 'name', 'keywords', 'has_all_keywords', 'city', 'types', 'style')
        )->save();

        return response()->json($alert);
    }

    public function destroy(Alert $alert): JsonResponse
    {
        $this->authorize('delete', $alert);

        $alert->delete();

        return response()->json([], 204);
    }
}
