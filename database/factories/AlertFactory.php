<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Alert;
use App\Models\Country;
use App\Models\Job;
use App\Models\Keyword;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AlertFactory extends Factory
{
    protected $model = Alert::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'country_id' => Country::query()->inRandomOrder()->first(),
            'name' => $this->faker->words(rand(1, 3), true),
            'has_all_keywords' => $this->faker->randomElement([true, false]),
            'city' => $this->faker->city,
            'type' => $this->faker->randomElement(['instant', 'weekly']),
            'job_types' => $this->faker->randomElements(Job::TYPES, rand(1, 5)),
            'job_styles' => $this->faker->randomElements(Job::STYLES, rand(1, 3)),
        ];
    }
}
