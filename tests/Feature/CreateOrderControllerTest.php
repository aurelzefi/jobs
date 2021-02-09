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

        $company = $user->companies()->save(
            Company::factory()->make()
        );

        $job = $company->jobs()->save(
            Job::factory()->make()
        );

        $data = Order::factory()->make()->toArray();

        $paypalOrder = Mockery::mock(PaypalOrder::class);
        $paypalOrder->shouldReceive('id')->andReturn('fake-id');

        $payment = Mockery::mock(Payment::class);
        $payment->shouldReceive('forOrder')->andReturn($payment);
        $payment->shouldReceive('create')->andReturn($paypalOrder);

        $this->app->instance(Payment::class, $payment);

        $response = $this->actingAs($user)->post("/jobs/{$job->id}/orders", [
            'type' => $data['type'],
        ]);

        $response->assertJson([
            'paypal_order_id' => 'fake-id',
            'amount' => 0,
        ]);
    }

    public function test_paid_orders_can_be_created_after_three_free_orders()
    {
        $user = User::factory()->create();

        $company = $user->companies()->save(
            Company::factory()->make()
        );

        $job = $company->jobs()->save(
            Job::factory()->make()
        );

        $job->orders()->saveMany(
            Order::factory(3)->make([
                'capture_id' => 'fake-capture-id',
                'captured_at' => now(),
            ])
        );

        $data = Order::factory()->make()->toArray();

        $paypalOrder = Mockery::mock(PaypalOrder::class);
        $paypalOrder->shouldReceive('id')->andReturn('fake-id');

        $payment = Mockery::mock(Payment::class);
        $payment->shouldReceive('forOrder')->andReturn($payment);
        $payment->shouldReceive('create')->andReturn($paypalOrder);

        $this->app->instance(Payment::class, $payment);

        $response = $this->actingAs($user)->post("/jobs/{$job->id}/orders", [
            'type' => $data['type'],
        ]);

        $response->assertJson([
            'paypal_order_id' => 'fake-id',
            'amount' => $data['amount'],
        ]);
    }
}
