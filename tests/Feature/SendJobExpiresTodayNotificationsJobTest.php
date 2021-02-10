<?php

namespace Tests\Feature;

use App\Jobs\SendJobExpiresTodayNotifications;
use App\Models\Company;
use App\Models\Job;
use App\Models\Order;
use App\Models\User;
use App\Notifications\JobExpiresToday;
use Database\Seeders\CountriesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SendJobExpiresTodayNotificationsJobTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(CountriesSeeder::class);
    }

    public function test_job_expiration_notifications_are_sent_correctly()
    {
        Notification::fake();

        $userOne = User::factory()->create();
        $userTwo = User::factory()->create();

        $companyOne = Company::factory()->for($userOne)->create();
        $companyTwo = Company::factory()->for($userTwo)->create();

        $jobOne = Job::factory()->for($companyOne)->create();
        $jobTwo = Job::factory()->for($companyTwo)->create();
        $jobThree = Job::factory()->for($companyTwo)->create();

        Order::factory()->for($jobOne)->create([
            'paid_at' => now()->subMonth(),
        ]);

        Order::factory()->for($jobTwo)->create([
            'paid_at' => now()->subMonth()->subDay(),
        ]);

        Order::factory()->for($jobThree)->create([
            'paid_at' => now()->subMonth()->addDay(),
        ]);

        dispatch(new SendJobExpiresTodayNotifications());

        Notification::assertSentTo($userOne, JobExpiresToday::class);
        Notification::assertNotSentTo($userTwo, JobExpiresToday::class);
    }
}
