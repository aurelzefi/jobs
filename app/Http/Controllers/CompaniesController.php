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
        $data = $request->only('country_id', 'name', 'description', 'website', 'address', 'city');

        if ($request->hasFile('logo')) {
            $data = array_merge($data, ['logo' => $request->file('logo')->store('images', 'public')]);
        }

        $company = $request->user()->companies()->create($data);

        return response()->json($company);
    }

    public function show(Company $company): JsonResponse
    {
        $this->authorize('view', $company);

        return response()->json($company);
    }

    public function update(CompanyRequest $request, Company $company): JsonResponse
    {
        $this->authorize('update', $company);

        $data = $request->only('country_id', 'name', 'description', 'website', 'address', 'city');

        if ($request->hasFile('logo')) {
            $data = array_merge($data, ['logo' => $request->file('logo')->store('images', 'public')]);

            if ($company->logo) {
                Storage::disk('public')->delete($company->logo);
            }
        }

        $company->fill($data)->save();

        return response()->json($company);
    }
}
