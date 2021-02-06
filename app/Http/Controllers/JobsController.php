<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\JobRequest;
use App\Models\Job;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $jobs = $request->user()->jobs;

        return response()->json($jobs);
    }

    public function store(JobRequest $request): JsonResponse
    {
        $job = $request->user()->jobs()->create(
            $request->only('company_id', 'country_id', 'title', 'description', 'city', 'type', 'style')
        );

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
