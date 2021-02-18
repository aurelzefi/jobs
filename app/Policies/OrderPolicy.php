<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Order $order): bool
    {
        return $user->id === $order->user_id;
    }

    public function capture(User $user, Order $order): bool
    {
        return $user->id === $order->user_id;
    }
}
