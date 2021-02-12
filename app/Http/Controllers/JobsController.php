<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Events\JobCreated;
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
        $job = $request->user()->jobs()->create($request->fields());

        event(new JobCreated($job));

        return response()->json($job);
    }

    public function show(Job $job): JsonResponse
    {
        return response()->json($job);
    }

    public function update(JobRequest $request, Job $job): JsonResponse
    {
        $this->authorize('update', $job);

        $job->fill($request->fields())->save();

        return response()->json($job);
    }

    public function destroy(Job $job): JsonResponse
    {
        $this->authorize('delete', $job);

        $job->delete();

        return response()->json([], 204);
    }
}
