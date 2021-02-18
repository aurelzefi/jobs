<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Job;
use App\Models\Order;
use App\Models\User;
use Database\Seeders\CountriesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateFreeOrderControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(CountriesSeeder::class);

        $this->withHeaders([
            'Accept' => 'application/json',
        ]);
    }

    public function test_free_orders_can_be_created()
    {
        $user = User::factory()->create();
        $company = Company::factory()->for($user)->create();
        $job = Job::factory()->for($company)->create();

        $response = $this->actingAs($user)->post("/api/jobs/{$job->id}/orders/free");

        $response->assertJson([
            'user_id' => $user->id,
            'job_id' => $job->id,
            'amount' => 0,
        ]);
    }

    public function test_free_orders_cant_be_created_if_the_user_is_not_eligible()
    {
        $user = User::factory()->create();
        $company = Company::factory()->for($user)->create();
        $job = Job::factory()->for($company)->create();

        Order::factory(3)->for($job)->for($user)->create([
            'amount' => 0,
            'paid_at' => now(),
        ]);

        $response = $this->actingAs($user)->post("/api/jobs/{$job->id}/orders/free");

        $response->assertJsonValidationErrors('order');
    }
}
