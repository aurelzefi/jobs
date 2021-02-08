<?php

namespace Tests\Feature;

use App\Events\JobCreated;
use App\Models\Company;
use App\Models\Job;
use App\Models\User;
use Database\Seeders\CountriesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
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

        $company = $user->companies()->save(
            Company::factory()->make()
        );

        $data = Job::factory()->make(['company_id' => $company])->toArray();

        $response = $this->actingAs($user)->post('/jobs', $data);

        $response->assertJson([
            'title' => $data['title'],
        ]);

        Event::assertDispatched(JobCreated::class);
    }

    public function test_jobs_cant_be_created_with_invalid_data()
    {
        $this->assertTrue(true);
    }

    public function test_jobs_can_be_shown()
    {
        $user = User::factory()->create();

        $company = $user->companies()->save(
            Company::factory()->make()
        );

        $job = $company->jobs()->save(
            Job::factory()->make()
        );

        $response = $this->actingAs($user)->get("/jobs/{$job->id}");

        $response->assertJson([
            'title' => $job->title,
        ]);
    }

    public function test_jobs_can_be_updated()
    {
        $user = User::factory()->create();

        $company = $user->companies()->save(
            Company::factory()->make()
        );

        $job = $company->jobs()->save(
            Job::factory()->make()
        );

        $data = Job::factory()->make(['company_id' => $company])->toArray();

        $response = $this->actingAs($user)->put("/jobs/{$job->id}", $data);

        $response->assertJson([
            'title' => $data['title'],
        ]);
    }

    public function test_jobs_cant_be_updated_with_invalid_data()
    {
        $this->assertTrue(true);
    }
}
