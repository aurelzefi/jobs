<?php

namespace Tests\Feature;

use App\Models\Alert;
use App\Models\Keyword;
use App\Models\User;
use Database\Seeders\CountriesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class AlertsControllerTest extends TestCase
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

    public function test_alerts_can_be_listed()
    {
        $user = User::factory()->create();

        Alert::factory(3)->for($user)->create();

        $response = $this->actingAs($user)->get('/api/alerts');

        $response->assertJsonCount(3);
    }

    public function test_alerts_can_be_created()
    {
        $user = User::factory()->create();

        $alert = Alert::factory()->for($user)->make();
        $keywords = Keyword::factory(3)->for($alert)->make()->pluck('word')->implode(',');

        $response = $this->actingAs($user)->post('/api/alerts', array_merge($alert->toArray(), ['keywords' => $keywords]));

        $response->assertJson([
            'name' => $alert->name,
        ]);
    }

    public function test_alerts_cant_be_created_with_invalid_data()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/api/alerts');

        $response->assertJsonValidationErrors([
            'country_id', 'name', 'keywords', 'city', 'type', 'job_types', 'job_styles',
        ]);

        $response = $this->actingAs($user)->post('/api/alerts', [
            'country_id' => 'wrong-id',
            'name' => ['wrong-name'],
            'keywords' => ['wrong-keywords'],
            'has_all_keywords' => 'wrong-has-all-keywords',
            'city' => ['wrong-city'],
            'type' => 'wrong-type',
            'job_types' => 'wrong-types',
            'job_styles' => 'wrong-styles',
        ]);

        $response->assertJsonValidationErrors([
            'country_id', 'name', 'keywords', 'has_all_keywords', 'city', 'type', 'job_types', 'job_styles',
        ]);

        $alert = Alert::factory()->for($user)->make();
        $keywords = Keyword::factory(3)->for($alert)->make()->pluck('word')->implode(',');

        $response = $this->actingAs($user)->post('/api/alerts', [
            'country_id' => $alert->country_id,
            'name' => Str::random(256),
            'keywords' => $keywords,
            'has_all_keywords' => $alert->has_all_keywords,
            'city' => Str::random(256),
            'type' => $alert->type,
            'job_types' => [],
            'job_styles' => [],
        ]);

        $response->assertJsonValidationErrors([
            'name', 'city', 'job_types', 'job_styles',
        ]);

        $alert = Alert::factory()->for($user)->make();
        $keywords = Keyword::factory(3)->for($alert)->make()->pluck('word')->implode(',');

        $response = $this->actingAs($user)->post('/api/alerts', [
            'country_id' => $alert->country_id,
            'name' => $alert->name,
            'keywords' => $keywords,
            'has_all_keywords' => $alert->has_all_keywords,
            'city' => $alert->city,
            'type' => $alert->type,
            'job_types' => [
                [],
                'wrong-type',
            ],
            'job_styles' => [
                [],
                'wrong-style',
            ],
        ]);

        $response->assertJsonValidationErrors([
            'job_types.0', 'job_types.1', 'job_styles.0', 'job_styles.1',
        ]);
    }

    public function test_alerts_can_be_shown()
    {
        $user = User::factory()->create();

        $alert = Alert::factory()->for($user)->create();

        $response = $this->actingAs($user)->get("/api/alerts/{$alert->id}");

        $response->assertJson([
            'name' => $alert->name,
        ]);
    }

    public function test_alerts_can_be_updated()
    {
        $user = User::factory()->create();

        $alert = Alert::factory()->for($user)->create();

        $newAlert = Alert::factory()->for($user)->make();
        $keywords = Keyword::factory(3)->for($newAlert)->make()->pluck('word')->implode(',');

        $response = $this->actingAs($user)->put("/api/alerts/{$alert->id}", array_merge($newAlert->toArray(), ['keywords' => $keywords]));

        $response->assertJson([
            'name' => $newAlert->name,
        ]);
    }

    public function test_alerts_cant_be_updated_with_invalid_data()
    {
        $user = User::factory()->create();

        $alert = Alert::factory()->for($user)->create();

        $response = $this->actingAs($user)->put("/api/alerts/{$alert->id}");

        $response->assertJsonValidationErrors([
            'country_id', 'name', 'keywords', 'city', 'type', 'job_types', 'job_styles',
        ]);

        $response = $this->actingAs($user)->put("/api/alerts/{$alert->id}", [
            'country_id' => 'wrong-id',
            'name' => ['wrong-name'],
            'keywords' => ['wrong-keywords'],
            'has_all_keywords' => 'wrong-has-all-keywords',
            'city' => ['wrong-city'],
            'type' => 'wrong-type',
            'job_types' => 'wrong-types',
            'job_styles' => 'wrong-styles',
        ]);

        $response->assertJsonValidationErrors([
            'country_id', 'name', 'keywords', 'has_all_keywords', 'city', 'type', 'job_types', 'job_styles',
        ]);

        $newAlert = Alert::factory()->for($user)->make();
        $keywords = Keyword::factory(3)->for($newAlert)->make()->pluck('word')->implode(',');

        $response = $this->actingAs($user)->put("/api/alerts/{$alert->id}", [
            'country_id' => $newAlert->country_id,
            'name' => Str::random(256),
            'keywords' => $keywords,
            'has_all_keywords' => $newAlert->has_all_keywords,
            'city' => Str::random(256),
            'type' => $newAlert->type,
            'job_types' => [],
            'job_styles' => [],
        ]);

        $response->assertJsonValidationErrors([
            'name', 'city', 'job_types', 'job_styles',
        ]);

        $newAlert = Alert::factory()->for($user)->make();
        $keywords = Keyword::factory(3)->for($newAlert)->make()->pluck('word')->implode(',');

        $response = $this->actingAs($user)->put("/api/alerts/{$alert->id}", [
            'country_id' => $newAlert->country_id,
            'name' => $newAlert->name,
            'keywords' => $keywords,
            'has_all_keywords' => $newAlert->has_all_keywords,
            'city' => $newAlert->city,
            'type' => $newAlert->type,
            'job_types' => [
                [],
                'wrong-type',
            ],
            'job_styles' => [
                [],
                'wrong-style',
            ],
        ]);

        $response->assertJsonValidationErrors([
            'job_types.0', 'job_types.1', 'job_styles.0', 'job_styles.1',
        ]);
    }

    public function test_alerts_can_be_deleted()
    {
        $user = User::factory()->create();
        $alert = Alert::factory()->for($user)->create();

        $response = $this->actingAs($user)->delete("/api/alerts/{$alert->id}");

        $response->assertNoContent();
    }
}
