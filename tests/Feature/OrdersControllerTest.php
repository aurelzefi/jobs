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
        $user = User::factory()->create();
        $company = Company::factory()->for($user)->create();
        $job = Job::factory()->for($company)->create();

        Order::factory(3)->for($job)->create();

        $response = $this->actingAs($user)->get('/api/orders');

        $response->assertJsonCount(3);
    }

    public function test_orders_can_be_shown()
    {
        $user = User::factory()->create();
        $company = Company::factory()->for($user)->create();
        $job = Job::factory()->for($company)->create();
        $order = Order::factory()->for($job)->create();

        $response = $this->actingAs($user)->get("/api/orders/{$order->id}");

        $response->assertJson([
            'type' => $order->type,
        ]);
    }
}
