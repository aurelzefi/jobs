<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Alert;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AlertsController extends Controller
{
    public function index(): JsonResponse
    {
        //
    }

    public function store(Request $request): JsonResponse
    {
        //
    }

    public function show(Alert $alert): JsonResponse
    {
        //
    }

    public function update(Request $request, Alert $alert): JsonResponse
    {
        //
    }

    public function destroy(Alert $alert): JsonResponse
    {
        //
    }
}
