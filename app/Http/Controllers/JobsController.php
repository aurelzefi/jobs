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
        $job = $request->user()->jobs()->create([
            'company_id' => $request->input('company_id'),
            'country_id' => $request->input('country_id'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'city' => $request->input('city'),
            'type' => $request->input('type'),
            'style' => $request->input('style'),
        ]);

        event(new JobCreated($job));

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

        $job->fill([
            'company_id' => $request->input('company_id'),
            'country_id' => $request->input('country_id'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'city' => $request->input('city'),
            'type' => $request->input('type'),
            'style' => $request->input('style'),
        ])->save();

        return response()->json($job);
    }
}
