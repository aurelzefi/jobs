<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompaniesController extends Controller
{
    public function index(): JsonResponse
    {
        //
    }

    public function store(Request $request): JsonResponse
    {
        //
    }

    public function show(Company $company): JsonResponse
    {
        //
    }

    public function update(Request $request, Company $company): JsonResponse
    {
        //
    }

    public function destroy(Company $company): JsonResponse
    {
        //
    }
}
