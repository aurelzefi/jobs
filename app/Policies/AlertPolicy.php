<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Alert;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AlertPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        //
    }

    public function view(User $user, Alert $alert): bool
    {
        //
    }

    public function create(User $user): bool
    {
        //
    }

    public function update(User $user, Alert $alert): bool
    {
        //
    }

    public function delete(User $user, Alert $alert): bool
    {
        //
    }

    public function restore(User $user, Alert $alert): bool
    {
        //
    }

    public function forceDelete(User $user, Alert $alert): bool
    {
        //
    }
}
