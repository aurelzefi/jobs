<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\CountriesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserProfileControllerTest extends TestCase
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

    public function test_user_profile_can_be_updated()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put('/api/user/profile', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $response->assertJson([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
