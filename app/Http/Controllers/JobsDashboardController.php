<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobsDashboardController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $jobs = Job::forInput($request->all())->paginate();

        return response()->json($jobs);
    }
}
