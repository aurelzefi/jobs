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
        $alert = $request->user()->alerts()->create($request->fields());

        $alert->keywords()->createMany(
            collect($request->keywords())->map(function ($word) {
                return ['word' => $word];
            })
        );

        return response()->json($alert);
    }

    public function show(Alert $alert): JsonResponse
    {
        $this->authorize('view', $alert);

        return response()->json($alert->load('keywords'));
    }

    public function update(AlertRequest $request, Alert $alert): JsonResponse
    {
        $this->authorize('update', $alert);

        $alert->fill($request->fields())->save();

        $keywords = collect($request->keywords())
            ->diff($alert->keywords()->pluck('word'))
            ->map(function ($keyword) {
                return ['word' => $keyword];
            });

        $alert->keywords()->createMany($keywords);

        $alert->keywords()->whereNotIn('word', $request->keywords())->delete();

        return response()->json($alert);
    }

    public function destroy(Alert $alert): JsonResponse
    {
        $this->authorize('delete', $alert);

        $alert->delete();

        return response()->json([], 204);
    }
}
