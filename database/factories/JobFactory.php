<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Company;
use App\Models\Country;
use App\Models\Job;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobFactory extends Factory
{
    protected $model = Job::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'country_id' => Country::query()->inRandomOrder()->first(),
            'title' => $this->faker->words(rand(1, 3), true),
            'description' => $this->faker->paragraphs(10, true),
            'city' => $this->faker->city,
            'type' => $this->faker->randomElement(Job::TYPES),
            'style' => $this->faker->randomElement(Job::STYLES),
        ];
    }
}
