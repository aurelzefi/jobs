<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Alert;
use App\Models\Company;
use App\Models\Job;
use App\Models\Keyword;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(CountriesSeeder::class);

        User::factory()
            ->has($this->seedCompanies())
            ->has($this->seedAlerts())
            ->create([
                'name' => 'Aurel Zefi',
                'email' => 'aurelzefi1994@gmail.com',
            ]);

        for ($i = 0; $i < 10; $i++) {
            User::factory()->has($this->seedCompanies())
                ->has($this->seedAlerts())
                ->create();
        }
    }

    protected function seedCompanies(): Factory
    {
        return Company::factory(rand(0, 3))->has(
            Job::factory(rand(0, 3))->has(Order::factory())
        );
    }

    protected function seedAlerts(): Factory
    {
        return Alert::factory(rand(0, 3))->has(
            Keyword::factory(rand(1, 3))
        );
    }
}
