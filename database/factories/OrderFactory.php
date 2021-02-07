<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Job;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'job_id' => Job::factory(),
            'paypal_order_id' => Str::random(),
            'capture_id' => $this->faker->randomElement([Str::random(), null]),
            'type' => $type = $this->faker->randomElement(Order::TYPES),
            'amount' => config("app.orders.{$type}"),
        ];
    }
}
