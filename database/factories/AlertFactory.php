<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Alert;
use App\Models\Country;
use App\Models\Job;
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
            'name' => $this->faker->word,
            'keywords' => $this->fakeKeywords(),
            'has_all_keywords' => $this->faker->randomElement([true, false]),
            'city' => $this->faker->city,
            'types' => $this->faker->randomElements(Job::TYPES, rand(1, 5)),
            'style' => $this->faker->randomElement(Job::STYLES),
        ];
    }

    protected function fakeKeywords(): string
    {
        return implode(',', $this->faker->words(rand(1, 5)));
    }
}
