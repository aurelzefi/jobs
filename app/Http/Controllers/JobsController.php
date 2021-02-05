<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobsController extends Controller
{
    public function index(): JsonResponse
    {
        //
    }

    public function store(Request $request): JsonResponse
    {
        //
    }

    public function show(Job $job): JsonResponse
    {
        //
    }

    public function update(Request $request, Job $job): JsonResponse
    {
        //
    }

    public function destroy(Job $job): JsonResponse
    {
        //
    }
}
