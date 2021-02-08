<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Country;
use App\Models\Job;
use App\Models\User;
use Carbon\Carbon;
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

        $company->jobs()->saveMany([
            $jobOne = Job::factory()->make([
                'title' => 'Job One Title',
                'description' => 'Job One Description',
            ]),
            $jobTwo = Job::factory()->make([
                'title' => 'Job Two Title',
                'description' => 'Job Two Description',
            ])
        ]);

        $parameters = http_build_query([
            'query' => 'One',
        ]);

        $response = $this->get("/jobs/dashboard?{$parameters}");

        $response->assertJsonCount(1);
        $response->assertJson([
            [
                'title' => $jobOne->title,
            ],
        ]);

        $parameters = http_build_query([
            'query' => 'One',
        ]);

        $response = $this->get("/jobs/dashboard?{$parameters}");

        $response->assertJsonCount(1);

        $response->assertJson([
            [
                'title' => $jobOne->title,
            ],
        ]);
    }

    public function test_jobs_can_be_searched_for_company()
    {
        $companyOne = Company::factory()->create([
            'name' => 'Laravel',
        ]);

        $companyTwo = Company::factory()->create([
            'name' => 'Toyota',
        ]);

        $companyOne->jobs()->saveMany([
            $jobOne = Job::factory()->make(),
            $jobTwo = Job::factory()->make(),
        ]);

        $companyTwo->jobs()->saveMany([
            Job::factory()->make(),
            Job::factory()->make(),
        ]);

        $parameters = http_build_query([
            'company' => 'ave',
        ]);

        $response = $this->get("/jobs/dashboard?{$parameters}");

        $response->assertJsonCount(2);

        $response->assertJson([
            [
                'title' => $jobOne->title,
            ],
            [
                'title' => $jobTwo->title,
            ],
        ]);
    }

    public function test_jobs_can_be_searched_for_country()
    {
        $company = Company::factory()->create();

        $company->jobs()->saveMany([
            $jobOne = Job::factory(['country_id' => Country::query()->first()])->make(),
            $jobTwo = Job::factory(['country_id' => Country::query()->orderByDesc('id')->first()])->make(),
        ]);

        $parameters = http_build_query([
            'country_id' => $jobOne->country_id,
        ]);

        $response = $this->get("/jobs/dashboard?{$parameters}");

        $response->assertJsonCount(1);

        $response->assertJson([
            [
                'title' => $jobOne->title,
            ],
        ]);
    }

    public function test_jobs_can_be_searched_for_title()
    {
        $company = Company::factory()->create();

        $company->jobs()->saveMany([
            $jobOne = Job::factory(['title' => 'Job One Title'])->make(),
            $jobTwo = Job::factory(['title' => 'Job Two Title'])->make(),
        ]);

        $parameters = http_build_query([
            'title' => 'One',
        ]);

        $response = $this->get("/jobs/dashboard?{$parameters}");

        $response->assertJsonCount(1);

        $response->assertJson([
            [
                'title' => $jobOne->title,
            ],
        ]);
    }

    public function test_jobs_can_be_searched_for_description()
    {
        $company = Company::factory()->create();

        $company->jobs()->saveMany([
            $jobOne = Job::factory(['description' => 'Job One Description'])->make(),
            $jobTwo = Job::factory(['description' => 'Job Two Description'])->make(),
        ]);

        $parameters = http_build_query([
            'description' => 'One',
        ]);

        $response = $this->get("/jobs/dashboard?{$parameters}");

        $response->assertJsonCount(1);

        $response->assertJson([
            [
                'title' => $jobOne->title,
            ],
        ]);
    }

    public function test_jobs_can_be_searched_for_city()
    {
        $company = Company::factory()->create();

        $company->jobs()->saveMany([
            $jobOne = Job::factory(['city' => 'Job One City'])->make(),
            $jobTwo = Job::factory(['city' => 'Job Two City'])->make(),
        ]);

        $parameters = http_build_query([
            'city' => 'One',
        ]);

        $response = $this->get("/jobs/dashboard?{$parameters}");

        $response->assertJsonCount(1);

        $response->assertJson([
            [
                'title' => $jobOne->title,
            ],
        ]);
    }

    public function test_jobs_can_be_searched_for_type()
    {
        $company = Company::factory()->create();

        $company->jobs()->saveMany([
            $jobOne = Job::factory(['type' => 'full-time'])->make(),
            $jobTwo = Job::factory(['type' => 'part-time'])->make(),
        ]);

        $parameters = http_build_query([
            'types' => 'full-time,freelance,contract',
        ]);

        $response = $this->get("/jobs/dashboard?{$parameters}");

        $response->assertJsonCount(1);

        $response->assertJson([
            [
                'title' => $jobOne->title,
            ],
        ]);
    }

    public function test_jobs_can_be_searched_for_style()
    {
        $company = Company::factory()->create();

        $company->jobs()->saveMany([
            $jobOne = Job::factory(['style' => 'office'])->make(),
            $jobTwo = Job::factory(['style' => 'optional'])->make(),
        ]);

        $parameters = http_build_query([
            'styles' => 'office,remote',
        ]);

        $response = $this->get("/jobs/dashboard?{$parameters}");

        $response->assertJsonCount(1);

        $response->assertJson([
            [
                'title' => $jobOne->title,
            ],
        ]);
    }

    public function test_jobs_can_be_searched_for_has_at_least_one_keyword()
    {
        $company = Company::factory()->create();

        $company->jobs()->saveMany([
            $jobOne = Job::factory([
                'title' => 'Job One Title',
                'description' => 'Job One Description'
            ])->make(),
            $jobTwo = Job::factory([
                'title' => 'Job Two Title',
                'description' => 'Job Two Description',
            ])->make(),
        ]);

        $parameters = http_build_query([
            'keywords' => 'one,three,four',
        ]);

        $response = $this->get("/jobs/dashboard?{$parameters}");

        $response->assertJsonCount(1);

        $response->assertJson([
            [
                'title' => $jobOne->title,
            ]
        ]);
    }

    public function test_jobs_can_be_searched_for_has_all_keywords()
    {
        $company = Company::factory()->create();

        $company->jobs()->saveMany([
            $jobOne = Job::factory([
                'title' => 'Job One Title',
                'description' => 'Job One Description'
            ])->make(),
            $jobTwo = Job::factory([
                'title' => 'Job Two Title',
                'description' => 'Job Two Description',
            ])->make(),
        ]);

        $parameters = http_build_query([
            'has_all_keywords' => true,
            'keywords' => 'one,description',
        ]);

        $response = $this->get("/jobs/dashboard?{$parameters}");

        $response->assertJsonCount(1);

        $response->assertJson([
            [
                'title' => $jobOne->title,
            ]
        ]);
    }

    public function test_jobs_can_be_searched_for_from_created_at_date()
    {
        $company = Company::factory()->create();

        $company->jobs()->saveMany([
            $jobOne = Job::factory(['created_at' => now()])->make(),
            $jobTwo = Job::factory(['created_at' => now()->subDays(3)])->make(),
        ]);

        $parameters = http_build_query([
            'from_created_at' => now()->subDay()->toDateString(),
        ]);

        $response = $this->get("/jobs/dashboard?{$parameters}");

        $response->assertJsonCount(1);

        $response->assertJson([
            [
                'title' => $jobOne->title,
            ]
        ]);
    }

    public function test_jobs_can_be_searched_for_to_created_at_date()
    {
        $company = Company::factory()->create();

        $company->jobs()->saveMany([
            $jobOne = Job::factory(['created_at' => now()->subDay()])->make(),
            $jobTwo = Job::factory(['created_at' => now()])->make(),
        ]);

        $parameters = http_build_query([
            'to_created_at' => now()->subDay()->toDateString(),
        ]);

        $response = $this->get("/jobs/dashboard?{$parameters}");

        $response->assertJsonCount(1);

        $response->assertJson([
            [
                'title' => $jobOne->title,
            ]
        ]);
    }
}
