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
        $keywords = Keyword::factory(3)->make()->pluck('word')->implode(',');

        $response = $this->actingAs($user)->post('/alerts', array_merge($data, ['keywords' => $keywords]));

        $response->assertJson([
            'name' => $data['name'],
        ]);
    }

    public function test_alerts_cant_be_created_with_invalid_data()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/alerts');

        $response->assertJsonValidationErrors([
            'country_id', 'name', 'keywords', 'city', 'type', 'job_types', 'job_styles',
        ]);

        $response = $this->actingAs($user)->post('/alerts', [
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

        $data = Alert::factory()->make()->toArray();
        $keywords = Keyword::factory(3)->make()->pluck('word')->implode(',');

        $response = $this->actingAs($user)->post('/alerts', [
            'country_id' => $data['country_id'],
            'name' => Str::random(256),
            'keywords' => $keywords,
            'has_all_keywords' => $data['has_all_keywords'],
            'city' => Str::random(256),
            'type' => $data['type'],
            'job_types' => [],
            'job_styles' => [],
        ]);

        $response->assertJsonValidationErrors([
            'name', 'city', 'job_types', 'job_styles',
        ]);

        $data = Alert::factory()->make()->toArray();
        $keywords = Keyword::factory(3)->make()->pluck('word')->implode(',');

        $response = $this->actingAs($user)->post('/alerts', [
            'country_id' => $data['country_id'],
            'name' => $data['name'],
            'keywords' => $keywords,
            'has_all_keywords' => $data['has_all_keywords'],
            'city' => $data['city'],
            'type' => $data['type'],
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

        $alert = $user->alerts()->save(
            Alert::factory()->make()
        );

        $response = $this->actingAs($user)->get("/alerts/{$alert->id}");

        $response->assertJson([
            'name' => $alert->name,
        ]);
    }

    public function test_alerts_can_be_updated()
    {
        $user = User::factory()->create();

        $alert = $user->alerts()->save(
            Alert::factory()->make()
        );

        $data = Alert::factory()->make()->toArray();
        $keywords = Keyword::factory(3)->make()->pluck('word')->implode(',');

        $response = $this->actingAs($user)->put("/alerts/{$alert->id}", array_merge($data, ['keywords' => $keywords]));

        $response->assertJson([
            'name' => $data['name'],
        ]);
    }

    public function test_alerts_cant_be_updated_with_invalid_data()
    {
        $user = User::factory()->create();

        $alert = $user->alerts()->save(
            Alert::factory()->make()
        );

        $response = $this->actingAs($user)->put("/alerts/{$alert->id}");

        $response->assertJsonValidationErrors([
            'country_id', 'name', 'keywords', 'city', 'type', 'job_types', 'job_styles',
        ]);

        $response = $this->actingAs($user)->put("/alerts/{$alert->id}", [
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

        $data = Alert::factory()->make()->toArray();
        $keywords = Keyword::factory(3)->make()->pluck('word')->implode(',');

        $response = $this->actingAs($user)->put("/alerts/{$alert->id}", [
            'country_id' => $data['country_id'],
            'name' => Str::random(256),
            'keywords' => $keywords,
            'has_all_keywords' => $data['has_all_keywords'],
            'city' => Str::random(256),
            'type' => $data['type'],
            'job_types' => [],
            'job_styles' => [],
        ]);

        $response->assertJsonValidationErrors([
            'name', 'city', 'job_types', 'job_styles',
        ]);

        $data = Alert::factory()->make()->toArray();
        $keywords = Keyword::factory(3)->make()->pluck('word')->implode(',');

        $response = $this->actingAs($user)->put("/alerts/{$alert->id}", [
            'country_id' => $data['country_id'],
            'name' => $data['name'],
            'keywords' => $keywords,
            'has_all_keywords' => $data['has_all_keywords'],
            'city' => $data['city'],
            'type' => $data['type'],
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

        $alert = $user->alerts()->save(
            Alert::factory()->make()
        );

        $response = $this->actingAs($user)->delete("/alerts/{$alert->id}");

        $response->assertNoContent();
    }
}
