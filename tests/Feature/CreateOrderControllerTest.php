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

    public function test_orders_can_be_created()
    {
        $this->mockPayment();

        $user = User::factory()->create();
        $company = Company::factory()->for($user)->create();
        $job = Job::factory()->for($company)->create();

        $order = Order::factory()->for($user)->for($job)->make();

        $response = $this->actingAs($user)->post("/api/jobs/{$job->id}/orders", [
            'type' => $order->type,
        ]);

        $response->assertJson([
            'user_id' => $user->id,
            'job_id' => $job->id,
            'paypal_order_id' => 'fake-id',
            'type' => $order->type,
            'amount' => $order->amount,
        ]);
    }

    public function test_orders_can_be_created_if_the_job_expires_today()
    {
        $this->mockPayment();

        $user = User::factory()->create();
        $company = Company::factory()->for($user)->create();
        $job = Job::factory()->for($company)->create();

        Order::factory()->for($job)->for($user)->create([
            'paid_at' => now()->subDays(30),
        ]);

        $order = Order::factory()->for($user)->for($job)->make();

        $response = $this->actingAs($user)->post("/api/jobs/{$job->id}/orders", [
            'type' => $order->type,
        ]);

        $response->assertJson([
            'user_id' => $user->id,
            'job_id' => $job->id,
            'paypal_order_id' => 'fake-id',
            'type' => $order->type,
            'amount' => $order->amount,
        ]);
    }

    public function test_orders_cant_be_created_with_invalid_data()
    {
        $this->mockPayment();

        $user = User::factory()->create();
        $company = Company::factory()->for($user)->create();
        $job = Job::factory()->for($company)->create();

        $response = $this->actingAs($user)->post("/api/jobs/{$job->id}/orders");

        $response->assertJsonValidationErrors(['type']);

        $response = $this->actingAs($user)->post("/api/jobs/{$job->id}/orders", [
            'type' => 'wrong-type',
        ]);

        $response->assertJsonValidationErrors(['type']);
    }

    public function test_orders_cant_be_created_if_the_job_is_active()
    {
        $this->mockPayment();

        $user = User::factory()->create();
        $company = Company::factory()->for($user)->create();
        $job = Job::factory()->for($company)->create();

        Order::factory()->for($job)->for($user)->create([
            'amount' => 0,
            'paid_at' => now(),
        ]);

        $order = Order::factory()->for($user)->for($job)->make();

        $response = $this->actingAs($user)->post("/api/jobs/{$job->id}/orders", [
            'type' => $order->type,
        ]);

        $response->assertJsonValidationErrors('job');
    }

    protected function mockPayment()
    {
        $paypalOrder = Mockery::mock(PaypalOrder::class);
        $paypalOrder->shouldReceive('id')->andReturn('fake-id');

        $payment = Mockery::mock(Payment::class);
        $payment->shouldReceive('withType')->andReturn($payment);
        $payment->shouldReceive('withAmount')->andReturn($payment);

        $payment->shouldReceive('create')->andReturn($paypalOrder);

        $this->app->instance(Payment::class, $payment);
    }
}
