<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Job;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class JobPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        //
    }

    public function view(User $user, Job $job): bool
    {
        //
    }

    public function create(User $user): bool
    {
        //
    }

    public function update(User $user, Job $job): bool
    {
        //
    }

    public function delete(User $user, Job $job): bool
    {
        //
    }

    public function restore(User $user, Job $job): bool
    {
        //
    }

    public function forceDelete(User $user, Job $job): bool
    {
        //
    }
}
