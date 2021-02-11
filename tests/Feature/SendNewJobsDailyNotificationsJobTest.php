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

    public function test_notification_is_not_sent_if_country_does_not_match()
    {
        Notification::fake();

        $job = $this->job();

        $alert = Alert::factory()->create([
            'country_id' => Country::query()->where('id', '<>', $job->country_id)->inRandomOrder()->first(),
            'has_all_keywords' => true,
            'city' => 'iran',
            'type' => Alert::TYPE_DAILY,
            'job_types' => [Job::TYPE_FULL_TIME, Job::TYPE_PART_TIME],
            'job_styles' => [Job::STYLE_OFFICE, Job::STYLE_REMOTE],
        ]);

        Keyword::factory()->for($alert)->create([
            'word' => 'ustom',
        ]);

        Keyword::factory()->for($alert)->create([
            'word' => 'descrip',
        ]);

        dispatch(new SendNewJobsDailyNotifications());

        Notification::assertNotSentTo($alert->user, NewJobsYesterday::class);
    }

    public function test_notification_is_not_sent_if_city_does_not_match()
    {
        Notification::fake();

        $job = $this->job();

        $alert = Alert::factory()->create([
            'country_id' => $job->country_id,
            'has_all_keywords' => true,
            'city' => 'wrong-city',
            'type' => Alert::TYPE_DAILY,
            'job_types' => [Job::TYPE_FULL_TIME, Job::TYPE_PART_TIME],
            'job_styles' => [Job::STYLE_OFFICE, Job::STYLE_REMOTE],
        ]);

        Keyword::factory()->for($alert)->create([
            'word' => 'ustom',
        ]);

        Keyword::factory()->for($alert)->create([
            'word' => 'descrip',
        ]);

        dispatch(new SendNewJobsDailyNotifications());

        Notification::assertNotSentTo($alert->user, NewJobsYesterday::class);
    }

    public function test_notification_is_not_sent_if_type_does_not_match()
    {
        Notification::fake();

        $job = $this->job();

        $alert = Alert::factory()->create([
            'country_id' => $job->country_id,
            'has_all_keywords' => true,
            'city' => 'iran',
            'type' => Alert::TYPE_INSTANT,
            'job_types' => [Job::TYPE_FULL_TIME, Job::TYPE_PART_TIME],
            'job_styles' => [Job::STYLE_OFFICE, Job::STYLE_REMOTE],
        ]);

        Keyword::factory()->for($alert)->create([
            'word' => 'ustom',
        ]);

        Keyword::factory()->for($alert)->create([
            'word' => 'descrip',
        ]);

        dispatch(new SendNewJobsDailyNotifications());

        Notification::assertNotSentTo($alert->user, NewJobsYesterday::class);
    }

    public function test_notification_is_not_sent_if_job_type_does_not_match()
    {
        Notification::fake();

        $job = $this->job();

        $alert = Alert::factory()->create([
            'country_id' => $job->country_id,
            'has_all_keywords' => true,
            'city' => 'iran',
            'type' => Alert::TYPE_INSTANT,
            'job_types' => [Job::TYPE_PART_TIME, Job::TYPE_FREELANCE],
            'job_styles' => [Job::STYLE_OFFICE, Job::STYLE_REMOTE],
        ]);

        Keyword::factory()->for($alert)->create([
            'word' => 'ustom',
        ]);

        Keyword::factory()->for($alert)->create([
            'word' => 'descrip',
        ]);

        dispatch(new SendNewJobsDailyNotifications());

        Notification::assertNotSentTo($alert->user, NewJobsYesterday::class);
    }

    public function test_notification_is_not_sent_if_job_style_does_not_match()
    {
        Notification::fake();

        $job = $this->job();

        $alert = Alert::factory()->create([
            'country_id' => $job->country_id,
            'has_all_keywords' => true,
            'city' => 'iran',
            'type' => Alert::TYPE_INSTANT,
            'job_types' => [Job::TYPE_FULL_TIME, Job::TYPE_PART_TIME],
            'job_styles' => [Job::STYLE_REMOTE, Job::STYLE_OPTIONAL],
        ]);

        Keyword::factory()->for($alert)->create([
            'word' => 'ustom',
        ]);

        Keyword::factory()->for($alert)->create([
            'word' => 'descrip',
        ]);

        dispatch(new SendNewJobsDailyNotifications());

        Notification::assertNotSentTo($alert->user, NewJobsYesterday::class);
    }

    public function test_notification_is_not_sent_if_all_keywords_do_not_match()
    {
        Notification::fake();

        $job = $this->job();

        $alert = Alert::factory()->create([
            'country_id' => $job->country_id,
            'has_all_keywords' => true,
            'city' => 'iran',
            'type' => Alert::TYPE_INSTANT,
            'job_types' => [Job::TYPE_FULL_TIME, Job::TYPE_PART_TIME],
            'job_styles' => [Job::STYLE_REMOTE, Job::STYLE_OPTIONAL],
        ]);

        Keyword::factory()->for($alert)->create([
            'word' => 'ustom',
        ]);

        Keyword::factory()->for($alert)->create([
            'word' => 'wrong-keyword',
        ]);

        dispatch(new SendNewJobsDailyNotifications());

        Notification::assertNotSentTo($alert->user, NewJobsYesterday::class);
    }

    public function test_notification_is_not_sent_if_none_of_the_keywords_match()
    {
        Notification::fake();

        $job = $this->job();

        $alert = Alert::factory()->create([
            'country_id' => $job->country_id,
            'has_all_keywords' => false,
            'city' => 'iran',
            'type' => Alert::TYPE_INSTANT,
            'job_types' => [Job::TYPE_FULL_TIME, Job::TYPE_PART_TIME],
            'job_styles' => [Job::STYLE_REMOTE, Job::STYLE_OPTIONAL],
        ]);

        Keyword::factory()->for($alert)->create([
            'word' => 'wrong-keyword',
        ]);

        Keyword::factory()->for($alert)->create([
            'word' => 'another-wrong-keyword',
        ]);

        dispatch(new SendNewJobsDailyNotifications());

        Notification::assertNotSentTo($alert->user, NewJobsYesterday::class);
    }

    public function test_notification_is_not_sent_if_job_is_not_added_yesterday()
    {
        Notification::fake();

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
            'paid_at' => now(),
        ]);

        $alert = Alert::factory()->create([
            'country_id' => $job->country_id,
            'has_all_keywords' => true,
            'city' => 'iran',
            'type' => Alert::TYPE_DAILY,
            'job_types' => [Job::TYPE_FULL_TIME, Job::TYPE_PART_TIME],
            'job_styles' => [Job::STYLE_OFFICE, Job::STYLE_REMOTE],
        ]);

        Keyword::factory()->for($alert)->create([
            'word' => 'itle',
        ]);

        Keyword::factory()->for($alert)->create([
            'word' => 'descrip',
        ]);

        dispatch(new SendNewJobsDailyNotifications());

        Notification::assertNotSentTo($alert->user, NewJobsYesterday::class);
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
