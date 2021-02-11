<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\UserPasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UserPasswordController extends Controller
{
    public function __invoke(UserPasswordRequest $request): JsonResponse
    {
        $request->validatePassword();

        $request->user()->fill([
            'password' => Hash::make($request->input('new_password')),
        ])->save();

        return response()->json($request->user());
    }
}
