<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Alert;
use App\Models\Company;
use App\Models\Job;
use App\Models\Keyword;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(CountriesSeeder::class);

        $user = User::factory()->create([
            'name' => 'Aurel Zefi',
            'email' => 'aurelzefi1994@gmail.com',
        ]);

        $this->seedForUser($user);

        User::factory(10)->create()->each(function (User $user) {
            $this->seedForUser($user);
        });
    }

    protected function seedForUser(User $user): void
    {
        $companies = Company::factory(rand(0, 5))->for($user)->create();

        $companies->each(function (Company $company) use ($user) {
            $jobs = Job::factory(rand(0, 5))->for($company)->create();

            $jobs->each(function (Job $job) use ($user) {
                Order::factory(rand(0, 5))->for($user)->for($job)->create();
            });
        });

        $alerts = Alert::factory(rand(0, 5))->for($user)->create();

        $alerts->each(function (Alert $alert) {
            Keyword::factory(rand(0, 5))->for($alert)->create();
        });
    }
}
