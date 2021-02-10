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

        $company = Company::factory()->for($user)->create();

        $job = Job::factory()->for($company)->create();

        $order = Order::factory()->for($job)->create([
            'capture_id' => null,
            'captured_at' => null,
        ]);

        $this->mockPayment();

        $response = $this->actingAs($user)->put("/api/orders/{$order->id}/capture");

        $response->assertJson([
            'capture_id' => 'fake-capture-id',
        ]);
    }

    public function test_orders_can_only_be_captured_once()
    {
        $user = User::factory()->create();

        $company = Company::factory()->for($user)->create();

        $job = Job::factory()->for($company)->create();

        $order = Order::factory()->for($job)->create([
            'capture_id' => 'fake-capture-id',
            'captured_at' => now(),
        ]);

        $this->mockPayment();

        $response = $this->actingAs($user)->put("/api/orders/{$order->id}/capture");

        $response->assertJsonValidationErrors(['order']);
    }

    protected function mockPayment()
    {
        $paypalOrder = Mockery::mock(PaypalOrder::class);
        $paypalOrder->shouldReceive('captureId')->andReturn('fake-capture-id');

        $payment = Mockery::mock(Payment::class);
        $payment->shouldReceive('forOrder')->andReturn($payment);
        $payment->shouldReceive('capture')->andReturn($paypalOrder);

        $this->app->instance(Payment::class, $payment);

    }
}
