<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\UserProfileRequest;
use Illuminate\Http\JsonResponse;

class UserProfileController extends Controller
{
    public function __invoke(UserProfileRequest $request): JsonResponse
    {
        $request->user()->fill([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ])->save();

        return response()->json($request->user());
    }
}
