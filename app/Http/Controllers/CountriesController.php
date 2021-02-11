<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CountriesController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $countries = (new Country)->getList();

        return response()->json($countries);
    }
}
