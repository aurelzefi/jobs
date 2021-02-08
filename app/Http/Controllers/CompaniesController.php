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
        $company = $request->user()->companies()->create([
            'country_id' => $request->input('country_id'),
            'name' => $request->input('name'),
            'logo' => $this->logo($request),
            'description' => $request->input('description'),
            'website' => $request->input('website'),
            'address' => $request->input('address'),
            'city' => $request->input('city'),
        ]);

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

        if ($request->hasFile('logo') && $company->logo) {
            Storage::disk('public')->delete($company->logo);
        }

        $company->fill([
            'country_id' => $request->input('country_id'),
            'name' => $request->input('name'),
            'logo' => $this->logo($request),
            'description' => $request->input('description'),
            'website' => $request->input('website'),
            'address' => $request->input('address'),
            'city' => $request->input('city'),
        ])->save();

        return response()->json($company);
    }

    protected function logo(Request $request)
    {
        if ($request->hasFile('logo')) {
            return $request->file('logo')->store('images', 'public');
        }
    }
}
