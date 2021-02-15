<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\CountriesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserLocaleControllerTest extends TestCase
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
    public function test_user_locale_can_be_updated()
    {
        $user = User::factory()->create([
            'locale' => 'al',
        ]);

        $response = $this->actingAs($user)->put('/api/user/locale', [
            'locale' => 'en',
        ]);

        $response->assertJson([
            'locale' => 'en',
        ]);
    }
}
