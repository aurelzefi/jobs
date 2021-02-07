<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Job;
use App\Models\Order;
use App\Models\User;
use Database\Seeders\CountriesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrdersControllerTest extends TestCase
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

    public function test_orders_can_be_listed()
    {
        $user = User::factory()
            ->has(
                Company::factory()
                    ->has(
                        Job::factory()->has(Order::factory()->count(3))
                    )
            )
            ->create();

        $response = $this->actingAs($user)->get('/orders');

        $response->assertJsonCount(3);
    }

    public function test_orders_can_be_shown()
    {
        $user = User::factory()->create();

        $company = $user->companies()->save(
            Company::factory()->make()
        );

        $job = $company->jobs()->save(
            Job::factory()->make()
        );

        $order = $job->orders()->save(
            Order::factory()->make()
        );

        $response = $this->actingAs($user)->get("/orders/{$order->id}");

        $response->assertJson([
            'type' => $order->type,
        ]);
    }
}
