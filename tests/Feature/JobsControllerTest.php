<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Job;
use App\Models\Order;
use App\Models\User;
use App\Paypal\Payment;
use App\Paypal\Response;
use Database\Seeders\CountriesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class JobsControllerTest extends TestCase
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

    public function test_jobs_can_be_created()
    {
        $user = User::factory()->create();

        $company = $user->companies()->save(
            Company::factory()->make()
        );

        $data = array_merge(
            Job::factory()->make(['company_id' => $company,])->toArray(),
            ['order_type' => Order::factory()->make()->type]
        );

        $response = Mockery::mock(Response::class);
        $response->shouldReceive('id')->andReturn('some-id');

        $payment = Mockery::mock(Payment::class);
        $payment->shouldReceive('forOrder')->andReturn($payment);
        $payment->shouldReceive('create')->andReturn($response);

        $this->app->instance(Payment::class, $payment);

        $response = $this->actingAs($user)->post('/jobs', $data);

        $response->assertJson([
            'title' => $data['title'],
        ]);
    }
}
