<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        $this->call(CountriesSeeder::class);

        User::factory()->create([
            'name' => 'Aurel Zefi',
            'email' => 'aurelzefi1994@gmail.com',
        ]);
    }
}
