<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Job;
use App\Models\Order;
use App\Models\User;
use App\Paypal\Order as PaypalOrder;
use App\Paypal\Payment;
use Database\Seeders\CountriesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class CreateOrderControllerTest extends TestCase
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

        $order = Order::factory()->for($job)->make([
            'type' => Order::ORDER_TYPE_FREE,
        ]);

        $this->mockPayment();

        $response = $this->actingAs($user)->post("/jobs/{$job->id}/orders", [
            'type' => $order->type,
        ]);

        $response->assertJson([
            'paypal_order_id' => 'fake-id',
            'amount' => 0,
        ]);
    }

    public function test_paid_orders_can_be_created_after_three_free_orders()
    {
        $user = User::factory()->create();

        $company = Company::factory()->for($user)->create();

        $job = Job::factory()->for($company)->create();

        Order::factory(3)->for($job)->create([
            'type' => Order::ORDER_TYPE_FREE,
            'capture_id' => 'fake-capture-id',
            'captured_at' => now(),
        ]);

        $order = Order::factory()->for($job)->make([
            'type' => $type = Order::ORDER_TYPE_BASIC,
            'amount' => config("app.orders.{$type}"),
        ]);

        $this->mockPayment();

        $response = $this->actingAs($user)->post("/jobs/{$job->id}/orders", [
            'type' => $order->type,
        ]);

        $response->assertJson([
            'paypal_order_id' => 'fake-id',
            'amount' => $order->amount,
        ]);
    }

    public function test_orders_cant_be_created_with_invalid_data()
    {
        $user = User::factory()->create();

        $company = Company::factory()->for($user)->create();

        $job = Job::factory()->for($company)->create();

        $response = $this->actingAs($user)->post("/jobs/{$job->id}/orders");

        $response->assertJsonValidationErrors(['type']);

        Order::factory()->for($job)->create([
            'type' => 'wrong-type',
        ]);

        $response = $this->actingAs($user)->post("/jobs/{$job->id}/orders");

        $response->assertJsonValidationErrors(['type']);

        Order::factory(3)->for($job)->create([
            'type' => Order::ORDER_TYPE_FREE,
            'capture_id' => 'fake-capture-id',
            'captured_at' => now(),
        ]);

        $order = Order::factory()->for($job)->make([
            'type' => Order::ORDER_TYPE_FREE,
        ]);

        $this->mockPayment();

        $response = $this->actingAs($user)->post("/jobs/{$job->id}/orders", [
            'type' => $order->type,
        ]);

        $response->assertJsonValidationErrors(['type']);
    }

    protected function mockPayment()
    {
        $paypalOrder = Mockery::mock(PaypalOrder::class);
        $paypalOrder->shouldReceive('id')->andReturn('fake-id');

        $payment = Mockery::mock(Payment::class);
        $payment->shouldReceive('forOrder')->andReturn($payment);
        $payment->shouldReceive('create')->andReturn($paypalOrder);

        $this->app->instance(Payment::class, $payment);
    }
}
