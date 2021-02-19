<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AllJobsController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $jobs = Job::with(['company', 'country'])->forInput($request->all())->paginate();

        return response()->json($jobs);
    }
}
