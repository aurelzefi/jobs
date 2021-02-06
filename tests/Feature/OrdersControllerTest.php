<?php

namespace Tests\Feature;

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

    public function test_orders_can_be_shown()
    {
        $this->assertTrue(true);
    }
}
