<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\UserLocaleRequest;
use Illuminate\Http\JsonResponse;

class UserLocaleController extends Controller
{
    public function __invoke(UserLocaleRequest $request): JsonResponse
    {
        $request->user()->fill([
            'locale' => $request->input('locale'),
        ])->save();

        return response()->json($request->user());
    }
}
