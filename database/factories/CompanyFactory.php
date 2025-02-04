<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Company;
use App\Models\Country;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    protected $fakeLogo = false;

    public function definition(): array
    {
        $this->ensureImagesDirectoryExists();

        return [
            'user_id' => User::factory(),
            'country_id' => Country::query()->inRandomOrder()->first(),
            'name' => $this->faker->company,
            'logo' => $this->logoPath(),
            'description' => $this->faker->text,
            'website' => $this->faker->randomElement([$this->faker->url, null]),
            'address' => $this->faker->address,
            'city' => $this->faker->city,
        ];
    }

    protected function ensureImagesDirectoryExists(): void
    {
        if(! Storage::disk('public')->exists('images')) {
            Storage::disk('public')->makeDirectory('images');
        }
    }

    public function withLogo(): self
    {
        $this->fakeLogo = true;

        return $this;
    }

    protected function logoPath()
    {
        if ($this->fakeLogo) {
            return 'images/'.$this->faker->image(storage_path('app/public/images'), 640, 480, null, false);
        }
    }
}
