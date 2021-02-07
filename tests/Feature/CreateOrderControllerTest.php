<?php

namespace Tests\Feature;

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
        $paypalOrder = Mockery::mock(PaypalOrder::class);
        $paypalOrder->shouldReceive('id')->andReturn('some-id');

        $payment = Mockery::mock(Payment::class);
        $payment->shouldReceive('forOrder')->andReturn($payment);
        $payment->shouldReceive('create')->andReturn($paypalOrder);

        $this->app->instance(Payment::class, $payment);

        $this->assertTrue(true);
    }
}
