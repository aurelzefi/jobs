<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(): JsonResponse
    {
        //
    }

    public function store(Request $request): JsonResponse
    {
        //
    }

    public function show(User $user): JsonResponse
    {
        //
    }

    public function update(Request $request, User $user): JsonResponse
    {
        //
    }

    public function destroy(User $user): JsonResponse
    {
        //
    }
}
