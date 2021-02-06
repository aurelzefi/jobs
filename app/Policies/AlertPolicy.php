<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Alert;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AlertPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Alert $alert): bool
    {
        return $user->id === $alert->user_id;
    }

    public function update(User $user, Alert $alert): bool
    {
        return $user->id === $alert->user_id;
    }

    public function delete(User $user, Alert $alert): bool
    {
        return $user->id === $alert->user_id;
    }
}
