<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\CountriesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserPasswordControllerTest extends TestCase
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

    public function test_user_password_can_be_updated()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/api/user/password', [
            'password' => 'password',
            'new_password' => 'new-password',
            'new_password_confirmation' => 'new-password',
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'new-password',
        ]);

        $this->assertAuthenticated();
    }
}
