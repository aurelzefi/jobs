<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Country;
use App\Models\Job;
use App\Models\Order;
use Database\Seeders\CountriesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobsDashboardControllerTest extends TestCase
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

    public function test_jobs_can_be_searched_for_query()
    {
        $company = Company::factory()->create();

        $jobOne = Job::factory()->for($company)->create([
            'title' => 'Job One Title',
            'description' => 'Job One Description',
        ]);

        $jobTwo = Job::factory()->for($company)->create([
            'title' => 'Job Two Title',
            'description' => 'Job Two Description',
        ]);

        Order::factory()->for($jobOne)->create([
            'capture_id' => 'fake-capture-id',
            'captured_at' => now()->subDays(3),
        ]);

        Order::factory()->for($jobTwo)->create([
            'capture_id' => 'fake-capture-id',
            'captured_at' => now()->subDays(2),
        ]);

        $parameters = http_build_query([
            'query' => 'One',
        ]);

        $response = $this->get("/jobs/dashboard?{$parameters}");

        $response->assertJsonCount(1, 'data');

        $response->assertJson([
            'data' => [
                [
                    'title' => $jobOne->title,
                ],
            ],
        ]);

        $parameters = http_build_query([
            'query' => 'One',
        ]);

        $response = $this->get("/jobs/dashboard?{$parameters}");

        $response->assertJsonCount(1, 'data');

        $response->assertJson([
            'data' => [
                [
                    'title' => $jobOne->title,
                ],
            ],
        ]);
    }

    public function test_jobs_can_be_searched_for_company()
    {
        $companyOne = Company::factory()->create(['name' => 'Laravel']);
        $companyTwo = Company::factory()->create(['name' => 'Toyota']);

        $jobOne = Job::factory()->for($companyOne)->create();
        $jobTwo = Job::factory()->for($companyOne)->create();

        Job::factory()->for($companyTwo)->create();
        Job::factory()->for($companyTwo)->create();

        Order::factory()->for($jobOne)->create([
            'capture_id' => 'fake-capture-id',
            'captured_at' => now()->subDays(3),
        ]);

        Order::factory()->for($jobTwo)->create([
            'capture_id' => 'fake-capture-id',
            'captured_at' => now()->subDays(2),
        ]);

        $parameters = http_build_query([
            'company' => 'ave',
        ]);

        $response = $this->get("/jobs/dashboard?{$parameters}");

        $response->assertJsonCount(2, 'data');

        $response->assertJson([
            'data' => [
                [
                    'title' => $jobTwo->title,
                ],
                [
                    'title' => $jobOne->title,
                ],
            ],
        ]);
    }

    public function test_jobs_can_be_searched_for_country()
    {
        $company = Company::factory()->create();

        $jobOne = Job::factory()->for($company)->create(['country_id' => Country::query()->first()]);
        $jobTwo = Job::factory()->for($company)->create(['country_id' => Country::query()->orderByDesc('id')->first()]);

        Order::factory()->for($jobOne)->create([
            'capture_id' => 'fake-capture-id',
            'captured_at' => now()->subDays(3),
        ]);

        Order::factory()->for($jobTwo)->create([
            'capture_id' => 'fake-capture-id',
            'captured_at' => now()->subDays(2),
        ]);

        $parameters = http_build_query([
            'country_id' => $jobOne->country_id,
        ]);

        $response = $this->get("/jobs/dashboard?{$parameters}");

        $response->assertJsonCount(1, 'data');

        $response->assertJson([
            'data' => [
                [
                    'title' => $jobOne->title,
                ],
            ],
        ]);
    }

    public function test_jobs_can_be_searched_for_title()
    {
        $company = Company::factory()->create();

        $jobOne = Job::factory()->for($company)->create(['title' => 'Job One Title']);
        $jobTwo = Job::factory()->for($company)->create(['title' => 'Job Two Title']);

        Order::factory()->for($jobOne)->create([
            'capture_id' => 'fake-capture-id',
            'captured_at' => now()->subDays(3),
        ]);

        Order::factory()->for($jobTwo)->create([
            'capture_id' => 'fake-capture-id',
            'captured_at' => now()->subDays(2),
        ]);

        $parameters = http_build_query([
            'title' => 'One',
        ]);

        $response = $this->get("/jobs/dashboard?{$parameters}");

        $response->assertJsonCount(1, 'data');

        $response->assertJson([
            'data' => [
                [
                    'title' => $jobOne->title,
                ],
            ],
        ]);
    }

    public function test_jobs_can_be_searched_for_description()
    {
        $company = Company::factory()->create();

        $jobOne = Job::factory()->for($company)->create(['description' => 'Job One Description']);
        $jobTwo = Job::factory()->for($company)->create(['description' => 'Job Two Description']);

        Order::factory()->for($jobOne)->create([
            'capture_id' => 'fake-capture-id',
            'captured_at' => now()->subDays(3),
        ]);

        Order::factory()->for($jobTwo)->create([
            'capture_id' => 'fake-capture-id',
            'captured_at' => now()->subDays(2),
        ]);

        $parameters = http_build_query([
            'description' => 'One',
        ]);

        $response = $this->get("/jobs/dashboard?{$parameters}");

        $response->assertJsonCount(1, 'data');

        $response->assertJson([
            'data' => [
                [
                    'title' => $jobOne->title,
                ],
            ],
        ]);
    }

    public function test_jobs_can_be_searched_for_city()
    {
        $company = Company::factory()->create();

        $jobOne = Job::factory()->for($company)->create(['city' => 'Job One City']);
        $jobTwo = Job::factory()->for($company)->create(['city' => 'Job Two City']);

        Order::factory()->for($jobOne)->create([
            'capture_id' => 'fake-capture-id',
            'captured_at' => now()->subDays(3),
        ]);

        Order::factory()->for($jobTwo)->create([
            'capture_id' => 'fake-capture-id',
            'captured_at' => now()->subDays(2),
        ]);

        $parameters = http_build_query([
            'city' => 'One',
        ]);

        $response = $this->get("/jobs/dashboard?{$parameters}");

        $response->assertJsonCount(1, 'data');

        $response->assertJson([
            'data' => [
                [
                    'title' => $jobOne->title,
                ],
            ],
        ]);
    }

    public function test_jobs_can_be_searched_for_type()
    {
        $company = Company::factory()->create();

        $jobOne = Job::factory()->for($company)->create(['type' => 'full-time']);
        $jobTwo = Job::factory()->for($company)->create(['type' => 'part-time']);

        Order::factory()->for($jobOne)->create([
            'capture_id' => 'fake-capture-id',
            'captured_at' => now()->subDays(3),
        ]);

        Order::factory()->for($jobTwo)->create([
            'capture_id' => 'fake-capture-id',
            'captured_at' => now()->subDays(2),
        ]);

        $parameters = http_build_query([
            'types' => ['full-time', 'freelance', 'contract'],
        ]);

        $response = $this->get("/jobs/dashboard?{$parameters}");

        $response->assertJsonCount(1, 'data');

        $response->assertJson([
            'data' => [
                [
                    'title' => $jobOne->title,
                ],
            ],
        ]);
    }

    public function test_jobs_can_be_searched_for_style()
    {
        $company = Company::factory()->create();

        $jobOne = Job::factory()->for($company)->create(['style' => 'office']);
        $jobTwo = Job::factory()->for($company)->create(['style' => 'optional']);

        Order::factory()->for($jobOne)->create([
            'capture_id' => 'fake-capture-id',
            'captured_at' => now()->subDays(3),
        ]);

        Order::factory()->for($jobTwo)->create([
            'capture_id' => 'fake-capture-id',
            'captured_at' => now()->subDays(2),
        ]);

        $parameters = http_build_query([
            'styles' => ['office', 'remote'],
        ]);

        $response = $this->get("/jobs/dashboard?{$parameters}");

        $response->assertJsonCount(1, 'data');

        $response->assertJson([
            'data' => [
                [
                    'title' => $jobOne->title,
                ],
            ],
        ]);
    }

    public function test_jobs_can_be_searched_for_has_at_least_one_keyword()
    {
        $company = Company::factory()->create();

        $jobOne = Job::factory()->for($company)->create([
            'title' => 'Job One Title',
            'description' => 'Job One Description'
        ]);

        Job::factory()->for($company)->create([
            'title' => 'Job Two Title',
            'description' => 'Job Two Description',
        ]);

        Order::factory()->for($jobOne)->create([
            'capture_id' => 'fake-capture-id',
            'captured_at' => now()->subDays(3),
        ]);

        $parameters = http_build_query([
            'keywords' => ['one', 'three', 'four'],
        ]);

        $response = $this->get("/jobs/dashboard?{$parameters}");

        $response->assertJsonCount(1, 'data');

        $response->assertJson([
            'data' => [
                [
                    'title' => $jobOne->title,
                ],
            ],
        ]);
    }

    public function test_jobs_can_be_searched_for_has_all_keywords()
    {
        $company = Company::factory()->create();

        $jobOne = Job::factory()->for($company)->create([
            'title' => 'Job One Title',
            'description' => 'Job One Description'
        ]);

        Job::factory()->for($company)->create([
            'title' => 'Job Two Title',
            'description' => 'Job Two Description',
        ]);

        Order::factory()->for($jobOne)->create([
            'capture_id' => 'fake-capture-id',
            'captured_at' => now()->subDays(3),
        ]);

        $parameters = http_build_query([
            'has_all_keywords' => true,
            'keywords' => ['one', 'description'],
        ]);

        $response = $this->get("/jobs/dashboard?{$parameters}");

        $response->assertJsonCount(1, 'data');

        $response->assertJson([
            'data' => [
                [
                    'title' => $jobOne->title,
                ],
            ],
        ]);
    }

    public function test_jobs_can_be_searched_for_from_created_at_date()
    {
        $company = Company::factory()->create();

        $jobOne = Job::factory()->for($company)->create(['created_at' => now()]);
        $jobTwo = Job::factory()->for($company)->create(['created_at' => now()->subDay()]);

        Job::factory()->for($company)->create(['created_at' => now()->subDays(2)]);
        Job::factory()->for($company)->create(['created_at' => now()->subDays(3)]);

        Order::factory()->for($jobOne)->create([
            'capture_id' => 'fake-capture-id',
            'captured_at' => now()->subDays(3),
        ]);

        Order::factory()->for($jobTwo)->create([
            'capture_id' => 'fake-capture-id',
            'captured_at' => now()->subDays(2),
        ]);

        $parameters = http_build_query([
            'from_created_at' => now()->subDay()->toDateString(),
        ]);

        $response = $this->get("/jobs/dashboard?{$parameters}");

        $response->assertJsonCount(2, 'data');

        $response->assertJson([
            'data' => [
                [
                    'title' => $jobTwo->title,
                ],
                [
                    'title' => $jobOne->title,
                ],
            ],
        ]);
    }

    public function test_jobs_can_be_searched_for_to_created_at_date()
    {
        $company = Company::factory()->create();

        $jobOne = Job::factory()->for($company)->create(['created_at' => now()->subDays(2)]);
        $jobTwo = Job::factory()->for($company)->create(['created_at' => now()->subDays(3)]);

        Job::factory()->for($company)->create(['created_at' => now()]);
        Job::factory()->for($company)->create(['created_at' => now()->subDay()]);

        Order::factory()->for($jobOne)->create([
            'capture_id' => 'fake-capture-id',
            'captured_at' => now()->subDays(3),
        ]);

        Order::factory()->for($jobTwo)->create([
            'capture_id' => 'fake-capture-id',
            'captured_at' => now()->subDays(2),
        ]);

        $parameters = http_build_query([
            'to_created_at' => now()->subDays(2)->toDateString(),
        ]);

        $response = $this->get("/jobs/dashboard?{$parameters}");

        $response->assertJsonCount(2, 'data');

        $response->assertJson([
            'data' => [
                [
                    'title' => $jobTwo->title,
                ],
                [
                    'title' => $jobOne->title,
                ],
            ],
        ]);
    }

    public function test_jobs_are_ordered_by_last_captured_at_desc()
    {
        $company = Company::factory()->create();

        $jobOne = Job::factory()->for($company)->create();
        $jobTwo = Job::factory()->for($company)->create();
        $jobThree = Job::factory()->for($company)->create();
        $jobFour = Job::factory()->for($company)->create();

        Order::factory()->for($jobOne)->create([
            'capture_id' => 'fake-capture-id',
            'captured_at' => now()->subDays(3),
        ]);

        Order::factory()->for($jobOne)->create([
            'capture_id' => 'fake-capture-id',
            'captured_at' => now()->subDays(2),
        ]);

        Order::factory()->for($jobTwo)->create([
            'capture_id' => 'fake-capture-id',
            'captured_at' => now()->subDay(),
        ]);

        Order::factory()->for($jobTwo)->create([
            'capture_id' => null,
            'captured_at' => now(),
        ]);

        Order::factory()->for($jobThree)->create([
            'capture_id' => null,
            'captured_at' => null,
        ]);

        Order::factory()->for($jobFour)->create([
            'capture_id' => 'fake-capture-id',
            'captured_at' => now()->subMonths(2),
        ]);

        $response = $this->get('/jobs/dashboard');

        $response->assertJsonCount(2, 'data');

        $response->assertJson([
            'data' => [
                [
                    'title' => $jobTwo->title,
                ],
                [
                    'title' => $jobOne->title,
                ],
            ],
        ]);
    }
}
