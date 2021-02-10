<?php

namespace Tests\Feature;

use App\Jobs\SendNewJobsDailyNotifications;
use App\Models\Alert;
use App\Models\Country;
use App\Models\Job;
use App\Models\Keyword;
use App\Models\Order;
use App\Notifications\NewJobsYesterday;
use Database\Seeders\CountriesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SendNewJobsDailyNotificationsJobTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(CountriesSeeder::class);
    }

    public function test_notification_is_sent_correctly()
    {
        Notification::fake();

        $job = $this->job();

        $alertOne = Alert::factory()->create([
            'country_id' => $job->country_id,
            'has_all_keywords' => true,
            'city' => 'iran',
            'type' => Alert::TYPE_DAILY,
            'job_types' => [Job::TYPE_FULL_TIME, Job::TYPE_PART_TIME],
            'job_styles' => [Job::STYLE_OFFICE, Job::STYLE_REMOTE],
        ]);

        Keyword::factory()->for($alertOne)->create([
            'word' => 'itle',
        ]);

        Keyword::factory()->for($alertOne)->create([
            'word' => 'descrip',
        ]);

        $alertTwo = Alert::factory()->create([
            'country_id' => $job->country_id,
            'has_all_keywords' => false,
            'city' => 'iran',
            'type' => Alert::TYPE_DAILY,
            'job_types' => [Job::TYPE_FULL_TIME, Job::TYPE_PART_TIME],
            'job_styles' => [Job::STYLE_OFFICE, Job::STYLE_REMOTE],
        ]);

        Keyword::factory()->for($alertTwo)->create([
            'word' => 'ustom',
        ]);

        Keyword::factory()->for($alertTwo)->create([
            'word' => 'wrong-keyword',
        ]);

        dispatch(new SendNewJobsDailyNotifications());

        Notification::assertSentTo([$alertOne->user, $alertTwo->user], NewJobsYesterday::class);
    }

    protected function job(): Job
    {
        $job = Job::factory()->create([
            'country_id' => Country::query()->inRandomOrder()->first(),
            'title' => 'Custom Title',
            'description' => 'Custom Description',
            'city' => 'Tirana',
            'type' => Job::TYPE_FULL_TIME,
            'style' => Job::STYLE_OFFICE,
        ]);

        Order::factory()->for($job)->create([
            'capture_id' => 'fake-capture-id',
            'paid_at' => now()->subDay(),
        ]);

        return $job;
    }
}
