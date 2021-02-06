<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Job;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class JobPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Job $job): bool
    {
        return $user->id === $job->company->user_id;
    }

    public function update(User $user, Job $job): bool
    {
        return $user->id === $job->company->user_id;
    }
}
