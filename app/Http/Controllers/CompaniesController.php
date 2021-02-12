<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CompanyRequest;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompaniesController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $companies = $request->user()->companies;

        return response()->json($companies);
    }

    public function store(CompanyRequest $request): JsonResponse
    {
        $company = $request->user()->companies()->create($request->fields());

        return response()->json($company);
    }

    public function show(Company $company): JsonResponse
    {
        return response()->json($company);
    }

    public function update(CompanyRequest $request, Company $company): JsonResponse
    {
        $this->authorize('update', $company);

        if ($request->hasFile('logo') && $company->logo) {
            Storage::disk('public')->delete($company->logo);
        }

        $company->fill($request->fields())->save();

        return response()->json($company);
    }

    public function destroy(Company $company): JsonResponse
    {
        $this->authorize('delete', $company);

        if ($company->logo) {
            Storage::disk('public')->delete($company->logo);
        }

        $company->delete();

        return response()->json([], 204);
    }
}
