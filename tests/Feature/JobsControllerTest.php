<?php

namespace Tests\Feature;

use App\Events\JobCreated;
use App\Models\Company;
use App\Models\Job;
use App\Models\User;
use Database\Seeders\CountriesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
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

    public function test_jobs_can_be_listed()
    {
        $user = User::factory()
            ->has(
                Company::factory()
                    ->has(Job::factory()->count(3))
            )
            ->create();

        $response = $this->actingAs($user)->get('/jobs');

        $response->assertJsonCount(3);
    }

    public function test_jobs_can_be_created()
    {
        Event::fake();

        $user = User::factory()->create();

        $company = Company::factory()->for($user)->create();

        $job = Job::factory()->for($company)->make();

        $response = $this->actingAs($user)->post('/jobs', $job->toArray());

        $response->assertJson([
            'title' => $job->title,
        ]);

        Event::assertDispatched(JobCreated::class);
    }

    public function test_jobs_cant_be_created_with_invalid_data()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/jobs');

        $response->assertJsonValidationErrors([
            'company_id', 'country_id', 'title', 'description', 'city', 'type', 'style',
        ]);

        $response = $this->actingAs($user)->post('/jobs', [
            'company_id' => 'wrong-company',
            'country_id' => 'wrong-country',
            'title' => ['wrong-title'],
            'description' => ['wrong-description'],
            'city' => ['wrong-city'],
            'type' => ['wrong-type'],
            'style' => ['wrong-style'],
        ]);

        $response->assertJsonValidationErrors([
            'company_id', 'country_id', 'title', 'description', 'city', 'type', 'style',
        ]);

        $company = Company::factory()->for($user)->create();

        $job = Job::factory()->for($company)->make();

        $response = $this->actingAs($user)->post('/jobs', [
            'company_id' => $company->id,
            'country_id' => $job->country_id,
            'title' => Str::random(256),
            'description' => $job->description,
            'city' => Str::random(256),
            'type' => 'wrong-type',
            'style' => 'wrong-style',
        ]);

        $response->assertJsonValidationErrors([
            'title', 'city', 'type', 'style',
        ]);
    }

    public function test_jobs_can_be_shown()
    {
        $user = User::factory()->create();

        $company = Company::factory()->for($user)->create();

        $job = Job::factory()->for($company)->create();

        $response = $this->actingAs($user)->get("/jobs/{$job->id}");

        $response->assertJson([
            'title' => $job->title,
        ]);
    }

    public function test_jobs_can_be_updated()
    {
        $user = User::factory()->create();

        $company = Company::factory()->for($user)->create();

        $job = Job::factory()->for($company)->create();

        $newJob = Job::factory()->for($company)->make();

        $response = $this->actingAs($user)->put("/jobs/{$job->id}", $newJob->toArray());

        $response->assertJson([
            'title' => $newJob->title,
        ]);
    }

    public function test_jobs_cant_be_updated_with_invalid_data()
    {
        $user = User::factory()->create();

        $company = Company::factory()->for($user)->create();

        $job = Job::factory()->for($company)->create();

        $response = $this->actingAs($user)->put("/jobs/{$job->id}");

        $response->assertJsonValidationErrors([
            'company_id', 'country_id', 'title', 'description', 'city', 'type', 'style',
        ]);

        $response = $this->actingAs($user)->put("/jobs/{$job->id}", [
            'company_id' => 'wrong-company',
            'country_id' => 'wrong-country',
            'title' => ['wrong-title'],
            'description' => ['wrong-description'],
            'city' => ['wrong-city'],
            'type' => ['wrong-type'],
            'style' => ['wrong-style'],
        ]);

        $response->assertJsonValidationErrors([
            'company_id', 'country_id', 'title', 'description', 'city', 'type', 'style',
        ]);

        $newJob = Job::factory()->for($company)->make();

        $response = $this->actingAs($user)->post('/jobs', [
            'company_id' => $company->id,
            'country_id' => $newJob->country_id,
            'title' => Str::random(256),
            'description' => $newJob->description,
            'city' => Str::random(256),
            'type' => 'wrong-type',
            'style' => 'wrong-style',
        ]);

        $response->assertJsonValidationErrors([
            'title', 'city', 'type', 'style',
        ]);
    }
}
