<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Job;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'job_id' => Job::factory(),
            'paypal_order_id' => Str::random(),
            'capture_id' => $captureId = $this->faker->randomElement([Str::random(), null]),
            'type' => $type = $this->faker->randomElement(Order::TYPES),
            'amount' => config("app.orders.{$type}"),
            'paid_at' => $captureId ? now() : null,
        ];
    }
}
