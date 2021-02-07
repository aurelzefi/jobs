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

class CaptureOrderControllerTest extends TestCase
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

    public function test_orders_can_be_captured()
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

        $paypalOrder = Mockery::mock(PaypalOrder::class);
        $paypalOrder->shouldReceive('captureId')->andReturn('fake-capture-id');

        $payment = Mockery::mock(Payment::class);
        $payment->shouldReceive('forOrder')->andReturn($payment);
        $payment->shouldReceive('capture')->andReturn($paypalOrder);

        $this->app->instance(Payment::class, $payment);

        $response = $this->actingAs($user)->put("/orders/{$order->id}/capture");

        $response->assertJson([
            'capture_id' => 'fake-capture-id',
        ]);
    }
}
