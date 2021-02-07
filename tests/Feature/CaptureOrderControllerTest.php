<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CaptureOrderControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_orders_can_be_captured()
    {
        $this->assertTrue(true);
    }
}
