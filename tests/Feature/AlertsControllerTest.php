<?php

namespace Tests\Feature;

use App\Models\Alert;
use App\Models\User;
use Database\Seeders\CountriesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class AlertsControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(CountriesSeeder::class);

        $this->withHeaders([
            'Accept' => 'application/json',
        ]);
    }

    public function test_alerts_can_be_listed()
    {
        $user = User::factory()
            ->has(Alert::factory()->count(3))
            ->create();

        $response = $this->actingAs($user)->get('/alerts');

        $response->assertJsonCount(3);
    }

    public function test_alerts_can_be_created()
    {
        $user = User::factory()->create();

        $data = Alert::factory()->make()->toArray();

        $response = $this->actingAs($user)->post('/alerts', $data);

        $response->assertJson([
            'name' => $data['name'],
        ]);
    }

    /**
     * @group current
     */
    public function test_alerts_cant_be_created_with_invalid_data()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/alerts');

        $response->assertJsonValidationErrors([
            'country_id', 'name', 'keywords', 'city', 'types', 'style',
        ]);

        $response = $this->actingAs($user)->post('/alerts', [
            'country_id' => 'wrong-id',
            'name' => ['wrong-name'],
            'keywords' => ['wrong-keywords'],
            'has_all_keywords' => 'wrong-has-all-keywords',
            'city' => ['wrong-city'],
            'types' => 'wrong-types',
            'style' => ['wrong-style'],
        ]);

        $response->assertJsonValidationErrors([
            'country_id', 'name', 'keywords', 'has_all_keywords', 'city', 'types', 'style',
        ]);

        $data = Alert::factory()->make()->toArray();

        $response = $this->actingAs($user)->post('/alerts', [
            'country_id' => $data['country_id'],
            'name' => Str::random(256),
            'keywords' => $data['keywords'],
            'has_all_keywords' => $data['has_all_keywords'],
            'city' => Str::random(256),
            'types' => [],
            'style' => Str::random(256),
        ]);

        $response->assertJsonValidationErrors([
            'name', 'city', 'types', 'style',
        ]);

        $data = Alert::factory()->make()->toArray();

        $response = $this->actingAs($user)->post('/alerts', [
            'country_id' => $data['country_id'],
            'name' => $data['name'],
            'keywords' => $data['keywords'],
            'has_all_keywords' => $data['has_all_keywords'],
            'city' => $data['city'],
            'types' => [
                [],
                'wrong-type',
            ],
            'style' => $data['style'],
        ]);

        $response->assertJsonValidationErrors([
            'types.0', 'types.1',
        ]);
    }

    public function test_alerts_can_be_updated()
    {
        $user = User::factory()->create();

        $alert = $user->alerts()->create(
            Alert::factory()->make()->toArray()
        );

        $data = Alert::factory()->make()->toArray();

        $response = $this->actingAs($user)->put("/alerts/{$alert->id}", $data);

        $response->assertJson([
            'name' => $data['name'],
        ]);
    }

    public function test_alerts_cant_be_updated_with_invalid_data()
    {
        $user = User::factory()->create();

        $alert = $user->alerts()->create(
            Alert::factory()->make()->toArray()
        );

        $response = $this->actingAs($user)->put("/alerts/{$alert->id}");

        $response->assertJsonValidationErrors([
            'country_id', 'name', 'keywords', 'city', 'types', 'style',
        ]);

        $response = $this->actingAs($user)->put("/alerts/{$alert->id}", [
            'country_id' => 'wrong-id',
            'name' => ['wrong-name'],
            'keywords' => ['wrong-keywords'],
            'has_all_keywords' => 'wrong-has-all-keywords',
            'city' => ['wrong-city'],
            'types' => 'wrong-types',
            'style' => ['wrong-style'],
        ]);

        $response->assertJsonValidationErrors([
            'country_id', 'name', 'keywords', 'has_all_keywords', 'city', 'types', 'style',
        ]);

        $data = Alert::factory()->make()->toArray();

        $response = $this->actingAs($user)->put("/alerts/{$alert->id}", [
            'country_id' => $data['country_id'],
            'name' => Str::random(256),
            'keywords' => $data['keywords'],
            'has_all_keywords' => $data['has_all_keywords'],
            'city' => Str::random(256),
            'types' => [],
            'style' => Str::random(256),
        ]);

        $response->assertJsonValidationErrors([
            'name', 'city', 'types', 'style',
        ]);

        $data = Alert::factory()->make()->toArray();

        $response = $this->actingAs($user)->post("/alerts/{$alert->id}", [
            'country_id' => $data['country_id'],
            'name' => $data['name'],
            'keywords' => $data['keywords'],
            'has_all_keywords' => $data['has_all_keywords'],
            'city' => $data['city'],
            'types' => [
                [],
                'wrong-type',
            ],
            'style' => $data['style'],
        ]);

        $response->assertJsonValidationErrors([
            'types.0', 'types.1',
        ]);
    }

    public function test_alerts_can_be_deleted()
    {
        $user = User::factory()->create();

        $alert = $user->alerts()->create(
            Alert::factory()->make()->toArray()
        );

        $response = $this->actingAs($user)->delete("/alerts/{$alert->id}");

        $response->assertNoContent();
    }
}
